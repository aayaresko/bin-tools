<?php

namespace App\Repository\Trading;

use App\Dto\Trading\ResultsFilterDto;
use App\Entity\Trading\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    public function filterByDto(ResultsFilterDto $dto)
    {
        $builder = $this->createQueryBuilder('r');

        $builder
            ->andWhere('r.user.id = userId')
            ->setParameter('userId', $dto->userId);

        if ($dto->dateFrom && $dto->dateTo) {
            $builder
                ->andWhere(
                    $builder->expr()->between('r.date', $dto->dateFrom, $dto->dateTo)
                );
        } else {
            if ($dto->dateFrom) {
                $builder
                    ->andWhere('r.date => date')
                    ->setParameter('date', $dto->dateFrom);
            }

            if ($dto->dateTo) {
                $builder
                    ->andWhere('r.date <= date')
                    ->setParameter('date', $dto->dateFrom);
            }
        }

        return $builder->getQuery()->execute();
    }
}
