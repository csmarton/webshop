<?php

namespace Frontend\CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Frontend\ProfileBundle\Entity\Profile;
use Frontend\ProfileBundle\Form\ProfileType;


class CartController extends Controller
{
    /*
     * Kosárba tevés funkció
     * A kosárban lévő termékeket asszociatív tömbként tároljuk el,
     * a kulcs maga az azonosító, az érték pedig a darabszám. A tömböt munkafolyamatba mentjük el.
     */
    public function addAction(){        
                
        $request = $this->get('request');
        
        $productId = $request->request->get('productId'); //termék azonosítója        
        $session = $request->getSession(); //munkafolyamat lekérése
        $inCart = $session->get('cart');
        if(isset($inCart[$productId])){
            $inCart[$productId]++;  //megnöveljük a darabszámot
        }else{
            $inCart[$productId] = 1; //kosárba tesszük a terméket egy darabszámmal
        }
        
        $session->set('cart', $inCart); //mentjük a munkafolyamatatot
        
        $service = $this->container->get('cart_service'); //service a kosárban lévő termékek darabszámának meghatározásához
        $cartCount = $service->getCartCount();
        
        $html = $cartCount;
        
        return new JsonResponse(array('success' => true,'html' => $html, 'cartCount' => $cartCount));
    }
    
    /*
     * Kosárba lévő termékek listázása
     * A munkafolyamatból megkapjuk a kosárban lévő termékek azonosítóját, ez
     * alapján lekérjük a megfelelő termékeket. 
     */
    public function cartAction(){
        $request = $this->get('request');   
        
        $service = $this->container->get('cart_service');
        $productsWithCount = $service->getProductWithCount();            
        $cartCount = $service->getCartCount();
        return $this->render('FrontendCartBundle:Cart:cart.html.twig',array('productsWithCount' => $productsWithCount,
                'cartCount'=>$cartCount));
    }
    
    /*
     * Kosárból való törlés
     * Töröljük a kosárból a megadott elemet, majd ismét kilistázzuk a kosár tartalmát
     */
    public function removeAction(){  
        $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $productId = $request->request->get('productId');

            $session = $request->getSession();
            $inCart = $session->get('cart');
            unset($inCart[$productId]); //Töröljük a terméket a kosárból
            
            $session->set('cart', $inCart); //Mentjük a munkafolyamatot
            
            $service = $this->container->get('cart_service');
            $productsWithCount = $service->getProductWithCount();            
            $cartCount = $service->getCartCount();
        
            //Kilistázzuk a kosár tartalmát 
            $html = $this->renderView('FrontendCartBundle:Cart:cartItems.html.twig', array( 
                        'productsWithCount' => $productsWithCount,
                        'cartCount'=>$cartCount));
            
            return new JsonResponse(array('success' => true,'html' => $html, 'cartCount' => $cartCount));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
    /*
     * Kosár tartalmának frissítése
     * Módosítjuk a megadott termék darabszámát, 
     * majd kilistázzuk újra a kosár tartalmát
     */
    public function updateAction(){        
                
        $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $productId = $request->request->get('productId');
            $changeValue = $request->request->get('changeValue');
            
            $session = $request->getSession();
            $inCart = $session->get('cart');
            if($changeValue == 0){ //Ha a darabszám 0-a, töröljük a terméket a kosárból
                unset($inCart[$productId]); 
            }else{
                $inCart[$productId] = $changeValue; //A darabszámot a megadott értékre változtatjuk
            }
                        
            $session->set('cart', $inCart);//Mentjük a munkafolyamatot
            
            
            $service = $this->container->get('cart_service');
            $productsWithCount = $service->getProductWithCount(); //Lekérjük a kosár tartalmát           
            $cartCount = $service->getCartCount(); //Kosárban lévő termékek száma
        
            //Kilistázzuk a kosárban lévő termékeket
            $html = $this->renderView('FrontendCartBundle:Cart:cartItems.html.twig', array(
                        'productsWithCount' => $productsWithCount,
                        'cartCount'=>$cartCount));
            
            return new JsonResponse(array('success' => true,'html' => $html, 'cartCount' => $cartCount));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }

}
