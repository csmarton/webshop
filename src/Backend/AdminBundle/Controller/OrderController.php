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
}