<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Frontend\ProductBundle\Entity\Propertys;
use Frontend\ProductBundle\Form\PropertysType;
class PropertyController extends Controller
{
    /*
     * Tulajdonságok listázása
     */
    public function listAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        
        //OLDALAK
        $page = (int)$request->query->get('page');
        if($page == NULL){
             $page = 1;
        }        
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
            $by = "asc";die;
        }
        
        $propertys = $this->getDoctrine()->getRepository('FrontendProductBundle:Propertys')->createQueryBuilder('p')
                ->select('p')
                ->where('p.deletedAt is NULL ');
        
        
        $filterId = "";
        $filterName = "";
        $filterMainCategory = "";
        $parameters = "";
        
        //Szűrés
        if ($request->getMethod() == 'GET') {
            $filterId = $request->query->get('filterId');
            $filterName = $request->query->get('filterName');
            $filterMainCategory = $request->query->get('filterMainCategory');
            $parameters .= "&filterId="; 
            if($filterId!= ""){ //Azonosító alapján
                $propertys = $propertys
                    ->andWhere('p.id = :id')
                    ->setParameter('id', (int)$filterId); 
                $parameters .= $filterId;
            }
            $parameters .= "&filterName=";
            if($filterName!= ""){ //Név alapján
                $propertys = $propertys
                    ->andWhere('p.name LIKE :name')
                    ->setParameter('name', "%".$filterName."%");
                $parameters .= $filterName;
            }
            $parameters .= "&filterMainCategory=";
            if($filterMainCategory!= ""){ //Kategória alapján
                $propertys = $propertys
                    ->andWhere('p.mainCategory = :filterMainCategory')
                    ->setParameter('filterMainCategory', (int)$filterMainCategory);
                $parameters .= $filterMainCategory;
            }
        }
        //Rendezés
        if($order == "id"){
            $propertys = $propertys
                ->orderBy('p.id',$by);
        }else if($order == "name"){
            $propertys = $propertys
                ->orderBy('p.name',$by);
        }else if($order == "mainCategory"){
            $propertys = $propertys
                ->orderBy('p.mainCategory',$by);
        }else{
            $propertys = $propertys
                ->orderBy('p.id',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($propertys->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        
        $propertys = $propertys
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        $mainCategorys = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Property:propertyList.html.twig', array(
            'filterId'=> $filterId,
            'filterName'=> $filterName,
            'filterMainCategory'=> $filterMainCategory,
            'mainCategorys' => $mainCategorys,
            'propertys' => $propertys,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));
    }
    
    /*
     * Tulajdonságok szerkesztése és új tulajdonság
     */
    public function newAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        $propertyId = $request->query->get('propertyId');
        if($propertyId == null){ //Új tulajdonság
            $property = new Propertys();
            
        }else{
            $property = $this->getDoctrine()->getRepository('FrontendProductBundle:Propertys')->findOneById($propertyId); //Tulajdonság lekérése
        }
       
        $form = $this->createForm(new PropertysType(),$property);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                if($property->getOrderValue() == NULL){
                   $property->setOrderValue(100); 
                }
                $now = new \DateTime("now"); 
                $property->setCreatedAt($now);
                $property->setUpdatedAt($now);
                $em = $this->getDoctrine()->getManager();
                $em->persist($property);
                $em->flush();               
                return $this->redirect($this->generateUrl('backend_admin_property'));
            }
        }    
        
        return $this->render('BackendAdminBundle:Property:propertyNew.html.twig', array(
            'form' => $form->createView(),
            'propertyId'=>$propertyId,
            'property' => $property,
        ));
    }
    
    /*
     * Tulajdonság törlése
     */
     public function removeAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $propertyId = $request->request->get('propertyId');
            
            $property = $this->getDoctrine()->getRepository('FrontendProductBundle:Propertys')->findOneById($propertyId);
            $propertyName = $property->getName();
            $now = new \DateTime("now"); 
            $property->setDeletedAt($now);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($property);
            $em->flush();
            
            return new JsonResponse(array('success' => true,'propertyName' => $propertyName));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
