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
        $currentMainCategory = $main_category;
        $currentCategory = $category;
        $withAjax = true;
        
        $request = $this->get('request');
        //aktuális oldalszám lekérdezése
        $page = (int)$request->query->get('page');
        if($page == NULL){
             $page = 1;
        }

        //Rendezés lekérdezése
        $order = $request->query->get('order');
        $by = $request->query->get('by');
        
        //OLDALAK
        $maxResult = 9;
        
        $productRepo = $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
        $productPropertyRepo = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty');
        
        //termékek listája, szűrt termékek azonosítója alapján ezt fogjuk szűrni
        $products = $productRepo->createQueryBuilder('p')
                    ->select('p,pi,c,pp,so,prop')
                    ->leftJoin('p.categorys','c')
                    ->leftJoin('p.productImages','pi')
                    ->leftJoin('p.productPropertys', 'pp')
                    ->leftJoin('pp.property', 'prop')
                    ->leftJoin('p.specialOffer', 'so')
                    ->where('p.isActive = 1');
        
        //Bal oldalsó menüben a kategóriák közötti szűrés bekapcsolása
        if ($request->getMethod() != 'POST') {
            if($currentMainCategory != null){
                $products = $products
                        ->addSelect('mc')
                        ->leftJoin('c.mainCategory','mc')
                        ->andWhere('mc.deletedAt is NULL')
                        ->andWhere('mc.name = :main_category OR mc.id = :main_category ')     
                        ->setParameter('main_category',$currentMainCategory);   
            }
            if($category != null){
                $products = $products
                        ->andWhere('c.slug = :category OR c.id = :category ')    
                        ->andWhere('c.deletedAt is NULL')
                        ->setParameter('category',$currentCategory);   
            }        
        }
        
        $request = $this->get('request');       
        $searchParameter = null;
        $onlyOneProductId = null; //ha a keresésnél csak egy termék van
        $reloadPage = "";
        
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
                
                //Szűrés típusa
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
                }else if($filterType == "0"){      
                    $searchString = $request->request->get('generalSearchString');
                    $searchParameter = $searchString;
                    $parameters = array(
                        'generalSearchString' => $searchString,
                        'generalFilterPrice' => $request->request->get('generalFilterPrice'),
                    );
                    if($request->request->get('reloadPage') == null){
                        $withAjax = false;
                        $searchParameter = $searchString;
                    }
                    //Szűrt laptopok azonosítói
                    $service = $this->container->get('product_service');
                    $filteredProductIds = $service->getGeneralFilteredProducts($products, $parameters);
                    if(count($filteredProductIds) == 1 && $filterType == "0"){
                        foreach($filteredProductIds as $key => $val){
                            $onlyOneProductId = $val;
                            break;
                        } 
                        $withAjax = true;                        
                        if($onlyOneProductId != null){
                            if($request->request->get('reloadPage') == null ){
                                return $this->forward('FrontendProductBundle:Default:product', array(
                                    'slug' => $onlyOneProductId
                                ));
                            }
                            $reloadPage = $this->generateUrl('product', array('slug' => $onlyOneProductId));
                        }
                    }
                }else if($filterType == ""){
                    $searchString = $request->request->get('generalSearchString');
                    $searchParameter = $searchString;
                    $parameters = array(
                        'generalSearchString' => $searchString,
                        'generalFilterPrice' => $request->request->get('generalFilterPrice'),
                    );
                    $service = $this->container->get('product_service');
                    $filteredProductIds = $service->getGeneralFilteredProducts($products, $parameters);
                } 
                
                //Termékek lekérdezése szűrés alapján
                $products = $products
                     ->andWhere('p.id IN (:filteredProductIds)')
                     ->setParameter('filteredProductIds',$filteredProductIds)
                     ->andWhere('p.isActive = 1');
                    
        }               
         
        //TERMÉKEK RENDEZÉSE
        $products = $productRepo->getOrderByProduct($products, $order, $by);
        
        $allProductsResult = $products->getQuery()->getResult();  
        
        $allProductCount = count($allProductsResult); //Az összes termék darabszáma az oldalszámok megjelenítéséhez
        
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
        if($searchParameter != null){
            $urlParameters .= '&searchKey=' . $searchParameter;
        }
        //AJAX kérés esetén visszadjuk a felső menüt és a termékek listáját
        if ($request->getMethod() == 'POST' && $withAjax) {            
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
                'pagesMenu' => $pagesMenu,
                'reloadPage' => $reloadPage
                    ));
         }
     
        return $this->render('FrontendProductBundle:Default:index.html.twig',array(
            'products'=>$products,
            'order' => $order,
            'by'=>$by,
            'searchKey' => $searchParameter,
            'actualPage' => $page, 
            'pageCount' => $pageCount,
            'urlParameters' => $urlParameters,
            'productCount' => $allProductCount,
            'mainCategory' => $currentMainCategory,
            'category' => $currentCategory
        ));
    }   
    
    
    /*
     * Adott termék megjelenítése
     */
    public function productAction($slug){
        $productRepo = $this->getDoctrine()->getRepository('FrontendProductBundle:Product');
        $product = $productRepo->createQueryBuilder('p')
                    ->select('p,pi,c,pp,prop')
                    ->leftJoin('p.categorys','c')
                    ->leftJoin('p.productImages','pi')
                    ->leftJoin('p.productPropertys', 'pp')
                    ->leftJoin('pp.property', 'prop')
                    ->where('p.slug = :slug OR p.id = :slug')
                    ->setParameter('slug', $slug)
                    ->getQuery()->getOneOrNullResult();
        
        //Ajánlott termékek 
        $service = $this->container->get('product_service');
        $offerProducts = $service->offerProducts($product);
        
        return $this->render('FrontendProductBundle:Default:product.html.twig',array('product'=>$product, 'offerProducts' => $offerProducts));
    }
    
    /*
     * Termékeknél kérdések feltevése
     */
    public function tabQuestionsAction($productId=null){        
        $user = $this->get('security.context')->getToken()->getUser();
        $request = $this->get('request');
        if($productId == null){
            $productId = $request->request->get('productId');
        }
        
        $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);        
        $productQuestion = new ProductQuestions();
        $productQuestion->setProduct($product);
        if(method_exists($user, 'getProfile')){
            $productQuestion->setName($user->getProfile()->getName());
            $productQuestion->setEmail($user->getEmail());
        }
            
        $form = $this->createForm(new ProductQuestionsType(),$productQuestion);
        if ($request->getMethod() == 'POST') { 
            //Adatok lekérése és elmentése
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $question = $request->request->get('question');
            $questionTime = new \DateTime('now');
            
            //$productQuestion->setQuestion($question);
            $productQuestion->setQuestionTime($questionTime);
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
    
    /*
     * Termékek összehasonlítása 
     */
    public function compareAction($productId){
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { 
            //Lekérjük a munkafolyamatot és beállítjuk a termék azonosítóját
            $session = $request->getSession();
            $compareProducts = $session->get('compareProducts');
            $compareProducts[$productId] = true;
            $session->set('compareProducts', $compareProducts);
            $compareProducts = $session->get('compareProducts');
            
            $service = $this->container->get('product_service');
            $data = $service->getCompareProductPropertysAndValues(); //Lekérjük az összehasonlítandó termékek tulajdonságát és értékét
            $comparePropertys = $data['comparePropertys']; //Tulajdonságok
            $productPropertysValues = $data['productPropertysValues']; //Értékek
            $html = $this->renderView('FrontendProductBundle:Compare:compareList.html.twig',array(
                'comparePropertys' => $comparePropertys,
                'productPropertysValues' => $productPropertysValues
            ));
            return new JsonResponse(array('success' => true, 'html' => $html));
        }
        return new JsonResponse(array('success' => false));
    }
    
    /*
     * Összehasonlítandó termékek listája
     */
    public function compareListAction(){
        $request = $this->get('request');
        //munkafolyamatból lekérjük a termékek listáját
        $session = $request->getSession();        
        $service = $this->container->get('product_service');
        $data = $service->getCompareProductPropertysAndValues();
        $comparePropertys = $data['comparePropertys'];
        $productPropertysValues = $data['productPropertysValues'];
        return $this->render('FrontendProductBundle:Compare:compareList.html.twig',array(
            'comparePropertys' => $comparePropertys,
            'productPropertysValues' => $productPropertysValues
        ));
    }
    
    /*
     * Összehasonlítandó termékek törlése
     */
    public function removeCompareProductAction($productId){
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { 
            $session = $request->getSession();
            $compareProducts = $session->get('compareProducts');
            unset($compareProducts[$productId]); //Töröljük az adott azonosítójú terméket
            $session->set('compareProducts', $compareProducts);
            
            //Újra rendereljük az összehasonlítandó termékek listáját
            $service = $this->container->get('product_service');
            $data = $service->getCompareProductPropertysAndValues();
            $comparePropertys = $data['comparePropertys'];
            $productPropertysValues = $data['productPropertysValues'];
            $html = $this->renderView('FrontendProductBundle:Compare:compareList.html.twig',array(
                'comparePropertys' => $comparePropertys,
                'productPropertysValues' => $productPropertysValues
            ));
            return new JsonResponse(array('success' => true, 'html' => $html));
        }
        return new JsonResponse(array('success' => false));
    }


}