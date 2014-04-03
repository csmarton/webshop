<?php

namespace Frontend\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('acceptConditions')
            ->add('shipping_option')
            ->add('payment_option')
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
