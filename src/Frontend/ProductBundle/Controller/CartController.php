<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
    public function addAction()
    {        
                
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
        return new JsonResponse(array('success' => true,'html' => $html));
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
        return $this->render('FrontendProductBundle:Default:cart.html.twig',array('productsWithCount' => $productsWithCount));
    }
    
}
