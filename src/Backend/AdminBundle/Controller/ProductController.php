<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\ProductType;
use Frontend\ProductBundle\Entity\SpecialOffers;
use Frontend\ProductBundle\Form\ProductPropertyType;
use Frontend\ProductBundle\Form\SpecialOffersType;
use Frontend\ProductBundle\Entity\Product;
use Frontend\ProductBundle\Entity\ProductProperty;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductController extends Controller
{
    /*
     * Termékek listázása
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
        
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                ->select('p');
        $filterId = "";
        $filterName = "";
        $filterActive = "";
        $filterCategory = "";
        $parameters = "";
        if ($request->getMethod() == 'GET') {
            $filterId = $request->query->get('filterId');
            $filterName = $request->query->get('filterName');
            $filterActive = $request->query->get('filterActive');
            $filterCategory = $request->query->get('filterCategroy');
            $parameters .= "&filterId=";
            if($filterId!= ""){
                $products = $products
                    ->andWhere('p.id = :id')
                    ->setParameter('id', (int)$filterId); 
                $parameters .= $filterId;
            }
            $parameters .= "&filterName=";
            if($filterName!= ""){
                $products = $products
                    ->andWhere('p.name LIKE :name')
                    ->setParameter('name', "%".$filterName."%");
                $parameters .= $filterName;
            }
            $parameters .= "&filterActive=";
            if($filterActive!= ""){
                if($filterActive == "yes"){
                    $products = $products
                        ->andWhere('p.isActive = 1');
                }else{
                    $products = $products
                        ->andWhere('p.isActive != 1');
                }  
                $parameters .= $filterActive;
            }
            $parameters .= "&filterCategory=";
            if($filterCategory!= ""){
                $products = $products
                    ->andWhere('p.categorys = :cat')
                    ->setParameter('cat', (int)$filterCategory);
                $parameters .= $filterCategory;
            }            
        }
        if($order == "id"){
            $products = $products
                ->orderBy('p.id',$by);
        }else if($order == "name"){
            $products = $products
                ->orderBy('p.name',$by);
        }else if($order == "price"){
            $products = $products
                ->orderBy('p.price',$by);
        }else{
            $products = $products
                ->orderBy('p.id',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($products->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findAll();
        $products = $products
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Product:list.html.twig', array(
            //'form' => $form->createView(),
            'products' => $products,
            'filterId'=> $filterId,
            'filterName'=> $filterName,
            'filterActive'=> $filterActive,
            'filterCategory'=> $filterCategory,
            'categorys' => $categorys,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));
    }
    
    /*
     * Új termék vagy meglévő szerkesztése
     */
    public function newAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        $productId = $request->query->get('productId');
        
        $currentCategoryId = null;
        $currentMainCategoryId = null;
        $categorys = null;
        if($productId == null){ //új termék
            $product = new Product();
        }else{//termék szerkesztése
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
            $currentCategoryId = $product->getCategory();
            $currentMainCategory = $product->getCategorys()->getMainCategory();
            $currentMainCategoryId = $currentMainCategory->getId();
            $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findByMainCategory($currentMainCategory);
        }
        
        $form = $this->createForm(new ProductType(),$product);
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) { 
               $em = $this->getDoctrine()->getManager();
               
               $categorysId = (int)$request->request->get('categorys');
               
               $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categorysId);
               
               $createdTime = new \DateTime("now"); 
               $product->setCategorys($category);
               $product->setCreatedAt($createdTime);
               $product->setUpdatedAt($createdTime);
               
               $em = $this->getDoctrine()->getEntityManager(); 
               $em->persist($product);
               $em->flush();              
               

               return $this->redirect($this->generateUrl('backend_admin_product_new').'?productId='.$product->getId());
            }
        }
        
        $mainCategorys = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        
                 

        
        return $this->render('BackendAdminBundle:Product:new.html.twig', array(
            'form' => $form->createView(),
            'productId'=>$productId,
            'product' => $product,
            'mainCategorys' => $mainCategorys,
            'categorys'=>$categorys,            
            'currentCategoryId'=>$currentCategoryId,
            'currentMainCategoryId' => $currentMainCategoryId,      
            
        ));
    }
    
    /*
     * Termékek törlése
     */
     public function removeAction(){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $productId = $request->request->get('productId');
            
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($product);
            $em->flush();
    
            return new JsonResponse(array('success' => true,'productName'=>$product->getName()));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
    /*
     * Tulajdonságok listázása
     */
    public function propertyListAction($productId){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
        $productPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->findByProduct($product);
        $mainCategory = $product->getCategorys()->getMainCategory()->getId();
        $havingProductPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                    ->select('pp')
                    ->where('pp.product = :product')
                    ->setParameter('product', $product)
                    ->getQuery()->getResult();
        $havingProductPropertyId = array();
        
        foreach($havingProductPropertys as $havingProductProperty){
            $havingProductPropertyId[] =$havingProductProperty->getProperty()->getId();
        }
        $propertys = $this->getDoctrine()->getRepository('FrontendProductBundle:Propertys')->createQueryBuilder('pp')
                ->select('pp')
                ->where('pp.mainCategory = :mainCategory')                
                ->setParameter('mainCategory', $mainCategory);
        if(count($havingProductPropertyId) != 0){
            $propertys = $propertys
                    ->andWhere('pp.id NOT IN (:havingProductProperty)')
                    ->setParameter('havingProductProperty',$havingProductPropertyId);
        }
        $propertys = $propertys->getQuery()->getResult();
        
        $request = $this->get('request');
        $productProperty = new ProductProperty(); 
        $productProperty->setProduct($product);
        $form = $this->createForm(new ProductPropertyType(), $productProperty);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) { 
              
               $em = $this->getDoctrine()->getManager();
               $em->persist($productProperty);
               $em->flush();               
               
               return $this->redirect($this->generateUrl('backend_admin_product').'?productId='.$productId);
            }
        }    
        
        return $this->render('BackendAdminBundle:Product:propertys.html.twig', array(
            'form' => $form->createView(),
            'productPropertys' => $productPropertys,
            'productId' => $product->getId(),
            'propertys' => $propertys
        ));
    }
    
    /*
     * Új termék tulajdonság, vagy meglévő tulajdonság szerkesztése
     */
    public function propertyNewAction($productId, $propertyId){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $edit = true;
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {//form küldése során
            $request = $this->get('request');
            $propertyValue = $request->request->get('propertyValue');

            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
            $property = $this->getDoctrine()->getRepository('FrontendProductBundle:Propertys')->findOneById($propertyId);        
            $productProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                    ->select('pp')
                    ->where('pp.product = :product AND pp.property = :property ')
                    ->setParameter('product',$product)
                    ->setParameter('property',$property)
                    ->getQuery()->getOneOrNullResult();
            if($productProperty == null){
                $edit = false;
                $productProperty = new ProductProperty();
                $productProperty->setProduct($product);
                $productProperty->setProperty($property);
            }
        
            $productProperty->setValue($propertyValue);
            $em = $this->getDoctrine()->getManager();
            $em->persist($productProperty);
            $em->flush();    
            $productPropertys = array();
            $productProperyts[] = $productProperty;
            $html = $this->renderView('BackendAdminBundle:Product:propertyList.html.twig', array(
                'productPropertys' => $productProperyts,
                'productId' => $productId,
                'propertyId' => $propertyId));
            return new JsonResponse(array('success' => true, 'html' => $html, 'edit' => $edit));               
        }
        return new JsonResponse(array('success' => false));
    }
    
    /*
     * Termék tulajdonságok törlése
     */
     public function propertyRemoveAction(){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $productPropertyId = $request->request->get('productPropertyId');
            $productProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->findOneById($productPropertyId);
            $property = $productProperty->getProperty()->getName(). "  = ". $productProperty->getValue();
            $productId = $productProperty->getProduct()->getId();
            $propertyId = $productProperty->getProperty()->getId();
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($productProperty);
            $em->flush();
    
            $productPropertys = array();
            $removeProperty = $productProperty->getProperty();
            $productPropertys[] = $removeProperty;
            
            
            $html = $this->renderView('BackendAdminBundle:Product:newPropertyList.html.twig', array(
                'propertys' => $productPropertys,
                'productId' => $productId,
                'propertyId' => $propertyId));
            
            return new JsonResponse(array('success' => true,'productProperty'=> $property, 'html' =>$html));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
    public function selectCategoryAjaxAction(){
        $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $mainCategoryId = $request->request->get('mainCategoryId');
            $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
            
            $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findByMainCategory($mainCategory);
            
            $html = $this->renderView('BackendAdminBundle:Product:categorysAjax.html.twig', array('categorys' => $categorys));
            return new JsonResponse(array('success' => true,'html'=> $html));
         }else{
             return new JsonResponse(array('success' => false));
         }  
    }
    
}
