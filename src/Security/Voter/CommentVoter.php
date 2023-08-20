<?php

namespace App\Security\Voter;

use App\Entity\CommentInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    public const DELETE = 'delete';
    public const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::DELETE, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof CommentInterface) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var CommentInterface $comment */
        $comment = $subject;

        return match($attribute) {
            self::DELETE => $this->canDelete($comment, $user),
            self::EDIT => $this->canEdit($comment, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canDelete(CommentInterface $comment, User $user): bool
    {
        // if they can edit, they can view
        return $this->canEdit($comment, $user);
    }

    private function canEdit(CommentInterface $comment, User $user): bool
    {
        return $user->getId() === $comment->getAuthor()->getId();
    }
}