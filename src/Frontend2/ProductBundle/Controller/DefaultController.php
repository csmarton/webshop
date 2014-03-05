<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;
class DefaultController extends Controller
{
    public function indexAction()
    {        
         //$em = $this->getDoctrine()->getEntityManager();
        // $user = $this->get('security.context')->getToken()->getUser();
         //var_dump($user);die;
        
        
        
        return $this->render('FrontendProductBundle:Default:index.html.twig');
    }
    
    public function sidebarAction(){
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
        return $this->render('FrontendProductBundle:Default:sidebar.html.twig', array('leftMenu'=>$leftMenu));
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
    
    public function productByTaxonomyAction($taxon, $taxonomy=null){
        $permalinks = $taxon . "/". $taxonomy;
        $productsPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductTaxon')->createQueryBuilder('pt')
                    ->select('pt.productId')  
                    ->leftJoin('pt.taxon', 't')
                    ->where('t.permalinks LIKE :permalinks')     
                    ->setParameter('permalinks',$permalinks."%")
                    ->getQuery()->getResult();
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                    ->select('p')  
                    ->where('p.id IN (:productsPropertys)')     
                    ->setParameter('productsPropertys',$productsPropertys)
                    ->getQuery()->getResult();
        
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
        return $this->render('FrontendProductBundle:Default:products_by_taxon.html.twig',array('products'=>$products,'propertys'=>$propertys,'images'=>$images));
    }
    
    public function productAction($slug){
        if(!is_numeric($slug)){//slug alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneBySlug($slug);
        }else{//id alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($slug);
        }
        
        $productPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                    ->select('pp')
                    ->where('pp.productId = :productId')
                    ->setParameter('productId',$product->getId())
                    ->getQuery()->getResult();
        $productImages = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductImages')->findByProductId($product->getId());
        
        
        $propertys = array();
        $images = array();
        foreach($productPropertys as $pp){
            if($product->getId() == $pp->getProductId()){
                $propertys[] = $pp;
            }
        }
        foreach($productImages as $img){
            if($product->getId() == $img->getProductId()){
                $images[] = $img;
            }
        } 
        
        return $this->render('FrontendProductBundle:Default:product.html.twig',array('product'=>$product,'propertys'=>$propertys,'images'=>$images));
    }
}
