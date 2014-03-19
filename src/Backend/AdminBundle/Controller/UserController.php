<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\UserType;
use Frontend\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
class UserController extends Controller
{
    public function listAction()
    {   
        $users = $this->getDoctrine()->getRepository('FrontendUserBundle:User')->findAll();
        //var_dump($users[0]->getRoles());die;
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:User:list.html.twig', array(
            //'form' => $form->createView(),
            'users' => $users
        ));
    }    
   
    
}
