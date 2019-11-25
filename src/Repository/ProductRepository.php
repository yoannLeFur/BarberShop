<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\ProductSearch;
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
    public function findLatest(): array
    {
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
    public function findAllVisibleQuery(ProductSearch $search): Query
    {
        $query = $this->createQueryBuilder('p');
        if ($search->getBrand()->count() > 0) {
            $k = 0;
            /** @var Brand $brand */
            foreach ($search->getBrand() as $brand) {
                $k++;
                $alias = $this->randomString(4);
                $query = $query
                    ->join("p.brand", $alias)
                    ->orWhere("$alias.id = :brand$k")
                    ->setParameter("brand$k", $brand);
            }
        };
        if ($search->getCategory()->count() > 0) {
            $k = 0;
            foreach ($search->getCategory() as $category) {
                $k++;
                $alias = $this->randomString(4);
                $query = $query
                    ->join("p.category", $alias)
                    ->orWhere("$alias.id = :category$k")
                    ->setParameter("category$k", $category);
            }
        };
        if ($search->getMaxPrice()) {
            $query = $query
                ->andWhere('p.price < :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }
        dump($query->getQuery());
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

    /**  * Get a random string  *  * @param int $length * @return bool|string */
    function randomString(int $length)
    {
        return substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyz', ceil($length / strlen($x)))), 1, $length);
    }
}
