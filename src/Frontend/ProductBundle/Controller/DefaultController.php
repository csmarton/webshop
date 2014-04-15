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
        $request = $this->get('request');
        $page = (int)$request->query->get('page');
        if($page == NULL){
             $page = 1;
         }
        $order = $request->query->get('order');
        $by = $request->query->get('by');
        
        //OLDALAK
        $maxResult = 5;        
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                    ->select('p')
                    ->leftJoin('p.categorys','c') //laptopokat keresünk
                    ->leftJoin('p.specialOffer','sales');
        
        $request = $this->get('request');       
        if ($request->getMethod() == 'POST') {               
               //LEKÉRJÜK A SZŰRÉSI ADATOKAT
               $laptopFilterManufacturer = $request->request->get('laptopFilterManufacturer');
               $laptopFilterWinchester = $request->request->get('laptopFilterWinchester');
               $laptopFilterOperationSystem = $request->request->get('laptopFilterOperationSystem');
               $laptopFilterProcessor = $request->request->get('laptopFilterProcessor');
               $laptopFilterScreenSize = $request->request->get('laptopFilterScreenSize');
               $laptopFilterMemory = $request->request->get('laptopFilterMemory');
               $laptopFilterPrice = $request->request->get('laptopFilterPrice');
                $products = $products
                    ->andWhere('c.mainCategory = 1'); //laptopokat keresünk
                
               if($order == NULL && $by == NULL){
                   $order = $request->request->get('order');
                   $by = $request->request->get('by');
               }
               
               $getPage = (int)$request->request->get('page');
               if($getPage != NULL){
                   $page = $getPage;
               }
               //Lekérjük a laptopokat és eltároljuk az azonosítójukat, ezeket fogjuk szűrés esetén szűkíteni
               $productIds = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                    ->select('p.id')
                    ->leftJoin('p.categorys','c')
                    ->where('c.mainCategory = 1')
                    ->getQuery()->getResult();
               
               $filteredProductIds = array(); //Laptopok azonosítói
               foreach((array)$productIds as $p){
                    $filteredProductIds[] = $p['id'];
               } 
               
               //Szűrés ár szerint
               list($lowPrice,$hightPrice)=explode("-",$laptopFilterPrice);
               $products = $products
                    ->where('p.price >= :lowPrice and p.price <= :hightPrice')
                    ->setParameter('lowPrice',(int)$lowPrice )
                    ->setParameter('hightPrice',(int)$hightPrice );
               
               //Szűrés GYÁRTÓ szerint
               
               if($laptopFilterManufacturer != ""){
                   $filteredProductIds0 = array();
                   $filtered0 = array();
                   
                   $productIdsFromProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                           ->select('p.id')
                           ->leftJoin('p.categorys','c')
                           ->where('c.mainCategory = 1')
                           ->andWhere('c.name LIKE :manufacturer')
                           ->setParameter('manufacturer',$laptopFilterManufacturer);
                   $filtered0 =  $productIdsFromProperty->getQuery()->getResult();
                   foreach((array)$filtered0 as $f0){
                       $filteredProductIds0[] = $f0['id'];
                   }
                   $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds0); 
               }
               
               //Szűrés MEREVLEMEZ szerint                
                if($laptopFilterWinchester != ""){
                   $filteredProductIds1 = array();
                   $filtered1 = array();
                   list($first,$second)=explode("-",$laptopFilterWinchester);
                   
                   $productIdsFromProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                       ->select('product.id')
                       ->leftJoin('pp.property','p')
                       ->leftJoin('pp.product','product')    
                       ->where('p.id = 6');
                   if($first != ""){
                        $filtered1 = $productIdsFromProperty
                                ->andWhere('CAST(pp.value) >= :first') //p.id==6 --> merevlemez
                                ->setParameter('first',(int)$first);
                   }
                   if($second != ""){
                        $filtered1 = $filtered1
                                ->andWhere('CAST(pp.value) <= :second')
                                ->setParameter('second',(int)$second);
                        
                   }
                   $filtered1 = $filtered1->getQuery()->getResult();
                   foreach((array)$filtered1 as $f1){
                       $filteredProductIds1[] = $f1['id'];
                   }
                   $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds1); 
                } 
                
                //Szűrés OPERÁCIÓS RENDSZER szerint                
                if($laptopFilterOperationSystem != ""){
                    $filteredProductIds2 = array();
                    $filtered2 = array();
                    $filtered2 = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                       ->select('product.id')
                       ->leftJoin('pp.property','p')
                       ->leftJoin('pp.product','product')    
                       ->where('p.id = 14')
                       ->andWhere('pp.value LIKE :laptopFilterOperationSystem') //p.id==14 --> operációs rendszer
                       ->setParameter('laptopFilterOperationSystem','%'.$laptopFilterOperationSystem.'%')
                       ->getQuery()->getResult();
                    
                   foreach((array)$filtered2 as $f2){
                       $filteredProductIds2[] = $f2['id'];
                   }
                   $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds2); 
                }
                               
                //Szűrés PROCESSZOR szerint
                if($laptopFilterProcessor != ""){
                    $filteredProductIds3 = array();
                    $filtered3 = array();
                    $filtered3 = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                       ->select('product.id')
                       ->leftJoin('pp.property','p')
                       ->leftJoin('pp.product','product')  
                       ->where('p.id = 13')
                       ->andWhere('pp.value LIKE :laptopFilterProcessor') //p.id==13 --> processzor
                       ->setParameter('laptopFilterProcessor','%'.$laptopFilterProcessor.'%')
                       ->getQuery()->getResult();
                   foreach((array)$filtered3 as $f3){
                       $filteredProductIds3[] = $f3['id'];
                   }
                   $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds3); 
                }
                
                //Szűrés KÉPERNYŐ méret szerint
                if($laptopFilterScreenSize != ""){  
                   $filteredProductIds4 = array();
                   $filtered4 = array();
                   list($first,$second)=explode("-",$laptopFilterScreenSize);
                   
                   $productIdsFromProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                       ->select('product.id')
                       ->leftJoin('pp.property','p')
                       ->leftJoin('pp.product','product') 
                       ->where('p.id = 5');
                   
                   if($first != ""){
                        $filtered4 = $productIdsFromProperty
                                ->andWhere('CAST(pp.value) >= :first') //p.id==5 --> Kijelző
                                ->setParameter('first',(int)$first);
                   }
                   if($second != ""){
                        $filtered4 = $filtered4
                                ->andWhere('CAST(pp.value) <= :second')
                                ->setParameter('second',(int)$second);
                   }
                    $filtered4 = $filtered4->getQuery()->getResult();
                   foreach((array)$filtered4 as $f4){
                       $filteredProductIds4[] = $f4['id'];
                   }
                   $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds4); 
                } 
                
                
                //Szűrés MEMÓRIA szerint
                if($laptopFilterMemory != ""){
                    $filteredProductIds5 = array();
                    $filtered5 = array();
                    list($first,$second)=explode("-",$laptopFilterMemory);
                   
                    $productIdsFromProperty = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                       ->leftJoin('pp.product','product') 
                       ->leftJoin('pp.property','p')
                       ->leftJoin('pp.product','product') 
                       ->where('p.id = 4');
                   
                    if($first != ""){
                         $filtered5 = $productIdsFromProperty
                                 ->andWhere('CAST(pp.value) >= :first') //p.id==4 --> Memória
                                 ->setParameter('first',(int)$first*1000);
                    }
                    if($second != ""){
                         $filtered5 = $filtered5
                                 ->andWhere('CAST(pp.value) <= :second')
                                 ->setParameter('second',(int)$second*1000);
                    }
                    $filtered5 = $filtered5->getQuery()->getResult();
                    foreach((array)$filtered5 as $f5){
                        $filteredProductIds5[] = $f5['id'];
                    }
                    $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds5); 
                } 
                                      
                //Termékek lekérdezése szűrés alapján
                $products = $products
                     ->andWhere('p.id IN (:filteredProductIds)')
                     ->setParameter('filteredProductIds',$filteredProductIds);
        }               
         
        //TERMÉKEK RENDEZÉSE
        if($order == "promotion" && $by=="desc"){
            $products = $products
                        ->orderBy('sales.newPrice', 'desc'); //TODO            
        }else if($order == "price" && $by=="asc"){
            $products = $products
                        ->orderBy('p.price', 'asc');
        }else if($order == "price" && $by=="desc"){
            $products = $products                        
                        ->orderBy('p.price', 'desc');
        }else if($order == "date"  && $by=="desc"){
            $products = $products
                        ->orderBy('p.createdAt', 'desc');
        }else if($order == "name"  && $by=="asc"){
            $products = $products
                        ->orderBy('p.name', 'asc');
        }else if($order == "name"  && $by=="desc"){
            $products = $products
                        ->orderBy('p.name', 'desc');
        }else{
           $order = "promotion"; 
           $by ="asc";
           $products = $products
                ->orderBy('sales.newPrice', 'desc');        
        }
        $allProductsResult = $products;        
        $allProductCount = count($allProductsResult->getQuery()->getResult());
        
        $pageCount = ceil($allProductCount/ 5); 
        if($pageCount == 0){
            $pageCount = 1;
        }
        $products = $products
            ->setFirstResult($page*$maxResult - $maxResult)
            ->setMaxResults($maxResult)
            ->getQuery()->getResult();

        $urlParameters = "?order=".$order."&by=".$by;
        
        //AJAX kérés esetén visszadjuk a felső menüt és a termékek listáját
        if ($request->getMethod() == 'POST') {  
            $upperMenu = $this->renderView('FrontendProductBundle:Default:upperMenu.html.twig', array(
                'order' => $order,
                'by'=>$by,
                'actualPage' => $page,
                'pageCount' => $pageCount,
             ));
            
             $pagesMenu = $this->renderView('FrontendProductBundle:Default:pages.html.twig', array(
                'actualPage' => $page,
                'pageCount' => $pageCount,
                'order' => $order,
                'by'=>$by,
             ));
            
            $productHtml = $this->renderView('FrontendProductBundle:Default:productBox.html.twig', array('products' => $products));
            return new JsonResponse(array(
                'success' => true,
                'productHtml'=>$productHtml,
                'upperMenu' => $upperMenu,
                'pagesMenu' => $pagesMenu));
         }               
     
        return $this->render('FrontendProductBundle:Default:index.html.twig',array(
                    'products'=>$products,
                    'order' => $order,
                    'by'=>$by,
                    'actualPage' => $page, 
                    'pageCount' => $pageCount,
                    'urlParameters' => $urlParameters));
    }   
    
    /*
     * SIDEBAR, az oldal oldalsó részén található menüsor
     */
    public function sidebarAction(){
        $main_catergory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        
        return $this->render('FrontendProductBundle:Default:sidebar.html.twig',array('main_category' => $main_catergory));
    }
    
    /*
     * Termékek listázása kategória alapján
     */
    public function productByCategoryAction($main_category, $category=null){
        $permalinks = $main_category . "/". $category;
        $request = $this->get('request');
        $page = (int)$request->query->get('page');
        $order = $request->query->get('order');
        $by = $request->query->get('by');
        
        if($page == null){
            $page = 1;
        }
        $maxResult = 5;        
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
        
        if($order == "promotion" && $by=="asc"){
            //$products = $products
                       // ->orderBy();
            $products = $products
                        ->orderBy('p.price', 'desc'); //TODO
            
        }else if($order == "price" && $by=="asc"){
            $products = $products
                        ->orderBy('p.price', 'asc');
        }else if($order == "price" && $by=="desc"){
            $products = $products
                        ->orderBy('p.price', 'desc');
        }else if($order == "date"  && $by=="desc"){
            $products = $products
                        ->orderBy('p.createdAt', 'desc');
        }else if($order == "name"  && $by=="asc"){
            $products = $products
                        ->orderBy('p.name', 'asc');
        }else if($order == "name"  && $by=="desc"){
            $products = $products
                        ->orderBy('p.name', 'desc');
        }else{
           $order = "promotion"; 
           $by ="asc";
           $products = $products
                ->orderBy('p.price', 'desc'); //TODO
        }
                    $allProduct = $products
                            ->getQuery()->getResult(); 
                    $products = $products
                                ->setFirstResult($page*$maxResult - $maxResult)
                                ->setMaxResults($maxResult)
                                ->getQuery()->getResult(); 
        
        
        $pageCount = ceil(count($allProduct)/ 5);
        return $this->render('FrontendProductBundle:Default:products_by_category.html.twig',array(
                    'products'=>$products,
                    'actualPage' => $page, 
                    'pageCount' => $pageCount,
                    'order' => $order,
                    'by' => $by,
                    'mainCategory' =>$main_category,
                    'category' => $category
                    ));
    }
    
    
    
    public function productAction($slug){
        if(!is_numeric($slug)){//slug alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneBySlug($slug);
        }else{//id alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($slug);
        }
        
        $categoryId = $product->getCategory();
       
        $categoryRelations = $this->getDoctrine()->getRepository('FrontendProductBundle:CategoryRelationship')->createQueryBuilder('r')
                ->select('r')
                ->where('r.firstCategoryId = :categoryId or r.secondCategoryId = :categoryId')
                ->setParameter('categoryId', $categoryId)
                ->getQuery()->getResult();
        $offerCategoryId = array();
        foreach((array)$categoryRelations as $categoryRelation){
            if($categoryRelation->getFirstCategoryId() == $categoryId ){
                $offerCategoryId[] = $categoryRelation->getSecondCategoryId();
            }else{
                $offerCategoryId[] = $categoryRelation->getFirstCategoryId();
            }
        }
        
        $offerProducts = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p') //TODO: valódi termék ajánlatok 
                    ->select('p')
                    ->setMaxResults(10)
                    ->where('p.category IN (:offerCategoryId)')
                    ->andWhere('p.id != :productId')
                    ->setParameter('offerCategoryId',$offerCategoryId)
                    ->setParameter('productId', $product->getId())
                    ->getQuery()->getResult();
        if(count($offerProducts) < 10){
            $moreProductCount = 10-count($offerProducts);
            $moreOfferProducts = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p') //TODO: valódi termék ajánlatok 
                        ->select('p')
                        ->setMaxResults($moreProductCount)
                        ->where('p.category = :categoryId ')
                        ->andWhere('p.id != :productId')
                        ->setParameter('categoryId',$categoryId)
                        ->setParameter('productId', $product->getId())
                        ->getQuery()->getResult();
            shuffle($moreOfferProducts);
            $offerProducts = array_merge($offerProducts, $moreOfferProducts);
        }
        
        
        return $this->render('FrontendProductBundle:Default:product.html.twig',array('product'=>$product, 'offerProducts' => $offerProducts));
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