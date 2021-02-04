<?php

namespace App\Command;

use App\Entity\User;
use App\Service\EmailSender;
use App\Service\SubscriptionService;
use App\Service\UserService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CheckSubscriptionsCommand
 */
class CheckSubscriptionsCommand extends Command
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * @var EmailSender
     */
    private $emailSender;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * CheckSubscriptionsCommand constructor.
     *
     * @param string|null $name
     * @param UserService $userService
     * @param SubscriptionService $subscriptionService
     * @param EmailSender $emailSender
     * @param TranslatorInterface $translator
     */
    public function __construct(
        string $name = null,
        UserService $userService,
        SubscriptionService $subscriptionService,
        EmailSender $emailSender,
        TranslatorInterface $translator
    ) {
        parent::__construct($name);
        $this->userService = $userService;
        $this->subscriptionService = $subscriptionService;
        $this->emailSender = $emailSender;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:check-subscriptions')
            ->setDescription('Removes club roles for expired subscriptions')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $usersWithExpiredSubscriptions = $this->userService->getAllWithExpiredSubscription();

        foreach ($usersWithExpiredSubscriptions as $userWithExpiredSubscription) {
            $userWithExpiredSubscription->setRole(User::ROLE_USER);
            $this->userService->update($userWithExpiredSubscription);

            $this->emailSender->send(
                $userWithExpiredSubscription->getEmail(),
                $userWithExpiredSubscription->getFirstName(),
                $this->translator->trans('email.alert_expired.title'),
                $this->translator->trans('email.alert_expired.body', ['url' => $_ENV['DOMAIN'] . '/subscription/'])
            );
        }

        return 1;
    }
}
