<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\Product;
use Frontend\ProductBundle\Entity\ProductImages;
use Frontend\ProductBundle\Entity\ProductQuestions;
use Frontend\ProductBundle\Form\ProductQuestionsType;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /*
     * Termékek kezdőoldal, termékek listázása és szűrése
     */
    public function indexAction($main_category=null, $category=null)
    {                       
        
        $request = $this->get('request');
        $page = (int)$request->query->get('page');
        if($page == NULL){
             $page = 1;
        }

        $order = $request->query->get('order');
        $by = $request->query->get('by');
        
        //OLDALAK
        $maxResult = 1; //   
        $productRepo = $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
        $productPropertyRepo = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty');
        
        //termékek listája, szűrt termékek azonosítója alapján ezt fogjuk szűrni
        $products = $productRepo->createQueryBuilder('p')
                    ->select('p')
                    ->leftJoin('p.categorys','c')
                    ->leftJoin('p.productImages','pi');
        if ($request->getMethod() != 'POST') {
            if($main_category != null){
                $products = $products
                        ->leftJoin('c.mainCategory','mc')
                        ->andWhere('mc.name = :main_category OR mc.id = :main_category ')     
                        ->setParameter('main_category',$main_category);   
            }
            if($category != null){
                $products = $products
                        ->andWhere('c.slug = :category OR c.id = :category ')     
                        ->setParameter('category',$category);   
            }        
        }
        
        $request = $this->get('request');       
        if ($request->getMethod() == 'POST') { //AJAX kérés esetén  
                //Rendezési szempontok
                if($order == NULL && $by == NULL){
                    $order = $request->request->get('order');
                    $by = $request->request->get('by');
                }

                
                //Oldalszám
                $getPage = (int)$request->request->get('page');
                if($getPage != NULL){
                    $page = $getPage;
                }
                
                $filterType = $request->request->get('filterType');
                $filteredProductIds = null;
                
                if($filterType == "1"){ //LAPTOP
                    //LEKÉRJÜK A SZŰRÉSI ADATOKAT                
                    $parameters = array(
                        'laptopFilterManufacturer' => $request->request->get('laptopFilterManufacturer'),
                        'laptopFilterWinchester' => $request->request->get('laptopFilterWinchester'),
                        'laptopFilterOperationSystem' => $request->request->get('laptopFilterOperationSystem'),
                        'laptopFilterProcessor' => $request->request->get('laptopFilterProcessor'),
                        'laptopFilterScreenSize' => $request->request->get('laptopFilterScreenSize'),
                        'laptopFilterMemory' => $request->request->get('laptopFilterMemory'),
                        'laptopFilterPrice' => $request->request->get('laptopFilterPrice'),
                    );
                    //Szűrt laptopok azonosítói
                    $service = $this->container->get('product_service');
                    $filteredProductIds = $service->getLaptopFilteredProducts($products, $parameters);

                }else if($filterType == "2"){ //TABLET
                    //LEKÉRJÜK A SZŰRÉSI ADATOKAT                
                    $parameters = array(
                        'tabletFilterManufacturer' => $request->request->get('tabletFilterManufacturer'),
                        'tabletFilterWinchester' => $request->request->get('tabletFilterWinchester'),
                        'tabletFilterOperationSystem' => $request->request->get('tabletFilterOperationSystem'),
                        'tabletFilterProcessor' => $request->request->get('tabletFilterProcessor'),
                        'tabletFilterScreenSize' => $request->request->get('tabletFilterScreenSize'),
                        'tabletFilterMemory' => $request->request->get('tabletFilterMemory'),
                        'tabletFilterPrice' => $request->request->get('tabletFilterPrice'),
                    );
                    
                    //Szűrt laptopok azonosítói
                    $service = $this->container->get('product_service');
                    $filteredProductIds = $service->getTabletFilteredProducts($products, $parameters);
                }else if($filterType == ""){
                    $parameters = array(
                        'generalSearchString' => $request->request->get('generalSearchString'),
                        'generalFilterPrice' => $request->request->get('generalFilterPrice'),
                    );
                    
                    //Szűrt laptopok azonosítói
                    $service = $this->container->get('product_service');
                    $filteredProductIds = $service->getGeneralFilteredProducts($products, $parameters);
                }
                
                //Termékek lekérdezése szűrés alapján
                    $products = $products
                         ->andWhere('p.id IN (:filteredProductIds)')
                         ->setParameter('filteredProductIds',$filteredProductIds);
                    
        }               
         
        //TERMÉKEK RENDEZÉSE
        $products = $productRepo->getOrderByProduct($products, $order, $by);
        
        $allProductsResult = $products->getQuery()->getResult();  
        $allProductCount = count($allProductsResult);
        
        //Oldalak száma
        $pageCount = ceil($allProductCount/ $maxResult); 
        if($pageCount == 0){
            $pageCount = 1;
        }
        if($page>$pageCount || $page < 0)
            $page = 1;
        
        
        //Termékek
        $products = array_slice($allProductsResult,$page*$maxResult - $maxResult,$maxResult);
        
        $urlParameters = "?order=".$order."&by=".$by;
        
        //AJAX kérés esetén visszadjuk a felső menüt és a termékek listáját
        if ($request->getMethod() == 'POST') {  
            //Felső menü
            $upperMenu = $this->renderView('FrontendProductBundle:Default:upperMenu.html.twig', array(
                'order' => $order,
                'by'=>$by,
                'actualPage' => $page,
                'pageCount' => $pageCount,
                'productCount' => $allProductCount,
             ));
            
            //Oldalszámok
             $pagesMenu = $this->renderView('FrontendProductBundle:Default:pages.html.twig', array(
                'actualPage' => $page,
                'pageCount' => $pageCount,
                'order' => $order,
                'by'=>$by,
                'productCount' => $allProductCount,
             ));
            
            //Termékek 
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
            'urlParameters' => $urlParameters,
            'productCount' => $allProductCount,
            'mainCategory' => $main_category,
            'category' => $category
        ));
    }   
    
    /*
     * SIDEBAR, az oldal oldalsó részén található menüsor
     */
    public function sidebarAction(){
        $main_catergory = $this->getDoctrine()->getRepository('FrontendProductBundle:MainCategory')->findAll();
        
        return $this->render('FrontendProductBundle:Default:sidebar.html.twig',array('main_category' => $main_catergory));
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