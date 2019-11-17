<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PaymentMethod", cascade={"persist", "remove"})
     */
    private $payment_method;

    /**
     * @ORM\Column(type="float")
     */
    private $total_price;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ProductsOrder", mappedBy="orders", cascade={"persist", "remove"})
     */
    private $productsOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="orders")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->reference);
    }


    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFormattedDate()
    {
        return date_format($this->date, 'd/m/Y H:i:s');
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(?PaymentMethod $payment_method): self
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getFormattefTotalPrice()
    {
        return number_format($this->total_price, 2,',',' ');
    }

    public function getProductsOrder(): ?ProductsOrder
    {
        return $this->productsOrder;
    }

    public function setProductsOrder(?ProductsOrder $productsOrder): self
    {
        $this->productsOrder = $productsOrder;

        // set (or unset) the owning side of the relation if necessary
        $newOrders = $productsOrder === null ? null : $this;
        if ($newOrders !== $productsOrder->getOrders()) {
            $productsOrder->setOrders($newOrders);
        }

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }
}
