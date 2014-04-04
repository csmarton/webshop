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
	
    function __construct(EntityManager $em, Container $container, Doctrine $doctrine){
		$this->em = $em;
        $this->container = $container;
    }
    
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

    
	
}