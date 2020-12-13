<?php

namespace App\Security;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use LogicException;

/**
 * Class MovieShowVoter
 */
class MovieShowVoter extends Voter
{
    const ATTRIBUTE = 'movie_show_voter';

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * MovieShowVoter constructor.
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
        return in_array($attribute, [self::ATTRIBUTE]) && $subject instanceof Movie;
    }

    /**
     * @param string         $attribute
     * @param Movie        $subject
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
            sprintf('%s voter does not protect against %s action.', MovieShowVoter::class, $attribute)
        );
    }

    /**
     * @param Movie $subject
     * @param User    $user
     *
     * @return bool
     */
    private function shouldAllow(Movie $subject, User $user): bool
    {
        if ($subject->isFree() && $user->isClubOrAdmin()) {
            return true;
        }

        foreach ($user->getTickets() as $ticket) {
            if ($ticket->getMovie() &&
                $ticket->getMovie()->getId() === $subject->getId() &&
                $ticket->isPaid() &&
                $ticket->getUser() === $user
            ) {
                return true;
            }
        }

        return false;
    }
}
