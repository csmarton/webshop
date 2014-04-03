<?php

namespace Frontend\CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Frontend\ProfileBundle\Entity\Profile;
use Frontend\ProfileBundle\Form\ProfileType;
use Frontend\OderBundle\Entity\Order;
use Frontend\OderBundle\Entity\ShippingOption;
use Frontend\OderBundle\Entity\PaymentOption;
use Frontend\OrderBundle\Form\OrderType;
use Frontend\OrderBundle\Form\ShippingOptionType;
use Frontend\OrderBundle\Form\PaymentOptionType;

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
             if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') )
                 return $this->redirect($this->generateUrl('frontend_cart'));
                 
             $profile = $user->getProfile();
             
             $ProfileForm = $this->createForm(new ProfileType(),$profile);
             $orderForm = $this->createForm(new OrderType());
             $shippingOptionForm = $this->createForm(new ShippingOptionType());
             if ($request->getMethod() == 'POST') {
                $ProfileForm->bind($request);
                if ($ProfileForm->isValid()) { 
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($profile);
                    $em->flush();
                    /*$html = "Sikeresen megváltoztattad az adataidat!";
                        return $this->render('FrontendProfileBundle:Default:edit.html.twig', array(
                            'form' => $form->createView(),
                            'succesChanges' => $html
                            ));
                    }     */           
                }  
             }
        return $this->render('FrontendCartBundle:Cart:order.html.twig',array(
            'ProfileForm' => $ProfileForm->createView(),
            'orderForm'=>$orderForm->createView(),
            'shippingOptionForm'=>$shippingOptionForm->createView(),
        ));
    }
}
