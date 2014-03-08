<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\ProductType;
use Frontend\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
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
        $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById(1);
        var_dump($product->getProductImages()->getPath());
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
               if (isset($form['price'])) {
                    $productPrice = $form['price']->getData();
               }
               $createdTime = new \DateTime("now");
               
               $product->setName($productName);
               $product->setPrice($productPrice);
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
            'productId'=>$productId
        ));
    }
    
     public function removeAction(){
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $productId = $request->query->get('productId');
            if($productId == null){
                $product = new Product();        
            }else{
                $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
            }
            return new JsonResponse(array('success' => true));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
