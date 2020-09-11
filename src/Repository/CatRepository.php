<?php

namespace App\Repository;

use App\Entity\Cat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cat[]    findAll()
 * @method Cat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cat::class);
    }


    public function search(string $keyword): ?array
    {
        //querybuilder pour faire une requête perso
        //recherche les chatons dont le nom commence par le $keyword
        return $this->createQueryBuilder('c') //alias
            ->andWhere('c.name LIKE :kw') //LIKE ici pour que le % fonctionne, et on utilise bien un paramètre nommé pour la sécurité
            ->setParameter('kw', $keyword . '%') //remplace le paramètre, tout en ajoutant un % (joker)
            ->getQuery()
            ->getResult()
        ;
    }

}
