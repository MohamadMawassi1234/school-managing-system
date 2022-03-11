<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class StudentVoter extends Voter
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT', 'SHOW'])
            && $subject instanceof \App\Entity\Student;
    }

    protected function voteOnAttribute(string $attribute, $student, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                return $student == $user->getStudent() || $this->security->isGranted('ROLE_ADMIN');
                break;
            case 'SHOW':
                return $student == $user->getStudent() || $this->security->isGranted('ROLE_COORDINATOR');
                break;
        }

        return false;
    }
}
