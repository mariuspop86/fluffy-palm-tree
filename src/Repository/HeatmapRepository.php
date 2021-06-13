<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Heatmap;
use App\Entity\LinkType;
use DateTime;
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
    public function findLinkHitsByRange(DateTime $from, DateTime $to): array
    {
        $from = new DateTime($from->format("Y-m-d")." 00:00:00");
        $to   = new DateTime($to->format("Y-m-d")." 23:59:59");
        
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
    public function findLinkTypeHitsByRange(DateTime $from, DateTime $to): array
    {
        $from = new DateTime($from->format("Y-m-d")." 00:00:00");
        $to   = new DateTime($to->format("Y-m-d")." 23:59:59");

        return $this->createQueryBuilder('h')
            ->select('count(h.id) as hits, lt.name')
            ->innerJoin(LinkType::class, 'lt', 'WITH' , 'h.linkType=lt')
            ->andWhere('h.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('h.linkType')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Heatmap[] Returns an array of links
     */
    public function getJourneyByCustomer(int $customer_id): array
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
    
    /**
     * @return array Returns an array of links
     */
    public function getSimilarJourneyUniqueCustomers(int $customer_id, array $links): array
    {
        return $this->createQueryBuilder('h')
            ->select('c.id, c.name')
            ->innerJoin(Customer::class, 'c', 'WITH', 'h.customer=c')
            ->andWhere('c.id <> :customer_id')
            ->andWhere('h.link in (:links)')
            ->setParameter('customer_id', $customer_id)
            ->setParameter('links', implode(',', $links))
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }
}
