<?php

namespace App\Form\Admin\Movie;

use App\Entity\Movie;
use App\EventListener\MovieListener;
use App\Service\CategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
     * @var MovieListener
     */
    private $movieListener;

    /**
     * CreateType constructor.
     *
     * @param CategoryService $categoryService
     * @param MovieListener $movieListener
     */
    public function __construct(CategoryService $categoryService, MovieListener $movieListener)
    {
        $this->categoryService = $categoryService;
        $this->movieListener = $movieListener;
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
                'label' => 'Pavadinimas',
                'attr' => ['class' => "form-control"]
            ])->add(
                'imageFile', FileType::class, [
                    'required' => false,
                    'label' => 'Filmo Viršelis',
                    'attr' => [
                        'class' => "form_gallery-upload",
                        "data-name" => "#gallery2"
                    ]
                ]
            )->add(
                'category',
                ChoiceType::class,
                [
                    'label' => 'Kategorija',
                    'choices' => $this->getCategoryChoices(),
                    'attr' => ['class' => "form-control"]
                ]
            )->add('price', MoneyType::class, [
                'required' => false,
                'label' => 'Įprasta Bilieto Kaina',
                'mapped' => false,
                'scale' => 2,
                'attr' => ['class' => "form-control"],
            ])->add('clubPrice', MoneyType::class, [
                'required' => false,
                'label' => 'Bilieto Kaina Klubo Nariams',
                'mapped' => false,
                'scale' => 2,
                'attr' => ['class' => "form-control"],
            ])->add('date', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Pasirinkite Transliavimo Datą',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => "form-control date-input basicFlatpickr"]
            ])->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Aprašymas',
                'attr' => ['class' => "form-control"]
            ])->add('movieFile', FileType::class, [
                    'required' => false,
                    'label' => 'Filmas',
                    'attr' => ['class' => 'movie-add-moviefile']
                ]
            )->add('subtitlesFile', FileType::class, [
                    'required' => false,
                    'label' => 'Subtitrai',
                    'attr' => ['class' => 'movie-add-subtitlesFile']
                ]
            )->add('previewUrl', TextType::class, [
                'required' => false,
                'label' => 'Anonso Nuoroda',
                'attr' => ['class' => "form-control"]
            ])->add('year', TextType::class, [
                'required' => false,
                'label' => 'Metai',
                'attr' => ['class' => "form-control"]
            ])->add('director', TextType::class, [
                'required' => false,
                'label' => 'Režisierius',
                'attr' => ['class' => "form-control"]
            ])->add('country', TextType::class, [
                'required' => false,
                'label' => 'Šalis',
                'attr' => ['class' => "form-control"]
            ])->add('duration', TextType::class, [
                'required' => false,
                'label' => 'Trukmė',
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => 'pvz: 1h 15min'
                ]
            ])->add('free', CheckboxType::class, [
                'label'    => 'Pažymėkite varnelę, jeigu filmas yra nemokamas klubo nariams',
                'required' => false,
                'attr' => [
                    'class' => "form-control club-checkbox d-flex align-items-center",
                ],
            ])->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Patvirtinti',
                    'attr' => ['class' => "btn btn-primary"],
                ]
            )->addEventSubscriber($this->movieListener);
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
