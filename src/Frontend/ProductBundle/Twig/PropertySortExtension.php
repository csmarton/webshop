<?php

namespace Frontend\ProductBundle\Twig;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\Common\Collections\Collection;

class PropertySortExtension extends \Twig_Extension{
    
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('propertySort', array($this, 'getPropertySort')),
        );
    }

    public function getPropertySort(Collection $objects)
    {
        $values = $objects->getValues();
        usort($values, function($a, $b){
            if($a->getProperty()->getOrderValue() > $b->getProperty()->getOrderValue()){
                return 1;
            }else{
                return -1;
            }
        });
        return $values;
    }
    
    public function getName(){
            return 'property_sor';
    }
}