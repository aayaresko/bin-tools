<?php declare(strict_types=1);

namespace App\Service;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Binary\FileBinaryInterface;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

class ImageProcessor
{
    const RESULT_IMAGE_NORMAL    = 'normal';
    const RESULT_IMAGE_ICON      = 'icon';
    const RESULT_IMAGE_THUMBNAIL = 'thumbnail';

    const USER_PHOTO_ICON_WIDEN        = 'user_photo_icon_widen';
    const USER_PHOTO_THUMBNAIL_WIDEN   = 'user_photo_thumbnail_widen';
    const USER_PHOTO_WIDEN             = 'user_photo_widen';
    const RESULT_IMAGE_ICON_WIDEN      = 'result_image_icon_widen';
    const RESULT_IMAGE_THUMBNAIL_WIDEN = 'result_image_thumbnail_widen';
    const RESULT_IMAGE_WIDEN           = 'result_image_widen';

    /**
     * @var DataManager
     */
    private $dataManager;
    /**
     * @var FilterManager
     */
    private $filterManager;
    /**
     * @var string
     */
    private $rootDir;

    /**
     * ImageProcessor constructor.
     * @param DataManager $dataManager
     * @param FilterManager $filterManager
     * @param string $rootDir
     */
    public function __construct(DataManager $dataManager, FilterManager $filterManager, $rootDir)
    {
        $this->dataManager   = $dataManager;
        $this->filterManager = $filterManager;
        $this->rootDir       = $rootDir;
    }

    /**
     * @param string $path
     * @param string $content
     * @return resource
     */
    private function saveImage(string $path, string $content)
    {
        $image = fopen($path, 'w');

        fwrite($image, $content);
        fclose($image);

        return $image;
    }

    /**
     * @param string $imagePath
     * @param string $filter
     * @param bool $shouldStore
     *
     * @return BinaryInterface
     */
    public function filter(string $imagePath, string $filter = self::RESULT_IMAGE_WIDEN, bool $shouldStore = false): BinaryInterface
    {
        /** @var FileBinaryInterface $binary */
        $binary = $this->dataManager->find($filter, $imagePath);

        /** @var BinaryInterface $data */
        $data = $this->filterManager->applyFilter($binary, $filter);

        if (true === $shouldStore) {
            $this->saveImage($binary->getPath(), $data->getContent());
        }

        return $data;
    }
}
