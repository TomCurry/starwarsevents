<?php

namespace Yoda\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of RegisterFormType
 *
 * @author tom
 */
class RegisterFormType extends AbstractType
{
    public function getName()
    {
        return 'user_register';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text')
                ->add('email', 'email', array(
                    'label' => 'Email Address',
                    'attr' => array('class' => 'C-3PO')
                ))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password'
                ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yoda\UserBundle\Entity\User',
        ));
    }



}
