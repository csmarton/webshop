<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\CategoryType;
use Frontend\ProductBundle\Entity\Category;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    public function indexAction()
    {   
        $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findAll();
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Category:list.html.twig', array(
            //'form' => $form->createView(),
            'categorys' => $categorys
        ));
    }
    
    public function newAction()
    {   
        $request = $this->get('request');
        $categoryId = $request->query->get('categoryId');

        if($categoryId == null){
            $category = new Category();        
        }else{
            $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categoryId);
        }
       
        $form = $this->createForm(new CategoryType(),$category);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {     
               if (isset($form['name'])) {
                    $productName = $form['name']->getData();
               }

               
               $category->setName($productName);
               $em = $this->getDoctrine()->getManager();
               $em->persist($category);
               $em->flush();
               
               return $this->redirect($this->generateUrl('backend_admin_category'));
            }
        }    
        
        return $this->render('BackendAdminBundle:Category:new.html.twig', array(
            'form' => $form->createView(),
            'categoryId'=>$categoryId,
            'category' => $category,
        ));
    }
    
     public function removeAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $categoryId = $request->request->get('categoryId');
            
            $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categoryId);
            $categoryName = $category->getName();
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($category);
            $em->flush();
            
            return new JsonResponse(array('success' => true,'categoryName' => $categoryName));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
