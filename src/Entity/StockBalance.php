<?php

namespace App\Entity;

use App\Repository\StockBalanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockBalanceRepository::class)
 */
class StockBalance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="stockBalances")
     */
    private $product;

    // /**
    //  * @ORM\Column(type="integer")
    //  */
    // private $requested;

    // /**
    //  * @ORM\Column(type="integer", nullable=true)
    //  */
    // private $delivered;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $available;

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

    // public function getRequested(): ?int
    // {
    //     return $this->requested;
    // }

    // public function setRequested(int $requested): self
    // {
    //     $this->requested = $requested;

    //     return $this;
    // }

    // public function getDelivered(): ?int
    // {
    //     return $this->delivered;
    // }

    // public function setDelivered(?int $delivered): self
    // {
    //     $this->delivered = $delivered;

    //     return $this;
    // }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(?int $available): self
    {
        $this->available = $available;

        return $this;
    }
}
