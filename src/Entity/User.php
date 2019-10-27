<?php

namespace App\Entity;

use App\Entity\Trading\Result;
use App\Helper\HasCreatedAtTrait;
use App\Helper\HasMediaFileTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    use HasCreatedAtTrait, HasMediaFileTrait;

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
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=150, nullable=true)
     */
    private $photo;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="user_media", fileNameProperty="photo")
     */
    private $mediaFile;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default" : 1})
     */
    private $visible = true;

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
     * @return null|string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto(string $photo = null): User
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return User
     */
    public function setIsVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @param mixed $results
     * @return User
     */
    public function setResults($results): self
    {
        $this->results = $results;

        return $this;
    }

    /**
     * @param Result $result
     * @return User
     */
    public function addResult(Result $result): self
    {
        if (false === $this->results->contains($result)) {
            $this->results->add($result);
            $result->setUser($this);
        }

        return $this;
    }
}
