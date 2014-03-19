<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;

use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction()
    {        
       
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findAll();
        return $this->render('FrontendProductBundle:Default:index.html.twig',array('products'=>$products));
    }   
    
	
    public function sidebarAction(){
        $main_catergory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        
        return $this->render('FrontendProductBundle:Default:sidebar.html.twig',array('main_category' => $main_catergory));
    }
    
    public function productByCategoryAction($main_category, $category=null){
        $permalinks = $main_category . "/". $category;
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                    ->select('p')  
                    ->leftJoin('p.categorys', 'c')
                    ->leftJoin('c.mainCategory','mc')
                    ->where('mc.name = :main_category OR mc.id = :main_category ')     
                    ->setParameter('main_category',$main_category);
        if($category != null){
             $products = $products
                ->andWhere('c.slug = :category OR c.id = :category')     
                ->setParameter('category',$category);
        }
        $products = $products
            ->getQuery()->getResult();       
        
        return $this->render('FrontendProductBundle:Default:products_by_category.html.twig',array('products'=>$products));
    }
    
    public function productAction($slug){
        if(!is_numeric($slug)){//slug alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneBySlug($slug);
        }else{//id alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($slug);
        }
        
        
        return $this->render('FrontendProductBundle:Default:product.html.twig',array('product'=>$product));
    }
}