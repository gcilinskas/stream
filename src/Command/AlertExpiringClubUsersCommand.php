<?php

namespace App\Command;

use App\Service\EmailSender;
use App\Service\SubscriptionService;
use App\Service\UserService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AlertExpiringClubUsersCommand
 */
class AlertExpiringClubUsersCommand extends Command
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
     * AlertExpiringClubUsersCommand constructor.
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
            ->setName('app:alert-expiring-club-users')
            ->setDescription('Alerts expiring club users to renew club membership')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userService->getAllWithLastMonthSubscription();

        foreach ($users as $user) {
            $this->emailSender->send($user->getEmail(),
                $user->getFirstName(),
                $this->translator->trans('email.alert_expiring.title'),
                $this->translator->trans('email.alert_expiring.body',
                    [
                        'validTo' => $user->getSubscription()->getValidTo()->format('Y-m-d'),
                        'url' => $_ENV['DOMAIN'] . '/subscription/'
                    ]
                )
            );
        }

        return 1;
    }
}
