<?php

namespace App\Repository;

use App\Entity\PaymentAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentAddress[]    findAll()
 * @method PaymentAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentAddress::class);
    }
}
