<?php

namespace App\Form\Admin\Movie;

use App\Entity\Movie;
use App\Service\CategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 */
class CreateType extends AbstractType
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * CreateType constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'attr' => ['class' => "form-control"]
            ])
            ->add(
                'imageFile', FileType::class, [
                    'required' => false,
                    'label' => 'Filmo Viršelio Paveikslėls',
                    'attr' => [
                        'class' => "form_gallery-upload",
                        "data-name" => "#gallery2"
                    ]
                ]
            )
            ->add(
                'category',
                ChoiceType::class,
                [
                    'choices' => $this->getCategoryChoices(),
                    'attr' => ['class' => "form-control"]
                ]
            )
            ->add('price', MoneyType::class, [
                'required' => false,
                'mapped' => false,
                'scale' => 2,
                'attr' => ['class' => "form-control"],
            ])
            ->add('date', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => "form-control date-input basicFlatpickr"]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => "form-control"]
            ])
            ->add(
                'movieFile', FileType::class, [
                    'required' => false,
                    'label' => 'Filmas',
                    'attr' => ['class' => 'movie-add-moviefile']
                ]
            )->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Patvirtinti',
                    'attr' => ['class' => "btn btn-primary"],
                ]
            );
    }

    /**
     * @return array
     */
    private function getCategoryChoices()
    {
        $categories = [];
        foreach ($this->categoryService->getAll() as $index => $category) {
            if (!$index) {
                $categories['Pasirinkite Kategoriją'] = null;
            }
            $categories[$category->getTitle()] = $category;
        }

        return $categories;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
