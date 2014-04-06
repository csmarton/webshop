<?php

namespace Frontend\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Termék neve:', 'required'  => false))
            ->add('slug', 'text', array('label' => 'Slug:', 'required'  => false))
            ->add('description', 'textarea', array('label' => 'Termék leírása:', 'required'  => false))
            ->add('createdAt', 'date',
		  array('label' => 'Létrehozás dátuma::','empty_value' => array('year' => 'év', 'month' => 'hó', 'day' => 'nap')))
            ->add('updatedAt', 'date',
		  array('label' => 'Frissítés dátuma:','empty_value' => array('year' => 'év', 'month' => 'hó', 'day' => 'nap')))
            ->add('price', 'integer', array('max_length'=>15,'label' => 'Bruttó ár:', 'required'  => false))    
            ->add('category', 'entity', array('label' => 'Kategória:',  'required'  => false, 
                                'class' => 'FrontendProductBundle:Category', 'property' => 'name',
                                'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('c');}
                                ))
            ->add('isActive', 'checkbox', array('label' => "Aktív -e?", 'required'  => false))
            ->add('inStock', 'integer', array('label' => "Raktáron van -e?", 'required'  => false))
            ->add('categorys','entity', array('label' => 'Kategória:',  'required'  => false, 
                                'class' => 'FrontendProductBundle:Category', 'property' => 'name',
                                'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('c');}
                                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Frontend\ProductBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_productbundle_product';
    }
}
