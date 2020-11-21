<?php

namespace App\Form\Api\PayseraPayment;

use App\Entity\Movie;
use App\Entity\PaymentAddress;
use App\Entity\PayseraPayment;
use App\Entity\Price;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CreateType
 */
class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', EntityType::class, [
                'entry_class' => Price::class,
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'required' => true
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'required' => true
            ])
            ->add('paymentAddress', EntityType::class, [
                'class' => PaymentAddress::class,
                'required' => true
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayseraPayment::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ]);
    }
}
