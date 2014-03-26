<?php

namespace Frontend\OrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontendOrderBundle:Default:index.html.twig', array('name' => $name));
    }
}
