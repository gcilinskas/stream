<?php

namespace App\Form\App\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class EditType
 */
class EditType extends AbstractType
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * EditType constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
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
        $builder->add(
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
            )->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Išsaugoti',
                    'attr' => ['class' => "btn btn-hover"],
                ]
            )->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
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
