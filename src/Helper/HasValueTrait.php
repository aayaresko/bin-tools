<?php declare(strict_types=1);

namespace App\Helper;

use Doctrine\ORM\Mapping as ORM;

trait HasValueTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value = '';

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }
}