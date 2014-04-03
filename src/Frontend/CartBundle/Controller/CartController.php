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
        return $this->render('FrontendCartBundle:Cart:cart.html.twig',array('productsWithCount' => $productsWithCount));
    }
    
    public function orderAction(){
        $request = $this->get('request');
             $user = $this->get('security.context')->getToken()->getUser();
             if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY') )
                 return $this->redirect($this->generateUrl('frontend_cart'));
                 
             $profile = $user->getProfile();
             
             $form = $this->createForm(new ProfileType(),$profile);
        
             if ($request->getMethod() == 'POST') {
                $form->bind($request);
                if ($form->isValid()) { 
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($profile);
                    $em->flush();
                    $html = "Sikeresen megváltoztattad az adataidat!";
                        return $this->render('FrontendProfileBundle:Default:edit.html.twig', array(
                            'form' => $form->createView(),
                            'succesChanges' => $html
                            ));
                    }                
             }    
        return $this->render('FrontendCartBundle:Cart:order.html.twig',array(
            'form' => $form->createView()
        ));
    }
}
