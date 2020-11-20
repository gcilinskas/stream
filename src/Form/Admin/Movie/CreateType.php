<?php

namespace App\Form\Admin\Movie;

use App\Entity\Movie;
use App\Service\CategoryService;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'required' => true
            ])
            ->add('description', TextType::class, [
                'required' => false
            ])
            ->add('price', NumberType::class, [
                'required' => false,
                'scale' => 2
            ])
            ->add('date', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd'
            ])
            ->add(
                'movieFile',
                FileType::class,
                [
                    'label' => 'Filmas',
                ]
            )
            ->add(
                'imageFile',
                FileType::class,
                [
                    'label' => 'Filmo Virselis',
                ]
            )
            ->add(
                'category',
                ChoiceType::class,
                [
                    'choices' => $this->getCategoryChoices(),
                ]
            )
        ;
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
