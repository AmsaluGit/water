<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="stocks")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Store::class, inversedBy="stocks")
     */
    private $store;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePurchased;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stocks")
     */
    private $registeredBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getDatePurchased(): ?\DateTimeInterface
    {
        return $this->datePurchased;
    }

    public function setDatePurchased(\DateTimeInterface $datePurchased): self
    {
        $this->datePurchased = $datePurchased;

        return $this;
    }

    public function getRegisteredBy(): ?User
    {
        return $this->registeredBy;
    }

    public function setRegisteredBy(?User $registeredBy): self
    {
        $this->registeredBy = $registeredBy;

        return $this;
    }
}
