<?php

namespace App\Service;


use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;

class TagService
{
    /** @var TagRepository */
    private $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function filterDuplicated($tags): ArrayCollection
    {
        $existing = $this->repository->findAll();
        $data = new ArrayCollection();

        foreach ($tags as $newTag) {
            /** @var Tag $tag */
            foreach ($existing as $existingTag) {
                /** @var Tag $newTag */
                if ($existingTag->getValue() === $newTag->getValue()) {
                    $data->add($existingTag);

                    continue 2;
                }
            }

            $data->add($newTag);
        }

        return $data;
    }
}