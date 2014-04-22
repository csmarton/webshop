<?php

namespace Frontend\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductPropertyRepository
 *
 * Add your own custom repository methods below.
 */
class ProductPropertyRepository extends EntityRepository{

    /*
     * Szűrés gyártó alapján
     */
    public function getProductIdsFilterByWinchester($first, $second){
        $filteredProductIds1 = array();
        $filtered1 = array();

        $productIdsFromProperty = $this->createQueryBuilder('pp')
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
        return $filteredProductIds1;
    }
    
    public function getProductIdsFilterByOperatingSystem($laptopFilterOperationSystem){
        $filteredProductIds2 = array();
        $filtered2 = array();
        $filtered2 = $this->createQueryBuilder('pp')
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
        return $filteredProductIds2;
    }
    
    public function getProductIdsFilterByProcessor($laptopFilterProcessor){
        $filtered3 = array();
        $filteredProductIds3 = array();
        $filtered3 = $this->createQueryBuilder('pp')
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
        return $filteredProductIds3;
    }
    
    public function getProductIdsFilterByScreenSize($first, $second){
        $filteredProductIds4 = array();
        $filtered4 = array();
        $productIdsFromProperty = $this->createQueryBuilder('pp')
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
        return $filteredProductIds4;
    }
    
    public function getProductIdsFilterByMemory($first, $second){
        $filteredProductIds5 = array();
        $filtered5 = array();
        
        $productIdsFromProperty = $this->createQueryBuilder('pp')
            ->select('product.id')
            ->leftJoin('pp.product','product') 
            ->leftJoin('pp.property','p')
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
        return $filteredProductIds5;
    }
    
    public function getProductIdsFilterBySearchString($generalSearchString){
        $productIds = $this->createQueryBuilder('pp')
                    ->select('product.id')
                    ->leftJoin('pp.product','product') 
                    ->leftJoin('pp.property','p')
                    ->where('p.name LIKE :generalSearchString OR pp.value LIKE :generalSearchString')
                    ->setParameter('generalSearchString', "%".$generalSearchString."%")
                    ->getQuery()->getResult();
        $filteredProductIds = array(); //Laptopok azonosítói
        foreach((array)$productIds as $p){
             $filteredProductIds[] = $p['id'];
        } 
        return $filteredProductIds;
    }

}