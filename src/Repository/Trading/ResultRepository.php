<?php

namespace App\Repository\Trading;

use App\Dto\Trading\ProfitabilityDto;
use App\Dto\Trading\ResultsFilterDto;
use App\Entity\Trading\Result;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

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

    public function attachResultsFilterCriteria(QueryBuilder $builder, ResultsFilterDto $dto): QueryBuilder
    {
        if ($dto->getUser()) {
            $builder
                ->andWhere('r.user = :userId')
                ->setParameter('userId', $dto->getUser()->getId());
        };

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

        return $builder->orderBy('r.createdAt', 'ASC');
    }

    public function getByUserQueryBuilder(User $user): QueryBuilder
    {
        $builder = $this->createQueryBuilder('r');

        $builder
            ->andWhere('r.user = :userId')
            ->setParameter('userId', $user->getId());

        return $builder;
    }

    public function getFilterByDtoQueryBuilder(ResultsFilterDto $dto): QueryBuilder
    {
        $builder = $this->createQueryBuilder('r');

        $this->attachResultsFilterCriteria($builder, $dto);

        return $builder;
    }

    public function getFilterByTagQueryBuilder(string $tag): QueryBuilder
    {
        $builder = $this->createQueryBuilder('r');

        $builder->leftJoin('r.tags', 't');

        $builder
            ->andWhere(
                $builder->expr()->like('t.value',"'%{$tag}%'")
            );

        return $builder;
    }

    public function calculateUserProfitabilityFromDto(ResultsFilterDto $dto): ProfitabilityDto
    {
        $data = new ProfitabilityDto();

        if ($dto->tagValue) {
            $builder = $this->getFilterByTagQueryBuilder($dto->tagValue);
        } else {
            $builder = $this->createQueryBuilder('r');
        }

        $builder->select('count(r.id)');
        $this->attachResultsFilterCriteria($builder, $dto);
        $builder->andWhere('r.spent >= r.profit');

        try {
            $unprofitable = $builder->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {
            $unprofitable = 0;
        }

        if ($dto->tagValue) {
            $builder = $this->getFilterByTagQueryBuilder($dto->tagValue);
        } else {
            $builder = $this->createQueryBuilder('r');
        }

        $builder->select('count(r.id)');
        $this->attachResultsFilterCriteria($builder, $dto);
        $builder->andWhere('r.spent < r.profit');

        try {
            $profitable = $builder->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {
            $profitable = 0;
        }

        $data->totalBetsPerMonth = $unprofitable + $profitable;

        if (0 === $profitable || 0 === $data->totalBetsPerMonth) {
            $data->profitableBetsPercentage = 0;

            return $data;
        }

        $data->profitableBetsPercentage = round(($profitable * 100) / $data->totalBetsPerMonth, 2);

        return $data;
    }
}
