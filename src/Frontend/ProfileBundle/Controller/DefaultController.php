<?php

namespace Frontend\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontendProfileBundle:Default:index.html.twig', array('name' => $name));
    }
}
