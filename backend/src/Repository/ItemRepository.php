<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @return Item[] Returns an array of Item objects sorted by dateTime
     */
    public function findAllSortedByDateTime(): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.dateTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(Item $item, bool $flush = true): void
    {
        $this->getEntityManager()->persist($item);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
