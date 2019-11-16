<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductSearch;
use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findLatest(): array {
        return $this->createQueryBuilder('p')
            ->setMaxResults(4)
            ->orderBy("p.creation_date", 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param ProductSearch $search
     * @return Query
     */
    public function findAllVisibleQuery(ProductSearch $search): Query {
        $query = $this->createQueryBuilder('p');
        if($search->getMaxPrice()) {
            $query = $query
                ->andWhere('p.price < :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }
        if($search->getCategory()->count() > 0) {
            $k = 0;
            foreach ($search->getCategory() as $brand) {
                $k++;
                $query = $query
                    ->andWhere("p.category MEMBER OF :category$k")
                    ->setParameter("category$k", $brand);
            }
        };
        if($search->getBrand()->count() > 0) {
            $k = 0;
            foreach ($search->getBrand() as $brand) {
                $k++;
                $query = $query
                    ->andWhere("p.brand MEMBER OF :brand$k")
                    ->setParameter("brand$k", $brand);
            }
        };
        return $query->getQuery();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
