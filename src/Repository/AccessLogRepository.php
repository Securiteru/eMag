<?php

namespace App\Repository;

use App\Entity\AccessLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccessLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessLog[]    findAll()
 * @method AccessLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessLog::class);
    }

    /**
     * Generates a query of all entries order descending based on id and returns the results
     *
     * @return int|mixed|string
     */
    public function findAllDesc()
    {
        return $this->createQueryBuilder('accesslog')
            ->orderBy('accesslog.timestamp', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Generates a query of all entries order descending based on timestamps while matching the id and time criteria
     * and returns the results
     *
     * @param $id
     * @param $startTime
     * @param $endTime
     * @return int|mixed|string
     */
    public function findByLinkIdWithTimeStamps($id, $startTime, $endTime)
    {
        return $this->createQueryBuilder('accesslog')
            ->orderBy('accesslog.timestamp', 'DESC')
            ->andWhere('accesslog.url = :id')
            ->andWhere('accesslog.timestamp >= :startTime')
            ->andWhere('accesslog.timestamp <= :endTime')
            ->setParameters(
                new ArrayCollection(
                    array(
                        new Parameter('id', $id),
                        new Parameter('startTime', $startTime),
                        new Parameter('endTime', $endTime),
                    )
                )
            )
            ->getQuery()
            ->getResult();
    }

    /**
     * Generates a query of all entries order descending based on timestamps while matching the id and time criteria
     * and returns a count of the results
     *
     * @param $id
     * @param $startTime
     * @param $endTime
     * @return int|mixed|string
     */
    public function findByLinkIdWithTimeStampsCount($id, $startTime, $endTime)
    {
        return $this->createQueryBuilder('accesslog')
            ->leftJoin('accesslog.url', "url")
            ->select("url.id", "url.link", "url.link_type", 'count(accesslog.id) as count')
            ->andWhere('accesslog.url = :id')
            ->andWhere('accesslog.timestamp >= :startTime')
            ->andWhere('accesslog.timestamp <= :endTime')
            ->setParameters(
                new ArrayCollection(
                    array(
                        new Parameter('id', $id),
                        new Parameter('startTime', $startTime),
                        new Parameter('endTime', $endTime),
                    )
                )
            )
            ->groupBy('url.id')
            ->getQuery()
            ->getResult();

    }

    /**
     * Generates a query of all entries order descending based on timestamp based on the url type required and returns
     * the results
     *
     * @param $url_type
     * @return int|mixed|string
     */
    public function findByLinkType($url_type,$startTime, $endTime)
    {
        return $this->createQueryBuilder('accesslog')
            ->orderBy('accesslog.timestamp', 'DESC')
            ->leftJoin('accesslog.url', "url")
            ->andWhere('accesslog.timestamp >= :startTime')
            ->andWhere('url.link_type = :url_type')
            ->andWhere('accesslog.timestamp <= :endTime')
            ->setParameters(
                new ArrayCollection(
                    array(
                        new Parameter('url_type', $url_type),
                        new Parameter('startTime', $startTime),
                        new Parameter('endTime', $endTime),
                    )
                )
            )
            ->getQuery()
            ->getResult();
    }

    /**
     * Generates a query of all entries order descending based on timestamp based on the url type required and returns
     * a count of the results
     *
     * @return int|mixed|string
     */
    public function findByLinkTypeCount($startTime,$endTime)
    {
        return $this->createQueryBuilder('accesslog')
            ->leftJoin('accesslog.url', "url")
            ->groupBy('url.link_type')
            ->select('count(accesslog.id) as count, url.link_type')
            ->andWhere('accesslog.timestamp >= :startTime')
            ->andWhere('accesslog.timestamp <= :endTime')
            ->setParameters(
                new ArrayCollection(
                    array(
                        new Parameter('startTime', $startTime),
                        new Parameter('endTime', $endTime),
                    )
                )
            )->getQuery()
            ->getResult();
    }

    /**
     * Generates a query of all entries that match the id criteria and returns the results
     *
     * @param $customer_id
     * @return int|mixed|string
     */
    public function findByCustomerJourney($customer_id)
    {
        return $this->createQueryBuilder('accesslog')
            ->where('accesslog.customer = :customer_id')
            ->setParameter('customer_id', $customer_id)
            ->getQuery()
            ->getResult();
    }
}
