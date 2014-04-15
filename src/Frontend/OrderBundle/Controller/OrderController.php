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
    public function ordersAction(){
        $request = $this->get('request');
        $session = $request->getSession();
        $inCart = $session->get('cart');
        if(count($inCart) == 0){
            return $this->redirect($this->generateUrl('frontend_cart'));
        }     
        $user = $this->get('security.context')->getToken()->getUser();
        if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') )
            return $this->redirect($this->generateUrl('frontend_cart'));

        $profile = $user->getProfile();
        
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
        
        $ProfileForm = $this->createForm(new OrdersProfileInformationType(),$ordersProfileInformation);
        $order = new Orders();             
        $orderForm = $this->createForm(new OrdersType(), $order);   
        
        if ($request->getMethod() == 'POST') {
           $ProfileForm->bind($request);
           $orderForm->bind($request);
           if ($ProfileForm->isValid() && $orderForm->isValid()) {
               $em = $this->getDoctrine()->getManager();



               $productIds = array();
               $itemsTotal = 0;
               foreach($inCart as $key => $value){
                   $productIds[] = $key;
                   $itemsTotal += $value;
               }

               $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
               $orderProducts = $repo                            
                   ->createQueryBuilder('p')
                   ->where('p.id IN (:productIds)')
                   ->setParameter('productIds',$productIds)
                   ->getQuery()->getResult();
               $orderPrice = 0;
               foreach($orderProducts as $orderProduct){
                   if($orderProduct->getSpecialOffer() != null){
                       $orderPrice = $orderProduct->getSpecialOffer()->getNewPrice();
                   }else{
                       $orderPrice = $orderProduct->getPrice();
                   }
               }

               $now =  new \DateTime("now"); 
               $order->setUser($user);
               $order->setOrderedAt($now);
               $order->setItemsTotalPrice($orderPrice);
               $order->setItemsTotal($itemsTotal);                    

               $em->persist($ordersProfileInformation);
               $order->setOrderProfileInformation($ordersProfileInformation);
               $em->persist($order);                    
               $em->flush();   

               $products = $repo
                           ->createQueryBuilder('p')                   
                           ->where('p.id IN (:productIds)')
                           ->setParameter('productIds',$productIds)
                           ->getQuery()->getResult();
               $productsAssoc = array();
               foreach((array)$products as $product){
                   $productsAssoc[$product->getId()] = $product;
               }

               foreach($inCart as $key => $value){
                   $ordersItem = new OrdersItem();
                   $ordersItem->setOrders($order);
                   $ordersItem->setProduct($productsAssoc[$key]);
                   $ordersItem->setUnitQuantity($value);
                   $em = $this->getDoctrine()->getManager();
                   $em->persist($ordersItem);          
                   $em->flush();
               }

               $session->remove('cart'); //töröljük a kosár tartalmát
               return $this->redirect($this->generateUrl('frontend_cart_orders_success'));
           }  
        }
        return $this->render('FrontendOrderBundle:Order:order.html.twig',array(
            'ProfileForm' => $ProfileForm->createView(),
            'orderForm'=>$orderForm->createView(),
        ));
    }
    
    public function successAction(){
        return $this->render('FrontendOrderBundle:Order:success.html.twig');
    }
}
