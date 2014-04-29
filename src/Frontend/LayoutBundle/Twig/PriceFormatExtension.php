<?php

namespace Frontend\LayoutBundle\Twig;
use Symfony\Component\Config\Definition\Exception\Exception;

/*
 * Twig kiegészítése az árak megjelenítésére
 */
class PriceFormatExtension extends \Twig_Extension{
    
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('priceFormat', array($this, 'getPriceFormat')),
        );
    }

    public function getPriceFormat($price)
    {
        $formatPrice = number_format($price, 0, ',', ' '); //a számot megadott formátúmúra alakítjuk át pl: 200 000 000 
        return $formatPrice;
    }
    
    public function getName(){
            return 'price_format';
    }
}