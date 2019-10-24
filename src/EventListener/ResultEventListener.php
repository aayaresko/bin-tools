<?php declare(strict_types = 1);

namespace App\EventListener;

use App\Entity\Trading\Result;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ResultEventListener
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * UserEventListener constructor.
     *
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param Result $entity
     * @param LifecycleEventArgs $args
     */
    public function postLoad(Result $entity, LifecycleEventArgs $args): void
    {
        if (!$entity->getImage()) {
            return;
        }

        $image = $entity->getImage();

        $entity
            ->setMediaWebPath($this->cacheManager->getBrowserPath($image, 'result_image_widen'))
            ->setMediaIconWebPath(
                $this->cacheManager->getBrowserPath($image, 'result_image_icon_widen')
            )
            ->setMediaThumbnailWebPath(
                $this->cacheManager->getBrowserPath($image, 'result_image_thumbnail_widen')
            );
    }
}
