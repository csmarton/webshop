<?php

namespace Frontend\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ProfileType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('userId')
            ->add('name', 'text', array('label' => 'Név:', 'required'  => false))
            ->add('billingAddressCity', 'text', array('label' => 'Település:', 'required'  => false))
            ->add('billingAddressStreet', 'text', array('label' => 'Útca/út:', 'required'  => false))
            ->add('billingAddressStreetNumber', 'text', array('label' => 'Házszám:', 'required'  => false))
            ->add('billingAddressZipCode', 'text', array('label' => 'Irányítószám:', 'required'  => false))
            ->add('shippingAddressCity', 'text', array('label' => 'Település:', 'required'  => false))
            ->add('shippingAddressStreet', 'text', array('label' => 'Útca/út:', 'required'  => false))
            ->add('shippingAddressStreetNumber', 'text', array('label' => 'Házszám:', 'required'  => false))
            ->add('shippingAddressZipCode', 'text', array('label' => 'Irányítószám:', 'required'  => false))
            ->add('telephone', 'text', array('label' => 'Telefonszám:', 'required'  => false))
            //->add('user')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Frontend\ProfileBundle\Entity\Profile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_profilebundle_profile';
    }
}
