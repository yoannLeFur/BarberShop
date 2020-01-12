<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;


class ProductSearch {

    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var
     */
    private $category;

    /**
     * @var ArrayCollection
     */
    private $brand;

    public function __construct()
    {
        $this->brand = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     */
    public function setMaxPrice(int $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * @return ArrayCollection
     */
    public function getBrand(): ArrayCollection
    {
        return $this->brand;
    }

    /**
     * @param ArrayCollection $brand
     */
    public function setBrand(ArrayCollection $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return ProductCategory
     */
    public function getCategory():?ProductCategory
    {
        return $this->category;
    }

    /**
     * @param $category
     * @return ProductSearch
     */
    public function setCategory(ProductCategory $productCategory): ProductSearch
    {
        $this->category = $productCategory;
        return $this;
    }


}
