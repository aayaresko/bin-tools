<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trading\Result;
use App\Helper\HasNameTrait;
use App\Helper\HasValueTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    use HasNameTrait, HasValueTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $value = '';

    /**
     * @var mixed
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Trading\Result", mappedBy="tags")
     */
    private $results;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     * @return Tag
     */
    public function setResults($results): self
    {
        $this->results = $results;

        return $this;
    }

    /**
     * @param Result $result
     * @return Tag
     */
    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
        }

        return $this;
    }

    /**
     * @param Result $result
     * @return Tag
     */
    public function removeResult(Result $result): self
    {
        $this->results->removeElement($result);

        return $this;
    }
}
