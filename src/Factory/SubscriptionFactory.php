<?php

namespace App\Factory;

use App\Entity\PayseraPayment;
use App\Entity\Subscription;
use App\Service\SubscriptionService;
use DateTime;
use Exception;

/**
 * Class SubscriptionFactory
 */
class SubscriptionFactory
{
    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * TicketFactory constructor.
     *
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param PayseraPayment $payseraPayment
     *
     * @return Subscription
     * @throws Exception
     */
    public function upgradeByPayment(PayseraPayment $payseraPayment): Subscription
    {
        if ($subscription = $payseraPayment->getUser()->getSubscription()) {
            $validTo = $subscription->getValidTo()
                ? (new DateTime())->setTimestamp($subscription->getValidTo()->getTimestamp())->modify('+1 year')
                : (new DateTime())->modify('+1 year');

            $subscription->setValidTo($validTo)->addPayseraPayment($payseraPayment);
            $this->subscriptionService->update($subscription);
        } else {
            $subscription = (new Subscription())->setUser($payseraPayment->getUser())
                ->addPayseraPayment($payseraPayment)
                ->setValidFrom(new DateTime())
                ->setValidTo((new DateTime())->modify('+1 year'));
            $this->subscriptionService->create($subscription);
        }

        return $subscription;
    }
}
