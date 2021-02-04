<?php

namespace App\Security;

use App\Entity\Subscription;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use LogicException;

/**
 * Class SubscriptionViewVoter
 */
class SubscriptionViewVoter extends Voter
{
    const ATTRIBUTE = 'subscription_view_voter';

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * SubscriptionViewVoter constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param string $attribute
     * @param User   $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [self::ATTRIBUTE]) && $subject instanceof Subscription;
    }

    /**
     * @param string         $attribute
     * @param Subscription        $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::ATTRIBUTE:
                return $this->shouldAllow($subject, $user);
        }

        throw new LogicException(
            sprintf('%s voter does not protect against %s action.', SubscriptionViewVoter::class, $attribute)
        );
    }

    /**
     * @param Subscription $subject
     * @param User    $user
     *
     * @return bool
     */
    private function shouldAllow(Subscription $subject, User $user): bool
    {
        return $user->isAdmin() || ($user->getId() === $subject->getUser()->getId());
    }
}
