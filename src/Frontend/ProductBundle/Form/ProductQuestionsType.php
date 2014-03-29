<?php

namespace Frontend\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductQuestionsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('productId', 'hidden')
            ->add('name', 'text', array('label' => 'Név:', 'required'  => false))
            ->add('email', 'text', array('label' => 'Email:', 'required'  => false))
            ->add('question', 'textarea', array('label' => 'Kérdés:', 'required'  => false))
            ->add('productId')
            //->add('answer')
            //->add('status')
            //->add('questionTime')
            //->add('answerTime')
           //->add('product')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Frontend\ProductBundle\Entity\ProductQuestions'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontend_productbundle_productquestions';
    }
}
