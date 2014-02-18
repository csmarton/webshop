<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;
class DefaultController extends Controller
{
    public function indexAction()
    {
        $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Taxonomy');
        $taxonomy = $repo->findAll();
        
        foreach($taxonomy as $tax){
       //     var_dump($tax->getName());
        }
        $productImages = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductImages')->findAll();
        foreach($productImages as $tax){
            var_dump($tax->getProduct()->getName());
        }
        return $this->render('FrontendProductBundle:Default:index.html.twig');
    }
}
