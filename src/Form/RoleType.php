<?php

namespace App\Form;

use App\Entity\Roles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('title')
            // ->add('users')
            ->add('title', ChoiceType::class, [
                                                'choices' => array_flip([
                                                                            'ROLE_USER'  => 'UTILISATEUR',
                                                                            'ROLE_ADMIN' => 'ADMINISTRATEUR'
                                                            ]),
                                                'multiple' => true,
                                                'mapped'  => false,
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Roles::class,
        ]);
    }
}
