<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\OrderBundle\Entity\Order;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    /*
     * Termékek listázása
     */
    public function listAction()
    {   
        $orders = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->findAll();
        return $this->render('BackendAdminBundle:Order:list.html.twig', array(
            'orders' => $orders
        ));
    }
    
    public function listMoreAction($orderId){
        
        $order = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->findOneById($orderId);
        return $this->render('BackendAdminBundle:Order:listMore.html.twig', array(
            'order' => $order
        ));
    }
    
    public function fulfillOrderAction($orderId){
        $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            
            $order = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->findOneById($orderId);
            $orderItems = $order->getOrderItems();
            
            $cantFullfillProduct = array();
            $canFulfill = true;
            foreach($orderItems as $orderItem){
                if($orderItem->getProduct()->getInStock() < $orderItem->getUnitQuantity()){
                    $cantFullfillProduct[] = $orderItem->getProduct()->getId();
                    $canFulfill = false;
                }
            }
            /*$em = $this->getDoctrine()->getEntityManager();
            $em->remove($product);
            $em->flush();*/
    
            return new JsonResponse(array('success' => true,'canFulfill'=>$canFulfill, 'cantFullfillProduct'=>$cantFullfillProduct ));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
}