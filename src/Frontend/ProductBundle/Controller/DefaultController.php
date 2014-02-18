<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;
class DefaultController extends Controller
{
    public function indexAction()
    {
        $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Taxon');
        
        $taxonWithTaxonomy = $repo
                    ->createQueryBuilder('t')
                    ->select('t, tt')                          
                    ->leftJoin('t.taxonomy', 'tt')                    
                    ->orderBy('tt.name,t.name')
                    ->getQuery()->getResult();
        
        $leftMenu = array();
        foreach($taxonWithTaxonomy as $t){
            $leftMenu[$t->getTaxonomy()->getName()][] = $t;
        }
        return $this->render('FrontendProductBundle:Default:index.html.twig', array('leftMenu'=>$leftMenu));
    }
}
