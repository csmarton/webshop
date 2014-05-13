<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\OrderBundle\Entity\Order;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    /*
     * Rendelések listázása
     */
    public function listAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        
        $page = (int)$request->query->get('page');
        if($page == NULL){
             $page = 1;
        }
        //OLDALAK
        $maxResult = (int)$request->query->get('maxResult');
        if($maxResult == NULL){
             $maxResult = 10;
        }
        
        //RENDEZÉS
        $order = $request->query->get('order');
        $by = $request->query->get('by');
        if($order == NULL){
             $order = "id";
             $by= "asc";
        }
        if($by != "asc" && $by != "desc"){
            $by = "asc";
        }
        
        $orders = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->createQueryBuilder('p')
                ->select('p');
        $filterId = "";
        $filterName = "";
        $filterDate = "";
        $filterFulfill = "";
        $parameters = "";
        //Szűrés
        if ($request->getMethod() == 'GET') {
            $filterId = $request->query->get('filterId');
            $filterName = $request->query->get('filterName');
            $filterDate = $request->query->get('filterDate');
            $filterFulfill = $request->query->get('filterFulfill');
            $parameters .= "&filterId=";
            if($filterId!= ""){ //Azonosító alapján
                $orders = $orders
                    ->andWhere('p.id = :id')
                    ->setParameter('id', (int)$filterId); 
                $parameters .= $filterId;
            }
            $parameters .= "&filterName=";
            if($filterName!= ""){ //Név alapján
                $orders = $orders                        
                    ->leftJoin('p.orderProfileInformation', 'prof')
                    ->andWhere('prof.name LIKE :name')
                    ->setParameter('name', "%".$filterName."%");
                $parameters .= $filterName;
            }
            $parameters .= "&filterFulfill=";
            if($filterFulfill!= ""){ //Teljesített -e
                if($filterFulfill == "yes"){
                    $orders = $orders
                        ->andWhere('p.performedAt IS NOT NULL');
                }else{
                    $orders = $orders
                        ->andWhere('p.performedAt IS NULL');
                }  
                $parameters .= $filterFulfill;
            }
            $parameters .= "&filterDate="; 
            if($filterDate!= ""){ //Dátum alapján
                $orders = $orders
                    ->andWhere('p.orderedAt LIKE :filterDate')
                    ->setParameter('filterDate', '%'.$filterDate.'%');
                $parameters .= $filterDate;
            }            
        }
        //Rendezés
        if($order == "id"){
            $orders = $orders
                ->orderBy('p.id',$by);
        }else if($order == "name"){
            $orders = $orders
                ->leftJoin('p.orderProfileInformation', 'pr')
                ->orderBy('pr.name',$by);
        }else if($order == "date"){
            $orders = $orders
                ->orderBy('p.orderedAt',$by);
        }else{
            $orders = $orders
                ->orderBy('p.id',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($orders->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        
        $orders = $orders
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        
        return $this->render('BackendAdminBundle:Order:orderList.html.twig', array(
            'orders' => $orders,
            'filterId'=> $filterId,
            'filterName'=> $filterName,
            'filterDate'=> $filterDate,
            'filterFulfill'=> $filterFulfill,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));        
         
    }
    
    /*
     * Rendelésekről bővebben
     */
    public function listMoreAction($orderId){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $order = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->findOneById($orderId);
        return $this->render('BackendAdminBundle:Order:OrderListMore.html.twig', array(
            'order' => $order
        ));
    }
    
    /*
     * Rendelés teljesítése
     */
    public function fulfillOrderAction($orderId){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $html = "";
            $canFulfill = true;
            
            $order = $this->getDoctrine()->getRepository('FrontendOrderBundle:Orders')->findOneById($orderId);
            $orderItems = $order->getOrderItems();
            $cantFullfillOrderItems = array();

            //megnézzük, hogy teljesíthető -e a rendelés, a raktáron van -e elegendő mennyiség minden termékből
                foreach($orderItems as $orderItem){
                    if($orderItem->getProduct()->getInStock() < $orderItem->getUnitQuantity()){
                        $cantFullfillOrderItems[] = $orderItem;
                        $canFulfill = false;
                    }
                }

                $em = $this->getDoctrine()->getEntityManager();
                if($canFulfill){//Teljesíthető
                    //Levonjuk a raktárkészletből 
                    foreach($orderItems as $orderItem){
                        $product = $orderItem->getProduct();
                        $inStock = $product->getInStock();
                        $product->setInStock($inStock - $orderItem->getUnitQuantity());         
                        $em->persist($product);
                    }
                    $now = new \DateTime('now');
                    $order->setPerformedAt($now);
                    $em->persist($order);
                    $em->flush();
                }

                $html = $this->renderView('BackendAdminBundle:Order:cantFullfillOrderProduct.html.twig', array(
                    'cantFullfillOrderItems' => $cantFullfillOrderItems
                 ));
                
            return new JsonResponse(array('success' => true,'canFulfill'=>$canFulfill, 'html' => $html ));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
}