<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
				->add('name', TextType::class, (["label"=>"Nombre","attr"=>(["class"=>"form-name form-control"])]))
				->add('surname', TextType::class, (["label"=>"Apellido","attr"=>(["class"=>"form-surname form-control"])]))
				->add('email', EmailType::class, (["label"=>"Email","attr"=>(["class"=>"form-email form-control"])]))
				->add('password', PasswordType::class, (["label"=>"Contraseña","attr"=>(["class"=>"form-password form-control"])]))
				->add('Registrar', SubmitType::class,(["attr"=>(["class"=>"btn btn-success"])]));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_user';
    }


}
