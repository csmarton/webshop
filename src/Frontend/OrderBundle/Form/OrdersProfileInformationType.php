<?php

namespace Frontend\OrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrdersProfileInformationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('telephone')
            ->add('billingAddressCity')
            ->add('billingAddressStreet')
            ->add('billingAddressStreetNumber')
            ->add('billingAddressZipCode')
            ->add('shippingAddressCity')
            ->add('shippingAddressStreet')
            ->add('shippingAddressStreetNumber')
            ->add('shippingAddressZipCode')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Frontend\OrderBundle\Entity\OrdersProfileInformation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_orderbundle_ordersprofileinformation';
    }
}
