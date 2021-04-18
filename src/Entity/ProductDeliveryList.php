<?php

namespace App\Entity;

use App\Repository\ProductDeliveryListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductDeliveryListRepository::class)
 */
class ProductDeliveryList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productDeliveryLists")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $specification;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\Column(type="float")
     */
    private $unitPrice;

    /**
     * @ORM\ManyToOne(targetEntity=ProductDelivery::class, inversedBy="productDeliveryLists")
     */
    private $productDelivery;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ApprovedQuantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Remark;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $ApprovalStatus;

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

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(string $specification): self
    {
        $this->specification = $specification;

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

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getProductDelivery(): ?ProductDelivery
    {
        return $this->productDelivery;
    }

    public function setProductDelivery(?ProductDelivery $productDelivery): self
    {
        $this->productDelivery = $productDelivery;

        return $this;
    }
    public function __toString()
    {
        return $this->typeOfDocAndNum;
        // return $this->getProductDelivery()->getTypeOfDocAndNum();
    }

    public function getApprovedQuantity(): ?int
    {
        return $this->ApprovedQuantity;
    }

    public function setApprovedQuantity(?int $ApprovedQuantity): self
    {
        $this->ApprovedQuantity = $ApprovedQuantity;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->Remark;
    }

    public function setRemark(?string $Remark): self
    {
        $this->Remark = $Remark;

        return $this;
    }

    public function getApprovalStatus(): ?int
    {
        return $this->ApprovalStatus;
    }

    public function setApprovalStatus(?int $ApprovalStatus): self
    {
        $this->ApprovalStatus = $ApprovalStatus;

        return $this;
    }
}
