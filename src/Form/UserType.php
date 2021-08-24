<?php

namespace App\Form;

use App\Entity\User;
use App\Form\RoleType;
use App\Services\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', EmailType::class)
            ->add('status', ChoiceType::class, [
                                                // 'label'   => false,
                                                'choices' => array_flip(UserService::STATUS),
                                                // 'mapped'  => false,
            ])
            ->add('gender', ChoiceType::class, [
                                                // 'label'   => false,
                                                'choices' => array_flip(UserService::GENDER),
                                                // 'mapped'  => false,
                                                 ])
            ->add('role', ChoiceType::class, [
                                                    'choices' => array_flip([
                                                                                'ROLE_USER'  => 'UTILISATEUR',
                                                                                'ROLE_ADMIN' => 'ADMINISTRATEUR'
                                                                ]),
                                                    'multiple' => true,
                                                    'mapped'  => false,
                 ])
            ->add('lastname');

        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
