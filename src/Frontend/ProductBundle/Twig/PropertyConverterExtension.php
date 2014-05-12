<?php

namespace Frontend\ProductBundle\Twig;
use Symfony\Component\Config\Definition\Exception\Exception;
use Frontend\ProductBundle\Entity\ProductProperty;

class PropertyConverterExtension extends \Twig_Extension{
    
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('propertyConverterToGB', array($this, 'getPropertyConverterToGB')),
            new \Twig_SimpleFilter('propertyConverter', array($this, 'getPropertyConverter'))
        );
    }

    /*
     * Tulajdonság értékének konvertálása GB-ra
     */
    public function getPropertyConverterToGB(ProductProperty $productProperty)
    {
        $property = $productProperty->getProperty();
        $value = $productProperty->getValue();
        if($property->getId() == 27 || $property->getId() == 4 || $property->getId() == 24 || $property->getId() == 6){ //1-2 Memória, 3-4 Merevlemez
            return round($value/1024/1024) . " Gb";
        }else{
            return $value;
        }
    }
    
    public function getPropertyConverter(ProductProperty $productProperty)
    {
        $property = $productProperty->getProperty();
        $value = $productProperty->getValue();
        if($property->getId() == 27 || $property->getId() == 4 || $property->getId() == 24 || $property->getId() == 6){ //1-2 Memória, 3-4 Merevlemez
            $v = $value/1024/1024;
            if($v<1){
                $v = round($value/1024) ." MB";
            }else{
                $v = round($value/1024/1024) ." GB";
            }
            return $v;
        }else{
            return $value;
        }
    }
    
    public function getName(){
            return 'property_converter';
    }
}