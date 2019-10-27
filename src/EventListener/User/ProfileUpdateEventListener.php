<?php

namespace App\EventListener\User;

use App\Entity\User;
use App\Service\ImageProcessor;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProfileUpdateEventListener implements EventSubscriberInterface
{
    /**
     * @var ImageProcessor
     */
    private $imageProcessor;

    public function __construct(ImageProcessor $processor)
    {
        $this->imageProcessor = $processor;
    }

    public function onUpdateSuccess(FilterUserResponseEvent $event)
    {
        /** @var User $entity */
        $entity = $event->getUser();

        if (null === $photo = $entity->getPhoto()) {
            return;
        }

        $this->imageProcessor->filter($photo, ImageProcessor::USER_PHOTO_WIDEN, true);
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'onUpdateSuccess'
        ];
    }
}