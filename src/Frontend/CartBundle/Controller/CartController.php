<?php

namespace Frontend\CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Frontend\ProfileBundle\Entity\Profile;
use Frontend\ProfileBundle\Form\ProfileType;


class CartController extends Controller
{
    public function addAction(){        
                
        $request = $this->get('request');
        
        $productId = $request->request->get('productId');
        
        $session = $request->getSession();
        $inCart = $session->get('cart');
        if(isset($inCart[$productId])){
            $inCart[$productId]++;
        }else{
            $inCart[$productId] = 1;
        }
        
        $session->set('cart', $inCart);
        
        $service = $this->container->get('cart_service');
        $cartCount = $service->getCartCount();
        $html = $cartCount;
        return new JsonResponse(array('success' => true,'html' => $html, 'cartCount' => $cartCount));
    }
    
    public function cartAction(){
        $request = $this->get('request');        
        $session = $request->getSession();
        $inCart = $session->get('cart');
        $productIds = array();
        foreach((array)$inCart as $key=>$value){
            $productIds[] = $key;
        }
        
        $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
        $products = $repo
                    ->createQueryBuilder('p')                   
                    ->where('p.id IN (:productIds)')
                    ->setParameter('productIds',$productIds)
                    ->getQuery()->getResult();
        $productsWithCount = array();
        foreach((array)$products as $product){
            $productsWithCount[] = array($product,$inCart[$product->getId()]);
        }
        $service = $this->container->get('cart_service');
        $cartCount = $service->getCartCount();
        return $this->render('FrontendCartBundle:Cart:cart.html.twig',array('productsWithCount' => $productsWithCount,
                'cartCount'=>$cartCount));
    }
    
        public function removeAction(){        
                
        $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $productId = $request->request->get('productId');

            $session = $request->getSession();
            $inCart = $session->get('cart');
            unset($inCart[$productId]);
            
            $session->set('cart', $inCart);
            
            $productIds[] = array();
            foreach((array)$inCart as $key=>$value){
                $productIds[] = $key;
            }
        
            $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
            $products = array();
            if(count($productIds) != 0){
                $products = $repo
                            ->createQueryBuilder('p')                   
                            ->where('p.id IN (:productIds)')
                            ->setParameter('productIds',$productIds)
                            ->getQuery()->getResult();
            }    
            $productsWithCount = array();
            foreach((array)$products as $product){
                $productsWithCount[] = array($product,$inCart[$product->getId()]);
            }
            $service = $this->container->get('cart_service');
            $cartCount = $service->getCartCount();
        
            $html = $this->renderView('FrontendCartBundle:Cart:cartItems.html.twig', array(
                        'productsWithCount' => $productsWithCount,
                        'cartCount'=>$cartCount));
            
            return new JsonResponse(array('success' => true,'html' => $html, 'cartCount' => $cartCount));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
    public function updateAction(){        
                
        $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $productId = $request->request->get('productId');
            $changeValue = $request->request->get('changeValue');
            
            $session = $request->getSession();
            $inCart = $session->get('cart');
            if($changeValue == 0){
                unset($inCart[$productId]);
            }else{
                $inCart[$productId] = $changeValue;
            }
            
            
            $session->set('cart', $inCart);
            
            $productIds[] = array();
            foreach((array)$inCart as $key=>$value){
                $productIds[] = $key;
            }
        
            $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
            $products = array();
            if(count($productIds) != 0){
                $products = $repo
                    ->createQueryBuilder('p')                   
                    ->where('p.id IN (:productIds)')
                    ->setParameter('productIds',$productIds)
                    ->getQuery()->getResult();
            }    
            $productsWithCount = array();
            foreach((array)$products as $product){
                $productsWithCount[] = array($product,$inCart[$product->getId()]);
            }
            $service = $this->container->get('cart_service');
            $cartCount = $service->getCartCount();
        
            $html = $this->renderView('FrontendCartBundle:Cart:cartItems.html.twig', array(
                        'productsWithCount' => $productsWithCount,
                        'cartCount'=>$cartCount));
            
            return new JsonResponse(array('success' => true,'html' => $html, 'cartCount' => $cartCount));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }

}
