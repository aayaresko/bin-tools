<?php declare(strict_types=1);

namespace App\Helper;

use Doctrine\ORM\Mapping as ORM;

trait HasNameTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}