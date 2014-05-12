<?php

namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
class DefaultController extends Controller
{
    /*
     * Réteg renderelése
     */
    public function layoutAction()
    {
        return $this->render('FrontendLayoutBundle:Default:layout.html.twig');
    }
    
    /*
     * Lábléc renderelése
     */
    public function footerAction(){
        return $this->render('FrontendLayoutBundle:Default:footer.html.twig');
    }
    
    /*
     * Fejléc renderelése
     * Paraméterben átadjuk neki a kosárban lévő termékek darabszámát
     */
     public function headerAction($searchKey = null) {        
        $request = $this->get('request');
        $service = $this->container->get('cart_service');
        $cartCount = $service->getCartCount();
        return $this->render('FrontendLayoutBundle:Default:header.html.twig',array('cartCount'=>$cartCount, 'searchKey' => $searchKey));
    }    
       
   /*
    * Ügyfélszolgálat renderelése
    */
    public function customerServicesAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:customerServices.html.twig');
    }
    
    /*
     * Adatvédelmi és szeződési feltételek renderelése
     */
    public function privacyPolicyAndTermsConditionsAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:PrivacyPolicyAndTermsConditions.html.twig');
    }
 
    /*
     * Gyakori kérdések renderelése
     */
    public function gyikAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:GYIK.html.twig');
    }
    
    /*
     * Kapcsolat renderelése
     */
    public function contactAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:contact.html.twig');
    }
    
     /*
     * SIDEBAR, az oldal oldalsó részén található menüsor
     */
    public function sidebarAction($currentMainCategory = null, $currentCategory = null){
        $main_catergory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->createQueryBuilder('mc')
                ->select('mc,c')
                ->leftJoin('mc.category','c')
                ->getQuery()->getResult();
        
        return $this->render('FrontendLayoutBundle:Default:sidebar.html.twig',array('main_category' => $main_catergory,
            'currentMainCategory' =>$currentMainCategory,
            'currentCategory' => $currentCategory
            ));
    }
    
    /*
     * Ajánlott termékek az oldalsávban
     * Lekérjük a rendeléseket, megnézzük hogy mely termékből rendelték a legtöbbet és azokat adjuk vissza
     */
    public function sideRecomendedProductsAction(){
        $ordersItem = $this->getDoctrine()->getRepository('FrontendOrderBundle:OrdersItem')->createQueryBuilder('p')
                ->select('p.productId,SUM(p.unitQuantity) AS db')
                ->groupBy('p.productId')                
                ->orderBy('db', 'desc')
                ->setMaxResults(8)
                ->getQuery()->getResult();
        $productIds = array();
        foreach($ordersItem as $item){
            $productIds[] = $item['productId']; //legtöbbet rendelt termékek azonosítói
        }
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                ->select('p,pi')
                ->leftJoin('p.productImages','pi')
                ->where('p.id IN (:productIds)')
                ->setParameter('productIds', $productIds)
                ->getQuery()->getResult();
        
        return $this->render('FrontendLayoutBundle:Default:sideRecomendedProducts.html.twig',array(
            'products' => $products
        ));
    }
    
}
