<?php

namespace App\Form\Admin\Subscription;

use App\Entity\Subscription;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateType
 */
class CreateType extends AbstractType
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * CreateType constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userEmail', TextType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Vartotojo Elpaštas',
                'attr' => ['class' => "form-control"]
            ])->add('validFrom', DateType::class, [
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
            ])->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $subscription = $event->getData();
                $email = $event->getForm()->get('userEmail')->getData();
                /** @var User $user */
                $user = $this->userService->getOneBy(['email' => $email]);

                if (!$user) {
                    $error = new FormError('Vartotojas su email ' . $email . ' neegzistuoja. Patikrinkite email.');
                    $event->getForm()->get('userEmail')->addError($error);
                } else {
                    $subscription->setUser($user);
                    if ($user->isRegularUser()) {
                        $user->setRole(User::ROLE_KLUBO_NARYS);
                        $this->userService->update($user);
                    }
                }
            });
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
