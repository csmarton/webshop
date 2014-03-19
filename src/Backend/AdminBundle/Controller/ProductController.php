<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\ProductType;
use Frontend\ProductBundle\Form\ProductPropertyType;
use Frontend\ProductBundle\Entity\Product;
use Frontend\ProductBundle\Entity\ProductProperty;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends Controller
{
    public function listAction()
    {   
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findAll();
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Product:list.html.twig', array(
            //'form' => $form->createView(),
            'products' => $products
        ));
    }
    
    public function newAction()
    {   
        $request = $this->get('request');
        $productId = $request->query->get('productId');

        if($productId == null){
            $product = new Product();        
        }else{
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
        }
        $form = $this->createForm(new ProductType(),$product);
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) { 
               if (isset($form['name'])) {
                        $productName = $form['name']->getData();
               }
               if (isset($form['grossSalary'])) {
                    $grossSalary = $form['grossSalary']->getData();
               }
               if (isset($form['categorys'])) {
                    $categorys = $form['categorys']->getData();
               }
               
               $createdTime = new \DateTime("now");               
               $product->setName($productName);
               $product->setCategory($categorys->getId());
               $product->setGrossSalary($grossSalary);
               $product->setCreatedAt($createdTime);
               $product->setUpdatedAt($createdTime);
               
               $em = $this->getDoctrine()->getManager();
               $em->persist($product);
               $em->flush();               
               
               return $this->redirect($this->generateUrl('backend_admin_product'));
            }
        }    
                

        
        return $this->render('BackendAdminBundle:Product:new.html.twig', array(
            'form' => $form->createView(),
            'productId'=>$productId,
            'product' => $product
        ));
    }
    
     public function removeAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
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
               
               return $this->redirect($this->generateUrl('backend_admin_product').'?productId='.$productId. "#propertytable");
            }
        }    
        
        return $this->render('BackendAdminBundle:Product:propertys.html.twig', array(
            'form' => $form->createView(),
            'productPropertys' => $productPropertys,
            'productId' => $productId
        ));
    }
    
    public function propertyEditAction(){
        $request = $this->get('request');
        $productId = $request->query->get('productId');
        $productPropertyId = $request->query->get('productPropertyId');
        
        $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
        
        if($productPropertyId == null){ //új tulajdonság létrehozása
            $productProperty = new ProductProperty(); 
            $productProperty->setProduct($product);
            
        }else{
            $productProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->findOneById($productPropertyId);            
        } 
        
        $form = $this->createForm(new ProductPropertyType(), $productProperty);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) { 
              
               $em = $this->getDoctrine()->getManager();
               $em->persist($productProperty);
               $em->flush();               
               
               return $this->redirect($this->generateUrl('backend_admin_product_new').'?productId='.$productId . '#propertytable');
            }
        }    
        
        return $this->render('BackendAdminBundle:Product:new_property.html.twig', array(
            'form' => $form->createView(),
            'productPropertyId' => $productPropertyId,
            'product' => $product,
            //'productPropertys' => $productPropertys
        ));
    }
    
}
