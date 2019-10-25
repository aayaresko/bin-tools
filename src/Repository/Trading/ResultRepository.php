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
            ->where('r.user = :userId')
            ->setParameter('userId', $dto->getUser()->getId());

        if ($dto->getDateFrom() instanceof \DateTimeInterface && $dto->getDateTo() instanceof \DateTimeInterface) {
            $builder
                ->andWhere($builder->expr()->between('r.date', ':from', ':to'))
                ->setParameter('from', $dto->getDateFrom()->format('Y-m-d H:i:s'))
                ->setParameter('to', $dto->getDateTo()->format('Y-m-d H:i:s'));

        } else {
            if ($dto->getDateFrom() instanceof \DateTimeInterface) {
                $builder
                    ->andWhere('r.date => :date')
                    ->setParameter('date', $dto->getDateFrom()->format('Y-m-d H:i:s'));
            }

            if ($dto->getDateTo() instanceof \DateTimeInterface) {
                $builder
                    ->andWhere('r.date <= :date')
                    ->setParameter('date', $dto->getDateTo()->format('Y-m-d H:i:s'));
            }
        }

        return $builder->getQuery()->execute();
    }
}
