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
    public function mainProductAction(){
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findAll();
        $productsPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                    ->select('pp, p')                          
                    ->leftJoin('pp.property', 'p') 
                    ->getQuery()->getResult();
        $productsImages = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductImages')->findAll();
        
        
        $propertys = array();
        $images = array();
        foreach($products as $i => $product){  
            $propertys[$i] = array();
            $images[$i] = array();
            foreach($productsPropertys as $pp){
                if($product->getId() == $pp->getProductId()){
                    $propertys[$i][] = $pp;
                }
            }
            foreach($productsImages as $img){
                if($product->getId() == $img->getProductId()){
                    $images[$i][] = $img;
                }
            }           
        }
        return $this->render('FrontendProductBundle:Default:products.html.twig',array('products'=>$products,'propertys'=>$propertys,'images'=>$images));
    }
}
