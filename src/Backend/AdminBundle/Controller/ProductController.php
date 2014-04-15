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

class ProductController extends Controller
{
    /*
     * Termékek listázása
     */
    public function listAction()
    {   
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findAll();
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Product:list.html.twig', array(
            //'form' => $form->createView(),
            'products' => $products
        ));
    }
    
    /*
     * Új termék vagy meglévő szerkesztése
     */
    public function newAction()
    {   
        $request = $this->get('request');
        $productId = $request->query->get('productId');

        $currentMainCategoryId = null;
        $currentCategoryId = null;
        $categorys = null;
        if($productId == null){ //új termék
            $product = new Product(); 
            $specialOffer = null;
        }else{//termék szerkesztése
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
            $categoryId = $product->getCategory();
            $specialOffer = $this->getDoctrine()->getRepository('FrontendProductBundle:SpecialOffers')->findOneByProduct($product);

            $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categoryId);   
            $mainCategory = $category->getMainCategory();
            $currentMainCategoryId = $mainCategory->getId();
            $currentCategoryId = $product->getCategorys()->getId();
            $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findByMainCategory($mainCategory);
        }
        if($specialOffer == null){
                $specialOffer = new SpecialOffers();
                $specialOffer->setProduct($product);
        }
        $specialOfferForm =  $this->createForm(new SpecialOffersType,$specialOffer);
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
               
               $specialOfferForm->bind($request);
               if ($specialOfferForm->isValid()) {                    
                    $specialOffer->setProductId($product->getId());
                    if($specialOffer->getNewPrice()>0){
                        $em->persist($specialOffer);
                        $em->flush(); 
                    }else{
                        $em = $this->getDoctrine()->getEntityManager();
                        $em->remove($specialOffer);
                        $em->flush(); 
                    }
               }
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
            'currentMainCategoryId' => $currentMainCategoryId,      
            'currentCategoryId'=>$currentCategoryId,
            'categorys'=>$categorys,
            'specialOfferForm'=>$specialOfferForm->createView(),
        ));
    }
    
    /*
     * Termékek törlése
     */
     public function removeAction(){
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
        $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
        $productPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->findByProduct($product);
        
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
            'productId' => $productId
        ));
    }
    
    /*
     * Új termék tulajdonság, vagy meglévő tulajdonság szerkesztése
     */
    public function propertyEditAction(){
        $request = $this->get('request');
        $productId = $request->query->get('productId');
        $productPropertyId = $request->query->get('productPropertyId');
        
        $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
        
        if($productPropertyId == null){ //új tulajdonság létrehozása
            $productProperty = new ProductProperty(); 
            $productProperty->setProduct($product);
            
        }else{ //tulajdonság szerkesztése
            $productProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->findOneById($productPropertyId);            
        } 
        
        $form = $this->createForm(new ProductPropertyType(), $productProperty);
        
        if ($request->getMethod() == 'POST') {//form küldése során
            $form->bind($request);
            if ($form->isValid()) { 
              
               $em = $this->getDoctrine()->getManager();
               $em->persist($productProperty);
               $em->flush();               
               
               return $this->redirect($this->generateUrl('backend_admin_product_new').'?productId='.$productId);
            }
        }    
        
        return $this->render('BackendAdminBundle:Product:new_property.html.twig', array(
            'form' => $form->createView(),
            'productPropertyId' => $productPropertyId,
            'product' => $product,
            //'productPropertys' => $productPropertys
        ));
    }
    
    /*
     * Termék tulajdonságok törlése
     */
     public function propertyRemoveAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $productPropertyId = $request->request->get('productPropertyId');
            
            $productProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->findOneById($productPropertyId);
            $property = $productProperty->getProperty()->getName(). "  = ". $productProperty->getValue();
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($productProperty);
            $em->flush();
    
            return new JsonResponse(array('success' => true,'productProperty'=> $property));
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
            
            $html = $this->renderView('BackendAdminBundle:Product:categorys_ajax.html.twig', array('categorys' => $categorys));
            return new JsonResponse(array('success' => true,'html'=> $html));
         }else{
             return new JsonResponse(array('success' => false));
         }  
    }
    
}
