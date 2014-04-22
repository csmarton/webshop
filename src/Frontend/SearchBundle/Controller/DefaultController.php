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
            
            $key = $request->request->get('key');            
 
            $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
        
            $products = $repo->createQueryBuilder('p') 
                    ->where('p.name LIKE :key')
                    ->setParameter('key','%'.$key.'%')
                    ->setMaxResults(10)
                    ->getQuery()->getResult();
            
            
            $html = $this->renderView('FrontendSearchBundle:Default:autocompleteResults.html.twig', array(
                        'products' => $products
                    ));
            return new JsonResponse(array('success' => true, 'html' => $html));
             
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
