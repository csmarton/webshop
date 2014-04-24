<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
class LogController extends Controller
{
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
             $order = "time";
             $by= "desc";
        }
        if($by != "asc" && $by != "desc"){
            $by = "asc";
        }
        $parameters = "";
        $logs = $this->getDoctrine()->getRepository('BackendAdminBundle:Log')->createQueryBuilder('p')
                ->select('p');
        
        
        if($order == "id"){
            $logs = $logs
                ->orderBy('p.id',$by);
        }else if($order == "time"){
            $logs = $logs
                ->orderBy('p.time',$by);
        }/*else if($order == "mainCategory"){
            $propertys = $propertys
                ->orderBy('p.mainCategory',$by);
        }*/else{
            $logs = $logs
                ->orderBy('p.time',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($logs->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        
        $logs = $logs
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Log:list.html.twig', array(
            'logs' => $logs,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));
    }

}
