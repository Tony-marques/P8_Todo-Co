<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserByUsernameAndRole(string $username, string $role): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('username', $username)
            ->setParameter('roles', '%"' . $role . '"%')
            ->getQuery()
            ->getResult();
    }
}
