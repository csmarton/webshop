<?php

namespace Frontend\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
class DefaultController extends Controller
{
    public function mainSearchAutocompleteAction()
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            
            $key = $request->request->get('searchText');
        
            $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findProductBySearchKey($key);
            
            $productWithImage = array();
            foreach((array)$products as $product){
                
                $productWithImage[] = array('image'=> (string)$product->getNormalProductImage(), 'name' => $product->getName() );
                //var_dump((string)$product->getNormalProductImage());die;
            }
            
            return new JsonResponse($productWithImage);
             
        }else{
            return new JsonResponse(array('success' => false));
        }
        
    }   
    
     public function mainSearchAction()
     {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            
            $key = $request->request->get('key');            
            
            $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
        
            $products = $repo->createQueryBuilder('p') 
                    ->where('p.name LIKE :key')
                    ->setParameter('key','%'.$key.'%')
                    ->getQuery()->getResult();

            return $this->render('FrontendProductBundle:Default:index.html.twig',array('products'=>$products));
        }
        
    }  
    
    public function mainSearchFormAction(){
        return $this->render('FrontendProductBundle:Default:index.html.twig');
    }
    
}
