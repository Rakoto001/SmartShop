<?php

namespace App\Form;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('status')
            ->add('gender', ChoiceType::class, [
                                                'label'   => false,
                                                'choices' => array_flip(UserService::GENDER),
                                                'mapped'  => false,
                                                 ])
            ->add('lastname')
            // ->add('password')
            ->add('avatar')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
