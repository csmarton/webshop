<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;
use Frontend\ProductBundle\Entity\ProductQuestions;
use Frontend\ProductBundle\Form\ProductQuestionsType;

use Symfony\Component\HttpFoundation\JsonResponse;
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
    
    public function tabQuestionsAction($productId=null){
        $request = $this->get('request');
        if($productId == null){
            $productId = $request->request->get('productId');
        }
       
        $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);        
        
        $form = $this->createForm(new ProductQuestionsType());
        if ($request->getMethod() == 'POST') { 
                
                
              $name = $request->request->get('name');
               $email = $request->request->get('email');
               $question = $request->request->get('question');
               $productQuestion = new ProductQuestions();
               $productQuestion->setProduct($product);
               $productQuestion->setName($name);
               $productQuestion->setEmail($email);
               $productQuestion->setQuestion($question);
               $productQuestion->setStatus(0);
               $em = $this->getDoctrine()->getManager();
               $em->persist($productQuestion);
               $em->flush(); 
               
               //$html = $this->renderView('FrontendProductBundle:Tabs:questions.html.twig', array('product' => $product));
               $html = "Kérdésedet sikeresen elküldtük!";
               return new JsonResponse(array('success' => true,'html'=>$html));
              
        }
        
        $productQuestions = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductQuestions')->createQueryBuilder('pq')
                    ->select('pq')  
                    ->where('pq.productId = :productId')
                    ->andWhere('pq.answer IS NOT NULL')
                    ->andWhere('pq.status = 1')
                    ->setParameter('productId',$productId)
                    ->getQuery()->getResult();  
        
        return $this->render('FrontendProductBundle:Tabs:tabQuestions.html.twig',array(
            'product'=>$product,
            'form' => $form->createView(),
            'productQuestions' => $productQuestions,
            ));
    }
    
    
}