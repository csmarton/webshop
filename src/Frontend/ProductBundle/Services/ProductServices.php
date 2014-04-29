<?php

namespace Frontend\ProductBundle\Services;


use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+

use Symfony\Component\HttpFoundation\Request;

class ProductServices{    
    
    protected $em;
    private $container;
    private $doctrine;
	
    function __construct(EntityManager $em, Container $container, Doctrine $doctrine){
        $this->em = $em;
        $this->container = $container;
        $this->doctrine = $doctrine;
    }
    
    /*
     * Laptopok szűrése     
     * return:  A szűrt termékek azonosítói
     */
    function getLaptopFilteredProducts($products, $parameters){
        $productRepo = $this->doctrine->getRepository('FrontendProductBundle:Product');
        $productPropertyRepo = $this->doctrine->getRepository('FrontendProductBundle:ProductProperty');
        
        //Adatok a paraméterből
        $laptopFilterManufacturer = $parameters['laptopFilterManufacturer'];
        $laptopFilterWinchester = $parameters['laptopFilterWinchester'];
        $laptopFilterOperationSystem = $parameters['laptopFilterOperationSystem'];
        $laptopFilterProcessor = $parameters['laptopFilterProcessor'];
        $laptopFilterScreenSize = $parameters['laptopFilterScreenSize'];
        $laptopFilterMemory = $parameters['laptopFilterMemory'];
        $laptopFilterPrice = $parameters['laptopFilterPrice'];
        
        $products = $products
            ->andWhere('c.mainCategory = 1'); //laptopokat keresünk
                
        $laptopCategoryId = 1;
        //Lekérjük a laptopokat és eltároljuk az azonosítójukat, ezeket fogjuk szűrés esetén szűkíteni
        $filteredProductIds = $productRepo->getProductIds($laptopCategoryId);

        //Szűrés ár szerint
        list($lowPrice,$hightPrice)=explode("-",$laptopFilterPrice);
        $products = $this->doctrine->getRepository('FrontendProductBundle:Product')->getfilterByPrice($products, $lowPrice, $hightPrice);

        //Szűrés GYÁRTÓ szerint               
        if($laptopFilterManufacturer != ""){
            $filteredProductIds0 = $productRepo->getProductIdsFilterByManufacturer($laptopFilterManufacturer);
            $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds0); 
        }

        //Szűrés MEREVLEMEZ szerint                
        if($laptopFilterWinchester != ""){
           list($first,$second)=explode("-",$laptopFilterWinchester);
           $filteredProductIds1 = $productPropertyRepo->getProductIdsFilterByWinchester($first,$second);
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds1); 
        } 

        //Szűrés OPERÁCIÓS RENDSZER szerint                
        if($laptopFilterOperationSystem != ""){
           $filteredProductIds2 = $productPropertyRepo->getProductIdsFilterByOperatingSystem($laptopFilterOperationSystem);                    
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds2); 
        }

        //Szűrés PROCESSZOR szerint
        if($laptopFilterProcessor != ""){
            $filteredProductIds3 = $productPropertyRepo->getProductIdsFilterByProcessor($laptopFilterProcessor);                    
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds3); 
        }

        //Szűrés KÉPERNYŐ méret szerint
        if($laptopFilterScreenSize != ""){ 
           list($first,$second)=explode("-",$laptopFilterScreenSize);
           $filteredProductIds4 = $productPropertyRepo->getProductIdsFilterByScreenSize($first, $second);
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds4); 
        } 


        //Szűrés MEMÓRIA szerint
        if($laptopFilterMemory != ""){
            list($first,$second)=explode("-",$laptopFilterMemory);
            $filteredProductIds5 = $productPropertyRepo->getProductIdsFilterByMemory($first, $second);
            $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds5); 
        } 
                                      
        return $filteredProductIds;
    }

    
    /*
     * Tabletek szűrése     
     * return:  A szűrt termékek azonosítói
     */
    function getTabletFilteredProducts($products, $parameters){
        $productRepo = $this->doctrine->getRepository('FrontendProductBundle:Product');
        $productPropertyRepo = $this->doctrine->getRepository('FrontendProductBundle:ProductProperty');
        
        //Adatok a paraméterből
        $tabletFilterManufacturer = $parameters['tabletFilterManufacturer'];
        $tabletFilterWinchester = $parameters['tabletFilterWinchester'];
        $tabletFilterOperationSystem = $parameters['tabletFilterOperationSystem'];
        $tabletFilterProcessor = $parameters['tabletFilterProcessor'];
        $tabletFilterScreenSize = $parameters['tabletFilterScreenSize'];
        $tabletFilterMemory = $parameters['tabletFilterMemory'];
        $tabletFilterPrice = $parameters['tabletFilterPrice'];
        
        $tabletCategoryId = 2;
        $products = $products
            ->andWhere('c.mainCategory = 2'); //tableteket keresünk
                

        //Lekérjük a laptopokat és eltároljuk az azonosítójukat, ezeket fogjuk szűrés esetén szűkíteni
        $filteredProductIds = $productRepo->getProductIds($tabletCategoryId);

        //Szűrés ár szerint
        list($lowPrice,$hightPrice)=explode("-",$tabletFilterPrice);
        $products = $this->doctrine->getRepository('FrontendProductBundle:Product')->getfilterByPrice($products, $lowPrice, $hightPrice);

        //Szűrés GYÁRTÓ szerint               
        if($tabletFilterManufacturer != ""){
            $filteredProductIds0 = $productRepo->getProductIdsFilterByManufacturer($tabletFilterManufacturer, $tabletCategoryId);
            $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds0); 
        }

        //Szűrés MEREVLEMEZ szerint                
        if($tabletFilterWinchester != ""){
           
           $filteredProductIds1 = $productPropertyRepo->getProductIdsFilterByWinchester($tabletFilterWinchester,$tabletFilterWinchester, 24);
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds1); 
        } 

        //Szűrés OPERÁCIÓS RENDSZER szerint                
        if($tabletFilterOperationSystem != ""){
           $filteredProductIds2 = $productPropertyRepo->getProductIdsFilterByOperatingSystem($tabletFilterOperationSystem,26);                    
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds2); 
        }

        //Szűrés PROCESSZOR szerint
        if($tabletFilterProcessor != ""){
            $filteredProductIds3 = $productPropertyRepo->getProductIdsFilterByProcessor($tabletFilterProcessor,23);                    
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds3); 
        }

        //Szűrés KÉPERNYŐ méret szerint
        if($tabletFilterScreenSize != ""){ 
           list($first,$second)=explode("-",$tabletFilterScreenSize);
           $filteredProductIds4 = $productPropertyRepo->getProductIdsFilterByScreenSize($first, $second,25);
           $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds4); 
        } 


        //Szűrés MEMÓRIA szerint
        if($tabletFilterMemory != ""){
            list($first,$second)=explode("-",$tabletFilterMemory);
            $filteredProductIds5 = $productPropertyRepo->getProductIdsFilterByMemory($first, $second,27,false);
            $filteredProductIds = array_intersect($filteredProductIds, $filteredProductIds5); 
        } 
                                     
        return $filteredProductIds;
    }
	
    
        /*
     * Általános termékek szűrése     
     * return:  A szűrt termékek azonosítói
     */
    function getGeneralFilteredProducts($products, $parameters){
        $productRepo = $this->doctrine->getRepository('FrontendProductBundle:Product');
        $productPropertyRepo = $this->doctrine->getRepository('FrontendProductBundle:ProductProperty');
        
        //Adatok a paraméterből
        $generalSearchString = $parameters['generalSearchString'];
        $generalFilterPrice = $parameters['generalFilterPrice'];
        
                
        //Lekérjük a laptopokat és eltároljuk az azonosítójukat, ezeket fogjuk szűrés esetén szűkíteni
        $filteredProductIds = $productRepo->getAllProductIds();

        //Szűrés ár szerint
        if($generalFilterPrice != null){
            list($lowPrice,$hightPrice)=explode("-",$generalFilterPrice);
            $products = $this->doctrine->getRepository('FrontendProductBundle:Product')->getfilterByPrice($products, $lowPrice, $hightPrice);
        } 
        //Szűrés kulcsszó alapján
        if($generalSearchString != ""){ 
           $filteredProductIds0 = $productRepo->getProductIdsFilterBySearchString($generalSearchString);           
           $filteredProductIds1 = $productPropertyRepo->getProductIdsFilterBySearchString($generalSearchString);
           $filteredUnion = array_merge($filteredProductIds0,$filteredProductIds1);
           $filteredProductIds = array_intersect($filteredProductIds, $filteredUnion);
        } 
        
        return $filteredProductIds;
    }
    
    function getCompareProductPropertysAndValues(){
        $request = $this->container->get('request');
        $session = $request->getSession();
        $compareProducts = $session->get('compareProducts');
        
        $productPropertysValues = array();
        $comparePropertys = array();        
        if(count($compareProducts) != 0){
            $productIds = array();
            foreach((array)$compareProducts as $key=>$value){
                $productIds[] = $key;
            }
            $products = array();
            $repo =  $this->doctrine->getRepository('FrontendProductBundle:Product');
            $products = $repo
                    ->createQueryBuilder('p')                   
                    ->where('p.id IN (:productIds)')
                    ->setParameter('productIds',$productIds)
                    ->getQuery()->getResult();
            $mainCategoryId = $products[0]->getCategorys()->getMainCategory()->getId();
            
            $propertys = $this->doctrine->getRepository('FrontendProductBundle:Propertys')
                    ->createQueryBuilder('p')       
                    ->where('p.mainCategory = :mainCategoryId')
                    ->orderBy('p.orderValue','asc')
                    ->setMaxResults(8)
                    ->setParameter('mainCategoryId',$mainCategoryId )
                    ->getQuery()->getResult();
            foreach($propertys as $property){
                $comparePropertys[] = $property;
            }    
            
              
            $i = 1;
            foreach($products as $product){
                $productId = $product->getId();
                $productPropertysValues[$productId] = array();
                $productPropetys = $product->getProductPropertys();
                $productPropertysValues[$productId][0] = $product->getName();
                $productPropertysValues[$productId][1] = $product->getRealPrice();
                $j = 2;
                foreach($comparePropertys as $property){                    
                    $productPropertysValues[$productId][$j] = $this->inPropertys($productPropetys,$property->getId());
                    $j++;               
                }
                $productPropertysValues[$productId][$j] = $product;
                $i++;                
            }
            
        }
        return array('comparePropertys' => $comparePropertys, 'productPropertysValues' => $productPropertysValues);
    }
    function inPropertys($propertys, $id){
        foreach($propertys as $property){
            if($property->getProperty()->getId() == $id){
                return $property->getValue();
            }
        }
        return "-";
    }
}