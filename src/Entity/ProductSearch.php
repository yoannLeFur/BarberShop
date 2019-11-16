<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


class ProductSearch {

    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var ArrayCollection
     */
    private $category;

    /**
     * @var ArrayCollection
     */
    private $brand;

    public function __construct()
    {
        $this->brand = new ArrayCollection();
        $this->category = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getCategory(): ArrayCollection
    {
        return $this->category;
    }

    /**
     * @param ArrayCollection $category
     * @return ProductSearch
     */
    public function setCategory(ArrayCollection $category): ProductSearch
    {
        $this->category = $category;
        return $this;
    }


}
