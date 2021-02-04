<?php

namespace App\Form\Admin\Subscription;

use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateType
 */
class UpdateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('validFrom', DateType::class, [
                'required' => true,
                'label' => 'Galioja nuo',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => "form-control date-input basicFlatpickr"]
            ])->add('validTo', DateType::class, [
                'required' => true,
                'label' => 'Galioja iki',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => "form-control date-input basicFlatpickr"]
            ])->add('submit', SubmitType::class, [
                    'label' => 'Patvirtinti',
                    'attr' => ['class' => "btn btn-primary"],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class
        ]);
    }
}
