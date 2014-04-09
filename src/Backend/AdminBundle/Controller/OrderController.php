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
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
             $filterName = $request->request->get('filterName');
             $filterDate =  $request->request->get('filterDate');
             $filterFulfill =  $request->request->get('filterFulfill');
             $orders = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->createQueryBuilder('o')
                    ->select('o')
                    ->leftJoin("o.user",'u')
                    ->leftJoin("u.profile",'p');
             
             if($filterName != ""){
                 $orders = $orders
                         ->andWhere('p.name LIKE :filterName')
                         ->setParameter('filterName', '%'.$filterName.'%');
             }        
             if($filterDate != ""){
                 $orders = $orders
                         ->andWhere('o.orderedAt LIKE :filterDate')
                         ->setParameter('filterDate', '%'.$filterDate.'%');
             }  
             if($filterFulfill == "no"){
                 $orders = $orders
                         ->andWhere('o.performedAt IS NULL');
             }  
             else if($filterFulfill == "yes"){
                 $orders = $orders
                         ->andWhere('o.performedAt IS NOT NULL');
             }  
                    
                    $orders = $orders
                            ->orderBy('o.orderedAt')                            
                            ->getQuery()->getResult();
             
             $html = $this->renderView('BackendAdminBundle:Order:listOrderTable.html.twig', array(
                        'orders' => $orders));
             
             return new JsonResponse(array('success' => true, 'html'=>$html));
         }
        $orders = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->createQueryBuilder('o')
                    ->select('o')
                    ->orderBy('o.orderedAt','DESC') 
                    ->getQuery()->getResult();
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