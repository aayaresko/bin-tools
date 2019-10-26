<?php

namespace App\Helper;

use App\Entity\User;

trait HasUserTrait
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(User $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
