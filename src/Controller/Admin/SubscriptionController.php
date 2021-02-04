<?php

namespace App\Controller\Admin;

use App\Entity\Subscription;
use App\Form\Admin\Subscription\CreateType;
use App\Form\Admin\Subscription\UpdateType;
use App\Service\SubscriptionService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubscriptionController
 * @Route("/subscription")
 */
class SubscriptionController extends AbstractController
{
    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * SubscriptionController constructor.
     *
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @Route("/add", name="admin_subscription_add")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request)
    {
        $subscription = new Subscription();
        $form = $this->createForm(CreateType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->subscriptionService->create($subscription);
                $this->addFlash('success', 'Sėkmingai sukurta');

                return $this->redirectToRoute('admin_subscription_list');
            } catch (Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('admin/subscription/add.html.twig', [
            'form' => $form->createView(),
            'subscription' => $subscription
        ]);
    }

    /**
     * @Route("/edit/{subscription}", name="admin_subscription_edit")
     * @param Subscription $subscription
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function edit(Subscription $subscription, Request $request)
    {
        $form = $this->createForm(UpdateType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->subscriptionService->update($subscription);
                $this->addFlash('success', 'Sėkmingai atnaujinta');

                return $this->redirectToRoute('admin_subscription_list');
            } catch (Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('admin/subscription/edit.html.twig', [
            'form' => $form->createView(),
            'subscription' => $subscription
        ]);
    }

    /**
     * @Route("/list", name="admin_subscription_list")
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('admin/subscription/list.html.twig', [
            'subscriptions' => $this->subscriptionService->getAll()
        ]);
    }

    /**
     * @Route("/delete/{subscription}", name="admin_subscription_delete")
     * @param Subscription $subscription
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function delete(Subscription $subscription)
    {
        $this->subscriptionService->remove($subscription);

        return $this->redirectToRoute('admin_subscription_list');
    }
}
