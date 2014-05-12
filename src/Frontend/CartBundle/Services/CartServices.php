<?php

namespace Frontend\CartBundle\Services;


use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+

use Symfony\Component\HttpFoundation\Request;

class CartServices{    
    
    protected $em;
    private $container;
    private $doctrine;
	
    /*
     * Konstruktor
     */
    function __construct(EntityManager $em, Container $container, Doctrine $doctrine){
        $this->em = $em;
        $this->container = $container;
        $this->doctrine = $doctrine;
    }
    
    /*
     * Kosárban lévő termékek darabszáma
     */
    function getCartCount(){
        $request = $this->container->get('request');
        $session = $request->getSession();
        $inCart = $session->get('cart');
        $cartCount = 0;
        
        foreach((array)$inCart as $key => $value){
            $cartCount+=$value;
        }
        return $cartCount;
    }
    
    /*
     * Kosárba lévő termékek darabszámmal együtt
     */
    function getProductWithCount(){
        $request = $this->container->get('request');
        $session = $request->getSession();
        $inCart = $session->get('cart');
        $productIds[] = array(); //lekédezzük a munkafolyamatból a termékek azonosítóját
        foreach((array)$inCart as $key=>$value){
            $productIds[] = $key;
        }

        $repo =  $this->doctrine->getRepository('FrontendProductBundle:Product'); //lekérjük a termékeket
        $products = array();
        if(count($productIds) != 0){
            $products = $repo
                        ->createQueryBuilder('p') 
                        ->select('p,pi,pp,prop')
                        ->leftJoin('p.productImages','pi')
                        ->leftJoin('p.productPropertys', 'pp')
                        ->leftJoin('pp.property', 'prop')
                        ->where('p.id IN (:productIds)')
                        ->setParameter('productIds',$productIds)
                        ->getQuery()->getResult();
        }    
        $productsWithCount = array();
        foreach((array)$products as $product){
            $productsWithCount[] = array($product,$inCart[$product->getId()]); //Darabszám és termék
        }
        return $productsWithCount;
    }

    
	
}