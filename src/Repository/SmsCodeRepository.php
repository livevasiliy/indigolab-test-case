<?php

namespace App\Repository;

use App\Entity\SmsCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SmsCode>
 *
 * @method SmsCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmsCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmsCode[]    findAll()
 * @method SmsCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmsCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmsCode::class);
    }

    public function add(SmsCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SmsCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatestByPhoneNumber($phoneNumber): ?SmsCode
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.phone_number = :phoneNumber')
            ->setParameter('phoneNumber', $phoneNumber)
            ->orderBy('s.created_at', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createNewCode(string $phoneNumber, string $newCode): SmsCode
    {
        $smsCode = new SmsCode();
        $smsCode->setPhoneNumber($phoneNumber);
        $smsCode->setCode($newCode);
        $smsCode->setCreatedAt(new \DateTimeImmutable());
        $smsCode->setAttempts(1);

        $this->add($smsCode, true);

        return $smsCode;
    }

    public function updateCode(SmsCode $smsCode, string $newCode): void
    {
        $smsCode->setCode($newCode);
        $smsCode->setLastSentAt(new \DateTimeImmutable());
        $smsCode->incrementAttempts();

        $this->add($smsCode, true);
    }
}
