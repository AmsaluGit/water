<?php

namespace App\Entity;

use App\Repository\StockRequestListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRequestListRepository::class)
 */
class StockRequestList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="stockRequestLists")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=UnitOfMeasure::class, inversedBy="stockRequestLists")
     */
    private $unitOfMeasure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $spcicification;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=StockRequest::class, inversedBy="stockRequestLists")
     */
    private $stockRequest;

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

    public function getUnitOfMeasure(): ?UnitOfMeasure
    {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(?UnitOfMeasure $unitOfMeasure): self
    {
        $this->unitOfMeasure = $unitOfMeasure;

        return $this;
    }

    public function getSpcicification(): ?string
    {
        return $this->spcicification;
    }

    public function setSpcicification(string $spcicification): self
    {
        $this->spcicification = $spcicification;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStockRequest(): ?StockRequest
    {
        return $this->stockRequest;
    }

    public function setStockRequest(?StockRequest $stockRequest): self
    {
        $this->stockRequest = $stockRequest;

        return $this;
    }
}
