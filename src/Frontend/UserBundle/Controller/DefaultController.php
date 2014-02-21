<?php

namespace Frontend\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontendUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
