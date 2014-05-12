<?php

namespace Frontend\OrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\OrderBundle\Entity\Orders;
use Frontend\OrderBundle\Entity\OrdersItem;
use Frontend\OrderBundle\Entity\ShippingOption;
use Frontend\OrderBundle\Entity\PaymentOption;
use Frontend\OrderBundle\Form\OrdersType;
use Frontend\OrderBundle\Form\ShippingOptionType;
use Frontend\OrderBundle\Form\PaymentOptionType;
use Frontend\ProfileBundle\Entity\Profile;
use Frontend\ProfileBundle\Form\ProfileType;
use Frontend\OrderBundle\Entity\OrdersProfileInformation;
use Frontend\OrderBundle\Form\OrdersProfileInformationType;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{    
    /*
     * Rendelés leadása
     */
    public function ordersAction(){
        $request = $this->get('request');
        //Lekérjük a munkafolyamatból a kosárban lévő termékeket
        $session = $request->getSession();
        $inCart = $session->get('cart');
        if(count($inCart) == 0){
            return $this->redirect($this->generateUrl('frontend_cart'));
        }     
        
        $user = $this->get('security.context')->getToken()->getUser();
        if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') )
            return $this->redirect($this->generateUrl('frontend_cart'));

        $profile = $user->getProfile();
        
        //Beállítjuk a rendeléshez tartozó személyes adatokat a prodfil adataira
        $ordersProfileInformation = new OrdersProfileInformation();
        $ordersProfileInformation->setName($profile->getName());
        $ordersProfileInformation->setEmail($user->getUserName());
        $ordersProfileInformation->setTelephone($profile->getTelephone());
        $ordersProfileInformation->setBillingAddressCity($profile->getBillingAddressCity());
        $ordersProfileInformation->setBillingAddressStreet($profile->getBillingAddressStreet());
        $ordersProfileInformation->setBillingAddressStreetNumber($profile->getBillingAddressStreetNumber());
        $ordersProfileInformation->setBillingAddressZipCode($profile->getBillingAddressZipCode());
        $ordersProfileInformation->setShippingAddressCity($profile->getShippingAddressCity());
        $ordersProfileInformation->setShippingAddressStreet($profile->getShippingAddressStreet());
        $ordersProfileInformation->setShippingAddressStreetNumber($profile->getShippingAddressStreetNumber());
        $ordersProfileInformation->setShippingAddressZipCode($profile->getShippingAddressZipCode());
        
        //Profil űrlap
        $ProfileForm = $this->createForm(new OrdersProfileInformationType(),$ordersProfileInformation);
        $order = new Orders();     
        //Rendelési űrlap
        $orderForm = $this->createForm(new OrdersType(), $order);   
        
        if ($request->getMethod() == 'POST') {
           $ProfileForm->bind($request);
           $orderForm->bind($request);
           if ($ProfileForm->isValid() && $orderForm->isValid()) {//a kapott űrlapok validak
               $em = $this->getDoctrine()->getManager();
               
               $productIds = array(); //Kosárban lévő termékek azonosítói
               $itemsTotal = 0;
               foreach($inCart as $key => $value){
                   $productIds[] = $key;
                   $itemsTotal += $value;
               }

               //Kosárban lévő termékek
               $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
               $orderProducts = $repo                            
                   ->createQueryBuilder('p')
                   ->where('p.id IN (:productIds)')
                   ->setParameter('productIds',$productIds)
                   ->getQuery()->getResult();
               $orderPrice = 0;
               foreach($orderProducts as $orderProduct){
                    $orderPrice += $orderProduct->getRealPrice()*$inCart[$orderProduct->getId()]; //Rendelési ár
               }

               //Rendelési adatok beállítása
               $now =  new \DateTime("now"); 
               $order->setUser($user);
               $order->setOrderedAt($now);
               $order->setItemsTotalPrice($orderPrice);
               $order->setItemsTotal($itemsTotal);                    

               //Adatok mentése az adatbázisba
               $em->persist($ordersProfileInformation);
               $order->setOrderProfileInformation($ordersProfileInformation);
               $em->persist($order);                    
               $em->flush();
                    
               $productsAssoc = array();
               foreach((array)$orderProducts as $product){
                   $productsAssoc[$product->getId()] = $product;
               }

               //Rendeléshez tartozó termékek mentése az adatbázisba
               foreach($inCart as $key => $value){
                   $ordersItem = new OrdersItem();
                   $ordersItem->setOrders($order);
                   $ordersItem->setProduct($productsAssoc[$key]);
                   $ordersItem->setUnitQuantity($value);
                   $em = $this->getDoctrine()->getManager();
                   $em->persist($ordersItem);          
                   $em->flush();
                   $order->addOrderItem($ordersItem);
               }
                $session->remove('cart'); //töröljük a kosár tartalmát
                
                //Levél küldése a vásárlónak a rendelt termékekről
                $mailer = $this->get('mailer');
                $confirmationMessage = \Swift_Message::newInstance()
                    ->setSubject('Rendelés leadása')
                    ->setFrom('noreply@marcitech.hu')
                    ->setTo($order->getOrderProfileInformation()->getEmail())
                    ->setBody(
                        $this->renderView(
                            'FrontendOrderBundle:Order:orderEmail.html.twig',
                            array('order' => $order)
                        ),
                        "text/html"
                    )
                ;
                $mailer->send($confirmationMessage); //Levél küldése
                
               return $this->redirect($this->generateUrl('frontend_cart_orders_success', array('success' => true))); //Átirányítás a sikeres rendelés weboldalra
           }  
        }
        
        return $this->render('FrontendOrderBundle:Order:order.html.twig',array(
            'ProfileForm' => $ProfileForm->createView(),
            'orderForm'=>$orderForm->createView(),
        ));
    }
    
    /*
     * Rendelés sikeres teljesítése
     */
    public function successAction(){
        return $this->render('FrontendOrderBundle:Order:success.html.twig');
    }
}
