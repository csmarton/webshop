<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\CategoryType;
use Frontend\ProductBundle\Form\MainCategoryType;
use Frontend\ProductBundle\Entity\Category;
use Frontend\ProductBundle\Entity\MainCategory;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    public function indexAction()
    {   
        $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findAll();
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Category:categoryList.html.twig', array(
            //'form' => $form->createView(),
            'categorys' => $categorys,
        ));
    }
    
    public function newAction()
    {   
        $request = $this->get('request');
        $categoryId = $request->query->get('categoryId');
        $mainCategoryId = $request->query->get('mainCategoryId');
        if($categoryId == null){
            $category = new Category();
            if($mainCategoryId != null){
                $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
                if($mainCategory != null){
                    $category->setMainCategory($mainCategory);
                }
            }
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
        
        return $this->render('BackendAdminBundle:Category:categoryNew.html.twig', array(
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
    
    public function mainCategoryAction(){
        $mainCategorys = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        return $this->render('BackendAdminBundle:Category:mainCategoryList.html.twig', array(
            'mainCategorys' => $mainCategorys
        ));
    }
    
        public function mainCategoryNewAction(){   
        $request = $this->get('request');
        $mainCategoryId = $request->query->get('mainCategoryId');
        $categorys = null;
        if($mainCategoryId == null){
            $mainCategory = new MainCategory();        
        }else{
            $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
            $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findByMainCategory($mainCategory);
        }
        
        
        
        $form = $this->createForm(new MainCategoryType(),$mainCategory);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {     
               
               $em = $this->getDoctrine()->getManager();
               $em->persist($mainCategory);
               $em->flush();
               
               if($mainCategoryId == null){
                    return $this->redirect($this->generateUrl('backend_admin_main_category'));
               }else{
                    return $this->redirect($this->generateUrl('backend_admin_main_category_new').'?mainCategoryId='.$mainCategoryId);
               }
            }
        }    
        
        return $this->render('BackendAdminBundle:Category:mainCategoryNew.html.twig', array(
            'form' => $form->createView(),
            'mainCategoryId'=>$mainCategoryId,
            'mainCategory' => $mainCategory,
            'categorys' => $categorys
        ));
    }
    
    public function removeMainCategoryAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $mainCategoryId = $request->request->get('mainCategoryId');
            
            $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
            $mainCategoryName = $mainCategory->getName();
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($mainCategory);
            $em->flush();
            
            return new JsonResponse(array('success' => true,'mainCategoryName' => $mainCategoryName));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
