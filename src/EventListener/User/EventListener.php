<?php

namespace App\EventListener\User;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class EventListener
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
     * @param User $entity
     * @param LifecycleEventArgs $args
     */
    public function postLoad(User $entity, LifecycleEventArgs $args): void
    {
        if (!$entity->getPhoto()) {
            return;
        }

        $image = $entity->getPhoto();

        $entity
            ->setMediaWebPath($this->cacheManager->getBrowserPath($image, 'user_photo_widen'))
            ->setMediaIconWebPath(
                $this->cacheManager->getBrowserPath($image, 'user_photo_icon_widen')
            )
            ->setMediaThumbnailWebPath(
                $this->cacheManager->getBrowserPath($image, 'user_photo_thumbnail_widen')
            );
    }
}
