<?php

namespace App\Entity\Trading;

use App\Entity\Tag;
use App\Entity\User;
use App\Helper\HasCreatedAtTrait;
use App\Helper\HasMediaFileTrait;
use App\Helper\HasUserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Trading\ResultRepository")
 * @Vich\Uploadable
 */
class Result
{
    const DIVISOR = 100000;

    use HasUserTrait, HasCreatedAtTrait, HasMediaFileTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = 0;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $openingQuote;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $closingQuote;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $spent = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $profit = 0.00;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="results")
     */
    private $user;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="result_media", fileNameProperty="image")
     */
    private $mediaFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var mixed
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="results", cascade={"persist"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpeningQuote(): ?float
    {
        return $this->openingQuote / self::DIVISOR;
    }

    public function setOpeningQuote(float $openingQuote): self
    {
        $this->openingQuote = self::DIVISOR * $openingQuote;

        return $this;
    }

    public function getClosingQuote(): ?float
    {
        return $this->closingQuote / self::DIVISOR;
    }

    public function setClosingQuote(float $closingQuote): self
    {
        $this->closingQuote = self::DIVISOR * $closingQuote;

        return $this;
    }

    public function getSpent(): int
    {
        return $this->spent;
    }

    public function setSpent(int $spent): self
    {
        $this->spent = $spent;

        return $this;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): self
    {
        $this->profit = $profit;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     * @return Result
     */
    public function setTags($tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @param Tag $tag
     * @return Result
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addResult($this);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     * @return Result
     */
    public function removeTag(Tag $tag): self
    {
        $tag->removeResult($this);
        $this->tags->removeElement($tag);

        return $this;
    }
}