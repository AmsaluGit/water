<?php

namespace App\Entity;

use App\Repository\ConsumptionDeliveryListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumptionDeliveryListRepository::class)
 */
class ConsumptionDeliveryList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="consumptionDeliveryLists")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=UnitOfMeasure::class, inversedBy="consumptionDeliveryLists")
     */
    private $unitOfMeasure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $unitPrice;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $approvedQuantity;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $approvalStatus;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\ManyToOne(targetEntity=ConsumptionDelivery::class, inversedBy="consumptionDeliveryLists")
     */
    private $consumptionDelivery;

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

    public function getCodeNumber(): ?string
    {
        return $this->codeNumber;
    }

    public function setCodeNumber(string $codeNumber): self
    {
        $this->codeNumber = $codeNumber;

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


    public function setApprovedQuantity(int $approvedQuantity): self
    {
        $this->approvedQuantity = $approvedQuantity;

        return $this;
    }
    public function getApprovedQuantity(): ?int
    {
        return $this->approvedQuantity;
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

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getConsumptionDelivery(): ?ConsumptionDelivery
    {
        return $this->consumptionDelivery;
    }

    public function setConsumptionDelivery(?ConsumptionDelivery $consumptionDelivery): self
    {
        $this->consumptionDelivery = $consumptionDelivery;

        return $this;
    }

    public function getApprovalStatus(): ?int
    {
        return $this->approvalStatus;
    }

    public function setApprovalStatus(int $approvalStatus): self
    {
        $this->approvalStatus = $approvalStatus;

        return $this;
    }
}
