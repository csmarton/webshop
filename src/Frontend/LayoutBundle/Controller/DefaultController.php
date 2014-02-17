<?php

namespace Frontend\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        return $this->render('FrontendLayoutBundle:Default:header.html.twig');
    }
}
