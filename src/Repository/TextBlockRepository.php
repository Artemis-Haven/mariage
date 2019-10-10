<?php

namespace App\Repository;

use App\Entity\TextBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TextBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextBlock[]    findAll()
 * @method TextBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextBlockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TextBlock::class);
    }

    public function findAllByName()
    {
        $result = $this->createQueryBuilder('t')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        //return $result;
        return array_combine(
                array_map(function($item) { return $item->getName(); }, $result),
                array_map(function($item) { return $item->getContent(); }, $result)
        );
    }
}
