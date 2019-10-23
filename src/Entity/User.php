<?php

namespace App\Entity;

use App\Entity\Trading\Result;
use App\Helper\HasCreatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
    use HasCreatedAtTrait;

    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trading\Result", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $results;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->results = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $results
     * @return User
     */
    public function setResults($results): User
    {
        $this->results = $results;

        return $this;
    }

    /**
     * @param Result $result
     * @return User
     */
    public function addSong(Result $result): User
    {
        if (false === $this->results->contains($result)) {
            $this->results->add($result);
            $result->setUser($this);
        }

        return $this;
    }
}
