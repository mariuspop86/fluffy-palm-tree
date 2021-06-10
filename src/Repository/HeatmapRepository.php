<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Heatmap;
use App\Entity\LinkType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Heatmap|null find($id, $lockMode = null, $lockVersion = null)
 * @method Heatmap|null findOneBy(array $criteria, array $orderBy = null)
 * @method Heatmap[]    findAll()
 * @method Heatmap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeatmapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Heatmap::class);
    }

    /**
     * @return Heatmap[] Returns an array of Heatmap objects
     * @throws \Exception
     */
    public function findLinkHitsByRange(\DateTime $from, \DateTime $to)
    {
        $from = new \DateTime($from->format("Y-m-d")." 00:00:00");
        $to   = new \DateTime($to->format("Y-m-d")." 23:59:59");
        
        return $this->createQueryBuilder('h')
            ->select('count(h.id) as hits, h.link')
            ->andWhere('h.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('h.link')
            ->getQuery()
            ->getResult();    
    }

    /**
     * @return Heatmap[] Returns an array of Heatmap objects
     */
    public function findLinkTypeHitsByRange(\DateTime $from, \DateTime $to): array
    {
        $from = new \DateTime($from->format("Y-m-d")." 00:00:00");
        $to   = new \DateTime($to->format("Y-m-d")." 23:59:59");

        return $this->createQueryBuilder('h')
            ->select('count(h.id) as hits, lt.id, lt.name')
            ->innerJoin(LinkType::class, 'lt', 'WITH' , 'h.linkType=lt')
            ->andWhere('h.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('h.linkType')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Heatmap[] Returns an array of Heatmap objects
     */
    public function findHeatmapByCustomer($customer_id): array
    {
        return $this->createQueryBuilder('h')
            ->select('h.link, h.createdAt')
            ->innerJoin(Customer::class, 'c', 'WITH', 'h.customer=c')
            ->andWhere('c.id=:customer_id')
            ->setParameter('customer_id', $customer_id)
            ->orderBy('h.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
