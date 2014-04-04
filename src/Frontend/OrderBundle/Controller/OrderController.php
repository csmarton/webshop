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
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{    
    public function ordersAction(){
        $request = $this->get('request');
             $user = $this->get('security.context')->getToken()->getUser();
             if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') )
                 return $this->redirect($this->generateUrl('frontend_cart'));
                 
             $profile = $user->getProfile();
             
             $ProfileForm = $this->createForm(new ProfileType(),$profile);
             $order = new Orders();             
             $orderForm = $this->createForm(new OrdersType(), $order);
             
             
             
             if ($request->getMethod() == 'POST') {
                $ProfileForm->bind($request);
                $orderForm->bind($request);
                if ($ProfileForm->isValid() && $orderForm->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    
                    $session = $request->getSession();
                    $inCart = $session->get('cart');
                    
                    $productIds = array();
                    $itemsTotal = 0;
                    foreach($inCart as $key => $value){
                        $productIds[] = $key;
                        $itemsTotal += $value;
                    }
                    
                    $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
                    $orderPrice = $repo                            
                        ->createQueryBuilder('p')
                        ->select("sum(p.price)")
                        ->where('p.id IN (:productIds)')
                        ->setParameter('productIds',$productIds)
                        ->getQuery()->getSingleScalarResult();
                    
                    $now =  new \DateTime("now"); 
                    $order->setUser($user);
                    $order->setOrderedAt($now);
                    $order->setItemsTotalPrice($orderPrice);
                    $order->setItemsTotal($itemsTotal);                    
                    
                    $em->persist($profile);
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
