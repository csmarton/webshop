<?php

namespace Frontend\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductPropertyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            //->add('property')
            ->add('property','entity', array('label' => 'Tulajdonság:',  'required'  => false, 
                            'class' => 'FrontendProductBundle:Propertys', 'property' => 'name',
                            'query_builder' => function(EntityRepository $er) {
                                return $er->createQueryBuilder('c')
                                        ->select('c')
                                ;
                                
                            }
                            ))
            //->add('product')
            //->add('mainCategory')
        ;
    }
    

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Frontend\ProductBundle\Entity\ProductProperty'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_productbundle_productproperty';
    }
}
