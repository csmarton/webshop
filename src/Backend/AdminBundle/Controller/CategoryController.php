<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\CategoryType;
use Frontend\ProductBundle\Form\MainCategoryType;
use Frontend\ProductBundle\Entity\Category;
use Frontend\ProductBundle\Entity\MainCategory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Backend\AdminBundle\Entity\Log;

class CategoryController extends Controller
{
    /*
     * Kategória listázása
     */
    public function listAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        
        //OLDALAK
        $page = (int)$request->query->get('page');
        if($page == NULL){
             $page = 1;
        }        
        $maxResult = (int)$request->query->get('maxResult');
        if($maxResult == NULL){
             $maxResult = 10;
        }
        
        //RENDEZÉS
        $order = $request->query->get('order');
        $by = $request->query->get('by');
        if($order == NULL){
             $order = "id";
             $by= "asc";
        }
        if($by != "asc" && $by != "desc"){
            $by = "asc";
        }
        
        $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->createQueryBuilder('c')
                ->select('c')
                ->where('c.deletedAt is NULL');
        $filterId = "";
        $filterName = "";
        $filterMainCategory = "";
        $parameters = "";
        //Szűrés
        if ($request->getMethod() == 'GET') {
            $filterId = $request->query->get('filterId');
            $filterName = $request->query->get('filterName');
            $filterMainCategory = $request->query->get('filterMainCategory');
            $parameters .= "&filterId=";
            if($filterId!= ""){ //Azonosító alapján
                $categorys = $categorys
                    ->andWhere('c.id = :id')
                    ->setParameter('id', (int)$filterId); 
                $parameters .= $filterId;
            }
            $parameters .= "&filterName=";
            if($filterName!= ""){ //Név alapján
                $categorys = $categorys
                    ->andWhere('c.name LIKE :name')
                    ->setParameter('name', "%".$filterName."%");
                $parameters .= $filterName;
            }
            $parameters .= "&filterMainCategory=";
            if($filterMainCategory!= ""){ //Kategória alapján
                $categorys = $categorys
                    ->leftJoin('c.mainCategory','mc')    
                    ->andWhere('mc.id = :cat')
                    ->setParameter('cat', (int)$filterMainCategory);
                $parameters .= $filterMainCategory;
            }            
        }
        //Rendezés
        if($order == "id"){
            $categorys = $categorys
                ->orderBy('c.id',$by);
        }else if($order == "name"){
            $categorys = $categorys
                ->orderBy('c.name',$by);
        }else if($order == "mainCategory"){
            $categorys = $categorys
                ->orderBy('c.mainCategory',$by);
        }else{
            $categorys = $categorys
                ->orderBy('c.id',$by);
        }
        $parameters .= "&maxResult=".$maxResult;
        $pageCount = ceil(count($categorys->getQuery()->getResult()) / $maxResult);
        
        if($pageCount == 0)
            $pageCount = 1;
        $categorys = $categorys
                ->setFirstResult($page*$maxResult - $maxResult)
                ->setMaxResults($maxResult)
                ->getQuery()->getResult();
        
        $mainCategorys = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        
        return $this->render('BackendAdminBundle:Category:categoryList.html.twig', array(
            //'form' => $form->createView(),
            'categorys' => $categorys,
            'mainCategorys' => $mainCategorys,
            'filterId'=> $filterId,
            'filterName'=> $filterName,
            'filterMainCategory'=> $filterMainCategory,
            'actualPage' => $page,
            'pageCount' => $pageCount,
            'parameters' => $parameters,
            'maxResult' => $maxResult,
            'order' => $order,
            'by' => $by
        ));        
    }
    
    /*
     * Kategória szerkesztése és új kategória
     */
    public function newAction()
    {   
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $edit = true;
        $request = $this->get('request');
        $categoryId = $request->query->get('categoryId');
        $mainCategoryId = $request->query->get('mainCategoryId');
        if($categoryId == null){ //Új kategória
            $edit = false;
            $category = new Category();
            if($mainCategoryId != null){
                $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
                if($mainCategory != null){
                    $category->setMainCategory($mainCategory);
                }
            }
        }else{ //Meglévő kategória
            $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categoryId);
            $oldCategory = array(
                'name' => $category->getName(),
                'slug' => $category->getSlug(),
                'mainCategory' => $category->getMainCategory()->getName()
            );
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
               
                //LOGOLÁS
                $log = new Log();
                $user = $this->get('security.context')->getToken()->getUser();
                if($edit){//Szerkesztés
                    $changedData = "";
                    if($category->getName() != $oldCategory['name']){                            
                        $changedData .= "<div class=\"label-text\">Név: </div><div class='content-box'><div class=\"old-value\">".$oldCategory['name'] . "</div><div class=\"new-value\"> " .$category->getName(). "</div></div>";
                    }
                    if($category->getMainCategory() != $oldCategory['mainCategory']){                            
                        $changedData .= "<div class=\"label-text\">Főkategória: </div><div class='content-box'><div class=\"old-value\">".$oldCategory['mainCategory'] . "</div><div class=\"new-value\"> " .$category->getMainCategory()->getName(). "</div></div>";
                    }
                    if($category->getSlug() != $oldCategory['slug']){                            
                        $changedData .= "<div class=\"label-text\">Slug: </div><div class='content-box'><div class=\"old-value\">".$oldCategory['slug'] . "</div><div class=\"new-value\"> " .$category->getSlug(). "</div></div>";
                    }
                    
                    $log->setAction(1);
                }else{//Új adat 
                    $changedData = "<div class=\"label-text\">Új kategória: </div><div class='content-box'>". $category->getId() ."</div>";
                    $log->setAction(0);
                }

                $now = new \DateTime('now');
                $log->setObjectClass("Frontend\ProductBundle\Entity\Category");
                $log->setObjectId($category->getId());
                $log->setTime($now);
                $log->setUser($user);
                $log->setData($changedData);
                $em = $this->getDoctrine()->getEntityManager(); 
                $em->persist($log);
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
    
    /*
     * Kategória törlése
     */
     public function removeAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $categoryId = $request->request->get('categoryId');
            
            $category = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findOneById($categoryId);
            $categoryName = $category->getName();
            //LOGOLÁS
            $user = $this->get('security.context')->getToken()->getUser();

            $changedData = "<div class=\"label-text\">Kategória törlése: </div><div class='content-box'>". $categoryName ."</div>";
            $now = new \DateTime('now');
            $log = new Log();
            $log->setAction(2);
            $log->setObjectClass("Frontend\ProductBundle\Entity\Category");
            $log->setObjectId($category->getId());
            $log->setTime($now);
            $log->setUser($user);
            $log->setData($changedData);
            $em = $this->getDoctrine()->getEntityManager(); 
            $em->persist($log);
            $em->flush();
            
            $now = new \DateTime("now"); 
            $category->setDeletedAt($now);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($category);
            $em->flush();
            
            return new JsonResponse(array('success' => true,'categoryName' => $categoryName));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
    /*
     * Főkategória listázása
     */
    public function mainCategoryAction(){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $mainCategorys = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->createQueryBuilder('mc')
                ->select('mc')
                ->where('mc.deletedAt is NULL')
                ->getQuery()->getResult();
        return $this->render('BackendAdminBundle:Category:mainCategoryList.html.twig', array(
            'mainCategorys' => $mainCategorys
        ));
    }
    
    /*
     * Főkategória szerkesztése vagy új főkategória
     */
    public function mainCategoryNewAction(){ 
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        $mainCategoryId = $request->query->get('mainCategoryId');
        $categorys = null;
        $edit = true;
        if($mainCategoryId == null){ //Új főkategória
            $edit = false;
            $mainCategory = new MainCategory();        
        }else{ //Meglévő szerkesztése
            $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
            $categorys = $this->getDoctrine()->getRepository('FrontendProductBundle:Category')->findByMainCategory($mainCategory);
            $oldMainCategory = array(
                'name' => $mainCategory->getName()
            );
        }
        
        
        
        $form = $this->createForm(new MainCategoryType(),$mainCategory);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {     
               
               $em = $this->getDoctrine()->getManager();
               $em->persist($mainCategory);
               $em->flush();
               //LOGOLÁS
                $log = new Log();
                $user = $this->get('security.context')->getToken()->getUser();
                if($edit){//Szerkesztés
                    $changedData = "";
                    if($mainCategory->getName() != $oldMainCategory['name']){                            
                        $changedData = "<div class=\"label-text\">Név: </div><div class='content-box'><div class=\"old-value\">".$oldMainCategory['name'] . "</div><div class=\"new-value\"> " .$mainCategory->getName(). "</div></div>";
                    }
                    $log->setAction(1);
                }else{//Új adat 
                    $changedData = "<div class=\"label-text\">Új főkategória: </div><div class='content-box'>". $mainCategory->getName() ."</div>";
                    $log->setAction(0);
                }

                $now = new \DateTime('now');
                $log->setObjectClass("Frontend\ProductBundle\Entity\MainCategory");
                $log->setObjectId($mainCategory->getId());
                $log->setTime($now);
                $log->setUser($user);
                $log->setData($changedData);
                $em = $this->getDoctrine()->getEntityManager(); 
                $em->persist($log);
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
    
    /*
     * Főkategória törlése
     */
    public function removeMainCategoryAction(){
         $request = $this->get('request');
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $mainCategoryId = $request->request->get('mainCategoryId');
            
            $mainCategory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findOneById($mainCategoryId);
            $mainCategoryName = $mainCategory->getName();
            
            //LOGOLÁS
            $user = $this->get('security.context')->getToken()->getUser();

            $changedData = "<div class=\"label-text\">Főkategória törlése: </div><div class='content-box'>". $mainCategory->getName() ."</div>";
            $now = new \DateTime('now');
            $log = new Log();
            $log->setAction(2);
            $log->setObjectClass("Frontend\ProductBundle\Entity\MainCategory");
            $log->setObjectId($mainCategory->getId());
            $log->setTime($now);
            $log->setUser($user);
            $log->setData($changedData);
            $em = $this->getDoctrine()->getEntityManager(); 
            $em->persist($log);
            $em->flush();
             
            $now = new \DateTime("now"); 
            $mainCategory->setDeletedAt($now);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($mainCategory);
            $em->flush();
            
            return new JsonResponse(array('success' => true,'mainCategoryName' => $mainCategoryName));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
