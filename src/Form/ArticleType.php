<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Category;
use App\Services\ArticleService;
use App\Services\CategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ArticleType extends AbstractType
{
    protected $categorieService;

    public function __construct(CategoryService $_categorieService) {
        $this->categorieService = $_categorieService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('Content')
            ->add('status',ChoiceType::class,[
                                                'choices' => array_flip(ArticleService::STATUS_OPTION)
                                            ]
            )
            ->add('price', NumberType::class,[
                                                'attr' => array(
                                                    'type' => 'number',
                                                    // 'max' => 90,
                                                ),
                                            ])
            ->add('category', EntityType::class, [
                                                'class' => Category::class,
                                                'choice_label' => 'name',
                                                'choices' => $this->categorieService->getActifCategory(1),
                                                'attr' => [
                                                    'class' => 'form-control',
                                                         ],
                                                    'empty_data' => null,
                                                    'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
