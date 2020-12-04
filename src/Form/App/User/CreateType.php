<?php

namespace App\Form\App\User;

use App\Entity\User;
use App\Validator\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CreateType
 */
class CreateType extends AbstractType
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'label' => false,
                    'attr' => [
                        'class' => "form-control mb-0",
                        'placeholder' => 'Vardas',
                    ],
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'label' => false,
                    'attr' => [
                        'class' => "form-control mb-0",
                        'placeholder' => 'Pavardė',
                    ]
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new UniqueEmail(),
                    ],
                    'label' => 'El-paštas',
                    'attr' => [
                        'class' => "form-control mb-0",
                        'placeholder' => 'El-paštas',
                    ]
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'invalid_message' => 'Slaptažodžiai nesutampa.',
                    'label' => false,
                    'first_options'  => [
                        'label' => false,
                        'attr' => [
                            'class' => "form-control mb-0 mgb10",
                            'placeholder' => 'Slaptažodis',
                        ],
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ],
                    'second_options' => [
                        'label' => false,
                        'attr' => [
                            'class' => "form-control mb-0",
                            'placeholder' => 'Patvirtinti Slaptažodį',
                        ],
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ],
                ]
            )->add('clubRequest', CheckboxType::class, [
                'label'    => 'Pažymėkite varnelę, jeigu esate klubo narys',
                'required' => false,
                'attr' => [
                    'class' => "form-control club-checkbox d-flex align-items-center",
                ],
            ])->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Registruotis',
                    'attr' => ['class' => "btn btn-hover full-width"],
                ]
            )
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                if ($event->getForm()->isValid()) {
                    /** @var User $user */
                    $user = $event->getData();
                    $plainPassword = $event->getForm()->get('password')->getData();
                    $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
                    $user->setPassword($encodedPassword);
                    $user->setRole(User::ROLE_USER);
                }
            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'data_class' => User::class,
                    'csrf_protection' => false,
                ]
            );
    }
}
