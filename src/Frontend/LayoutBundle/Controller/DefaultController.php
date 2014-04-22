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
    public function layoutAction()
    {

        return $this->render('FrontendLayoutBundle:Default:layout.html.twig');
    }
    
    public function footerAction(){
        return $this->render('FrontendLayoutBundle:Default:footer.html.twig');
    }
    
     public function headerAction() {        
        $request = $this->get('request');
        $service = $this->container->get('cart_service');
        $cartCount = $service->getCartCount();
        return $this->render('FrontendLayoutBundle:Default:header.html.twig',array('cartCount'=>$cartCount));
    }
    
   
    
    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }
    
    public function customerServicesAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:customerServices.html.twig');
    }
    
    public function privacyPolicyAndTermsConditionsAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:PrivacyPolicyAndTermsConditions.html.twig');
    }
 
    public function gyikAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:GYIK.html.twig');
    }
    
    public function contactAction(){
        return $this->render('FrontendLayoutBundle:BottomSection:contact.html.twig');
    }
}
