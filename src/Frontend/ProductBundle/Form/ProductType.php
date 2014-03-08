<?php

namespace Frontend\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('shortDescription')
            ->add('price')
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
        return 'frontend_productbundle_product';
    }
}
