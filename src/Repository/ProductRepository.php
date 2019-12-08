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

        // region ========== BRAND ==========

        if ($search->getBrand()->count() > 1) {
            $alias = $this->randomString(4);
            $query = $query->innerJoin("p.brand", $alias);

            $k = 0;
            /** @var Brand $brand */
            foreach ($search->getBrand() as $brand) {

                if ($k === 0) {
                    $query = $query
                        ->where("$alias.id = :brand$k")
                        ->setParameter("brand$k", $brand);
                } elseif ($k > 0) {
                    $query = $query
                        ->orWhere("$alias.id = :brand$k")
                        ->setParameter("brand$k", $brand);
                }

                $k++;
            }
        } elseif ($search->getBrand()->count() === 1) {
            $query = $query
                ->innerJoin("p.brand", "b")
                ->where("b.id = :brand")
                ->setParameter("brand", $search->getBrand()->first());
        }

        //endregion

        // region ========== CATEGORY ==========

        if ($search->getCategory()->count() > 1) {

            $alias = $this->randomString(4);
            $query = $query->innerJoin("p.category", $alias);

            $k = 0;
            foreach ($search->getCategory() as $category) {

                if ($search->getBrand()->count() > 0) {
                    $query = $query
                        ->orWhere("$alias.id = :category$k")
                        ->setParameter("category$k", $category);
                } else {
                    $query = $query
                        ->where("$alias.id = :category$k")
                        ->setParameter("category$k", $category);
                }
                $k++;
            }
        } elseif ($search->getCategory()->count() === 1) {
            $query = $query
                ->innerJoin("p.category", "c")
                ->where("c.id = :cat")
                ->setParameter("cat", $search->getCategory()->first());
        }

        // endregion

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