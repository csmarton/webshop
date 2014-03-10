<?php

namespace Frontend\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Frontend\ProductBundle\Entity\Taxon;
class ProductType extends AbstractType
{
    public $taxons;
    
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('name', 'text', array('label' => 'Termék neve:', 'required'  => true))
            ->add('slug', 'text', array('label' => 'Slug:', 'required'  => false))
            ->add('description', 'textarea', array('label' => 'Termék leírása:', 'required'  => false))
            ->add('createdAt', 'date',
		  array('label' => 'Létrehozás dátuma::','empty_value' => array('year' => 'év', 'month' => 'hó', 'day' => 'nap')))
            ->add('updatedAt', 'date',
		  array('label' => 'Frissítés dátuma:','empty_value' => array('year' => 'év', 'month' => 'hó', 'day' => 'nap')))
            ->add('shortDescription', 'text', array('label' => 'Termék rövid leírása:', 'required'  => false))
            ->add('price', 'integer', array('max_length'=>15,'label' => 'Ár:', 'required'  => false))
        ;

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => array(
                'Frontend\ProductBundle\Entity\Product',
                'Frontend\ProductBundle\Entity\ProductTaxon'
                )
        ));
    }*/

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_type';
    }
}
