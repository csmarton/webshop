<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function loginAction(){
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->render('BackendAdminBundle:Index:login.html.twig');    
        }else{
            return $this->redirect($this->generateUrl('backend_admin_product'));
        }
        
        
    }
}