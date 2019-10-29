<?php

namespace App\Dto\Trading;

use App\Entity\User;

class ResultsFilterDto
{
    private $dateFrom;

    private $dateTo;

    private $user;

    public $tagValue;

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTimeInterface $dateFrom
     * @return ResultsFilterDto
     */
    public function setDateFrom(\DateTimeInterface $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->dateTo;
    }

    /**
     * @param \DateTimeInterface $dateTo
     * @return ResultsFilterDto
     */
    public function setDateTo(\DateTimeInterface $dateTo): self
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return ResultsFilterDto
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}