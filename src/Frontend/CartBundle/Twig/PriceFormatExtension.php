<?php

namespace Frontend\CartBundle\Twig;
use Symfony\Component\Config\Definition\Exception\Exception;

class PriceFormatExtension extends \Twig_Extension{
    
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('priceFormat', array($this, 'getPriceFormat')),
        );
    }

    public function getPriceFormat($price)
    {
        $formatPrice = number_format($price, 0, ',', ' ');
        return $formatPrice;
    }
    
    public function getName(){
            return 'price_format';
    }
}