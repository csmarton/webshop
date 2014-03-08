<?php

namespace Backend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function layoutAction()
    {
        return $this->render('BackendLayoutBundle:Default:layout.html.twig');
    }
    
    public function footerAction(){
        return $this->render('BackendLayoutBundle:Default:footer.html.twig');
    }
    
     public function headerAction() {
        return $this->render('BackendLayoutBundle:Default:header.html.twig');
    }
}
