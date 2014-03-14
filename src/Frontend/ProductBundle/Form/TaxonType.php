<?php

namespace Frontend\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class TaxonType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxonomyId')
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('permalinks')
           // ->add('taxonomy')
            ->add('taxonomy', 'entity', array('label' => 'Taxonomy:', 'empty_value' =>  "Válassz!", 'required'  => false, 
                                    'class' => 'FrontendProductBundle:Taxonomy', 
                                    'property' => 'name',
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
            'data_class' => 'Frontend\ProductBundle\Entity\Taxon'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_productbundle_taxon';
    }
}
