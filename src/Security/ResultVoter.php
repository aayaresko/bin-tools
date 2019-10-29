<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\Song;
use App\Entity\Trading\Result;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ResultVoter extends BaseVoter
{
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Result) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $success = parent::voteOnAttribute($attribute, $subject, $token);

        if (null !== $success) {
            return $success;
        }

        /** @var User $user */
        $user = $token->getUser();

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        throw new \LogicException('Something went wrong during PlayList voting.');
    }

    /**
     * @param Result $entity
     * @param User $user
     * @return bool
     */
    private function canDelete(Result $entity, User $user): bool
    {
        return $this->canEdit($entity, $user);
    }

    /**
     * @param Result $entity
     * @param User $user
     * @return bool
     */
    private function canView(Result $entity, User $user): bool
    {
        return $this->canEdit($entity, $user);
    }

    /**
     * @param Result $entity
     * @param User $user
     * @return bool
     */
    private function canEdit(Result $entity, User $user): bool
    {
        return $user === $entity->getUser();
    }
}