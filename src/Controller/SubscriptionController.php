<?php

namespace App\Controller;

use App\Security\SubscriptionViewVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubscriptionController
 * @Route("/subscription")
 */
class SubscriptionController extends AbstractController
{
    /**
     * @Route("/", name="app_subscription_view")
     *
     * @return Response
     */
    public function index(): Response
    {
        if ($subscription = $this->getUser()->getSubscription()) {
            $this->denyAccessUnlessGranted(SubscriptionViewVoter::ATTRIBUTE, $subscription);
        }

        return $this->render('app/subscription/index.html.twig', [
            'subscription' => $subscription
        ]);
    }
}
