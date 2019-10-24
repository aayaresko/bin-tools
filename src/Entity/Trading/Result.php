<?php

namespace App\Entity\Trading;

use App\Entity\User;
use App\Helper\HasCreatedAtTrait;
use App\Helper\HasMediaFileTrait;
use App\Helper\HasUserTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Trading\ResultRepository")
 */
class Result
{
    use HasUserTrait, HasCreatedAtTrait, HasMediaFileTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = 0;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=6, nullable=false)
     */
    private $openingQuote = 0.00;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=6, nullable=false)
     */
    private $closingQuote = 0.00;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $spent = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=false)
     */
    private $profit = 0.00;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $notes;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="results")
     */
    private $user;

    /**
     * @var mixed
     *
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="result_media", fileNameProperty="file")
     */
    private $mediaFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpeningQuote(): ?float
    {
        return $this->openingQuote;
    }

    public function setOpeningQuote(float $openingQuote): self
    {
        $this->openingQuote = $openingQuote;

        return $this;
    }

    public function getClosingQuote(): ?float
    {
        return $this->closingQuote;
    }

    public function setClosingQuote(float $closingQuote): self
    {
        $this->closingQuote = $closingQuote;

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

    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

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
}