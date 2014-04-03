<?php

namespace Frontend\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class OrderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userId')
            ->add('itemsTotal')
            ->add('itemsTotalPrice')
            ->add('comment')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('paymentOptionId')
            ->add('shippingOptionId')
            ->add('paymentState')
            ->add('shippingState')
            ->add('acceptConditions','checkbox', array('label' => 'Vásárlási feltételek:', 'required'  => false))
            ->add('shippingOption','entity', array('label' => 'Szállítási mód:',  'required'  => true, 
                'class' => 'FrontendOrderBundle:ShippingOption', 'property' => 'name',
                'expanded' => true,
                'multiple' => false,
                'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('c');}
                ))
             ->add('paymentOption','entity', array('label' => 'Fizetési mód:',  'required'  => true, 
                'class' => 'FrontendOrderBundle:PaymentOption', 'property' => 'name',
                'expanded' => true,
                'multiple' => false,
                'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('c');}
                ))    
            //->add('shippingOption')
            //->add('paymentOption')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Frontend\OrderBundle\Entity\Order'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_orderbundle_order';
    }
}
