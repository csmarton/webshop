<?php

namespace Frontend\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PropertysType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Tulajdonság neve:', 'required'  => false))
            //->add('createdAt')
            //->add('updatedAt')
            ->add('orderValue', 'integer', array('label' => 'Rendezési sorrend:', 'required'  => false))    
            ->add('mainCategory','entity', array('label' => 'Kategória:',  'required'  => false, 
                'class' => 'FrontendProductBundle:MainCategory', 'property' => 'name',
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
            'data_class' => 'Frontend\ProductBundle\Entity\Propertys'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_productbundle_propertys';
    }
}
