<?php

namespace App\Security;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MicroPostVoter extends Voter
{
    // the actions to vote on
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $manager)
    {
        $this->decisionManager = $manager;
    }

    // Determines if this voter event applies to the action and the object that is passed
    // We only want to check if a user has permission to EDIT or DELETE a MicroPost...
    // for other things we would create another voter class
    // this will always be called before voteOnAttribute so if it returns FALSE then the second
    // method is not even called
    protected function supports($attribute, $subject): bool
    {
        if(!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if(!$subject instanceof MicroPost) {
            return false;
        }

        return true;
    }

    // this method checks the actual permissions
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        // If it's an admin we don't even need any other checks...
        if($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        $authenticatedUser = $token->getUser();

        if(!$authenticatedUser instanceof User) {
            return false;
        }

        /**
         * @var MicroPost $microPost
         */
        $microPost = $subject;

        // now let's check if the current user is the same user as the user of the micropost
        // (we know that from the relation between them)
        return $microPost->getUser()->getId() === $authenticatedUser->getId();
    }
}