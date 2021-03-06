<?php

namespace Frontend\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * Add your own custom repository methods below.
 */
class ProductRepository extends EntityRepository{

    /*
     * Laptopok azonosítói
     */
    public function getProductIds($catId = 1){
        $productIds = $this->createQueryBuilder('p')
                    ->select('p.id')
                    ->leftJoin('p.categorys','c')
                    ->where('c.mainCategory = :catId')
                    ->setParameter('catId', $catId)
                    ->getQuery()->getResult();
               
        $filteredProductIds = array(); //Laptopok azonosítói
        foreach((array)$productIds as $p){
             $filteredProductIds[] = $p['id'];
        } 
        return $filteredProductIds;
    }
    
        /*
     * Összes termék azonosítói
     */
    public function getAllProductIds(){
        $productIds = $this->createQueryBuilder('p')
                    ->select('p.id')                    
                    ->getQuery()->getResult();
               
        $filteredProductIds = array(); //Laptopok azonosítói
        foreach((array)$productIds as $p){
             $filteredProductIds[] = $p['id'];
        } 
        return $filteredProductIds;
    }
    /**
     * Ár szerinti szűrés
     */
    public function getFilterByPrice($products, $lowPrice, $hightPrice){
        return  $products
                    ->where('p.price >= :lowPrice and p.price <= :hightPrice')
                    ->setParameter('lowPrice',(int)$lowPrice )
                    ->setParameter('hightPrice',(int)$hightPrice );
    }
    
    /*
     * Gyártó szerinti szűrés
     */
    public function getProductIdsFilterByManufacturer($laptopFilterManufacturer, $catId = 1){
        $filtered0 = array();
        $filteredProductIds0 = array();
        $productIdsFromProperty = $this->createQueryBuilder('p')
                ->select('p.id')
                ->leftJoin('p.categorys','c')
                ->where('c.mainCategory = :catId')                
                ->andWhere('c.name LIKE :manufacturer')
                ->setParameter('catId', $catId)
                ->setParameter('manufacturer',$laptopFilterManufacturer);
        $filtered0 =  $productIdsFromProperty->getQuery()->getResult();
        foreach((array)$filtered0 as $f0){
            $filteredProductIds0[] = $f0['id'];
        }
        return $filteredProductIds0;
    }
    
    /*
     * Rendezés megadott szempontok alapján
     */
    public function getOrderByProduct($products,$order, $by){
        if($order == "promotion" && $by=="desc"){
            $products = $products
                        ->orderBy('p.salesPrice', 'desc')
                        ->addOrderBy('c.mainCategory','asc'); 
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
                ->orderBy('p.salesPrice', 'desc')
                ->addOrderBy('c.mainCategory','asc');        
        }
        return $products;
    }

    /*
     * Általános keresés
     */
    public function getProductIdsFilterBySearchString($generalSearchString){
        $productIds = $this->createQueryBuilder('p')
                    ->select('p.id')
                    ->leftJoin('p.categorys','c')
                    ->leftJoin('c.mainCategory','mc')
                    ->where('c.name LIKE :generalSearchString OR p.name LIKE :generalSearchString OR mc.name LIKE :generalSearchString')
                    ->setParameter('generalSearchString', "%".$generalSearchString."%")
                    ->andWhere('p.isActive = 1')
                    ->getQuery()->getResult();
               
        $filteredProductIds = array(); //Laptopok azonosítói
        foreach((array)$productIds as $p){
             $filteredProductIds[] = $p['id'];
        } 
        return $filteredProductIds;
    }
    
    //Termékek keresése kulcsszó alapján
    public function findProductBySearchKey($key, $maxResults = null){
        $products = $this->createQueryBuilder('p')
                ->select('p')
                ->leftJoin('p.categorys','c')                         
                ->leftJoin('c.mainCategory','mc')
                ->where('p.name LIKE :key OR c.name LIKE :key OR mc.name LIKE :key')
                ->andWhere('p.isActive = 1')
                ->setParameter('key', "%".$key."%");
        if($maxResults != null){
              $products = $products
                ->setMaxResults($maxResults);
        }
        return $products->getQuery()->getResult();
    }
    
    //Termékek keresése kulcsszó alapján
    public function findProductIdBySearchKey($key){
        return $this->createQueryBuilder('p')
                ->select('p.id')
                ->leftJoin('p.categorys','c')                         
                ->leftJoin('c.mainCategory','mc')
                ->where('p.name LIKE :key OR c.name LIKE :key OR mc.name LIKE :key')
                ->andWhere('p.isActive = 1')
                ->setParameter('key', "%".$key."%")
                ->getQuery()->getResult();
    }
}