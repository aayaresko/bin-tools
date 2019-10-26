<?php declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;

trait HasMediaFileTrait
{
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var File
     *
     */
    private $mediaFile;

    /**
     * @var string
     */
    private $mediaWebPath;

    /**
     * @var string
     */
    private $mediaIconWebPath;

    /**
     * @var string
     */
    private $mediaThumbnailWebPath;

    /**
     * @param File|null $media
     *
     * @return $this
     */
    public function setMediaFile(File $media = null)
    {
        $this->mediaFile = $media;
        if ($media) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getMediaFile(): ?File
    {
        return $this->mediaFile;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMediaWebPath(string $value)
    {
        $this->mediaWebPath = $value;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMediaWebPath(): ?string
    {
        return $this->mediaWebPath;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMediaIconWebPath(string $value)
    {
        $this->mediaIconWebPath = $value;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMediaIconWebPath(): ?string
    {
        return $this->mediaIconWebPath;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMediaThumbnailWebPath(string $value)
    {
        $this->mediaThumbnailWebPath = $value;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMediaThumbnailWebPath(): ?string
    {
        return $this->mediaThumbnailWebPath;
    }
}
