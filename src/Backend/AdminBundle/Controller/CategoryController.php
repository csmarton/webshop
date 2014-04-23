<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\CategoryType;
use Frontend\ProductBundle\Form\MainCategoryType;
use Frontend\ProductBundle\Entity\Category;
use Frontend\ProductBundle\Entity\MainCategory;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    public function indexAction()
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
        
        $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->createQueryBuilder('c')
                ->select('c');
        $filterId = "";
        $filterName = "";
        $filterMainCategory = "";
        $parameters = "";
        if ($request->getMethod() == 'GET') {
            $filterId = $request->query->get('filterId');
            $filterName = $request->query->get('filterName');
            $filterMainCategory = $request->query->get('filterMainCategory');
            $parameters .= "&filterId=";
            if($filterId!= ""){
                $categorys = $categorys
                    ->andWhere('c.id = :id')
                    ->setParameter('id', (int)$filterId); 
                $parameters .= $filterId;
            }
            $parameters .= "&filterName=";
            if($filterName!= ""){
                $categorys = $categorys
                    ->andWhere('c.name LIKE :name')
                    ->setParameter('name', "%".$filterName."%");
                $parameters .= $filterName;
            }
            $parameters .= "&filterMainCategory=";
            if($filterMainCategory!= ""){
                $categorys = $categorys
                    ->leftJoin('c.mainCategory','mc')    
                    ->andWhere('mc.id = :cat')
                    ->setParameter('cat', (int)$filterMainCategory);
                $parameters .= $filterMainCategory;
            }            
        }
        if($order == "id"){
            $categorys = $categorys
                ->orderBy('c.id',$by);
        }else if($order == "name"){
            $categorys = $categorys
                ->orderBy('c.name',$by);
        }else if($order == "mainCategory"){
            $categorys = $categorys
                ->orderBy('c.mainCategory',$by);
        }else{
            $categorys = $categorys
                ->orderBy('c.id',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($categorys->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        $categorys = $categorys
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        
        $mainCategorys = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        
        return $this->render('BackendAdminBundle:Category:categoryList.html.twig', array(
            //'form' => $form->createView(),
            'categorys' => $categorys,
            'mainCategorys' => $mainCategorys,
            'filterId'=> $filterId,
            'filterName'=> $filterName,
            'filterMainCategory'=> $filterMainCategory,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));        
    }
    
    public function newAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        $categoryId = $request->query->get('categoryId');
        $mainCategoryId = $request->query->get('mainCategoryId');
        if($categoryId == null){
            $category = new Category();
            if($mainCategoryId != null){
                $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
                if($mainCategory != null){
                    $category->setMainCategory($mainCategory);
                }
            }
        }else{
            $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categoryId);
        }
       
        $form = $this->createForm(new CategoryType(),$category);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {     
               if (isset($form['name'])) {
                    $productName = $form['name']->getData();
               }

               
               $category->setName($productName);
               $em = $this->getDoctrine()->getManager();
               $em->persist($category);
               $em->flush();
               
               return $this->redirect($this->generateUrl('backend_admin_category'));
            }
        }    
        
        return $this->render('BackendAdminBundle:Category:categoryNew.html.twig', array(
            'form' => $form->createView(),
            'categoryId'=>$categoryId,
            'category' => $category,
        ));
    }
    
     public function removeAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $categoryId = $request->request->get('categoryId');
            
            $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categoryId);
            $categoryName = $category->getName();
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($category);
            $em->flush();
            
            return new JsonResponse(array('success' => true,'categoryName' => $categoryName));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
    public function mainCategoryAction(){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $mainCategorys = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        return $this->render('BackendAdminBundle:Category:mainCategoryList.html.twig', array(
            'mainCategorys' => $mainCategorys
        ));
    }
    
    public function mainCategoryNewAction(){ 
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        $mainCategoryId = $request->query->get('mainCategoryId');
        $categorys = null;
        if($mainCategoryId == null){
            $mainCategory = new MainCategory();        
        }else{
            $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
            $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findByMainCategory($mainCategory);
        }
        
        
        
        $form = $this->createForm(new MainCategoryType(),$mainCategory);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {     
               
               $em = $this->getDoctrine()->getManager();
               $em->persist($mainCategory);
               $em->flush();
               
               if($mainCategoryId == null){
                    return $this->redirect($this->generateUrl('backend_admin_main_category'));
               }else{
                    return $this->redirect($this->generateUrl('backend_admin_main_category_new').'?mainCategoryId='.$mainCategoryId);
               }
            }
        }    
        
        return $this->render('BackendAdminBundle:Category:mainCategoryNew.html.twig', array(
            'form' => $form->createView(),
            'mainCategoryId'=>$mainCategoryId,
            'mainCategory' => $mainCategory,
            'categorys' => $categorys
        ));
    }
    
    public function removeMainCategoryAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $mainCategoryId = $request->request->get('mainCategoryId');
            
            $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
            $mainCategoryName = $mainCategory->getName();
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($mainCategory);
            $em->flush();
            
            return new JsonResponse(array('success' => true,'mainCategoryName' => $mainCategoryName));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
