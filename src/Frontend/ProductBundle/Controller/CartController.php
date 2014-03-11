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
        $inCart = $element = $session->get('cart');
        $inCart[] = $productId;
        
        $session->set('cart', $inCart);
        
        

        $html = count($inCart);
        return new JsonResponse(array('success' => true,'html' => $html));
    }
    
}
