<?php

namespace App\Entity;

use App\Repository\ConsumptionDeliveryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumptionDeliveryRepository::class)
 */
class ConsumptionDelivery
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consumptionDeliveries")
     */
    private $receiver;

    /**
     * @ORM\ManyToOne(targetEntity=ConsumptionRequest::class, inversedBy="consumptionDeliveries")
     */
    private $requestNo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="consumptionDeliveries")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=UnitOfMeasure::class, inversedBy="consumptionDeliveries")
     */
    private $unitOfMeasure;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $unitPrice;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $deliveredBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $approvedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getRequestNo(): ?ConsumptionRequest
    {
        return $this->requestNo;
    }

    public function setRequestNo(?ConsumptionRequest $requestNo): self
    {
        $this->requestNo = $requestNo;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getDeliveredBy(): ?User
    {
        return $this->deliveredBy;
    }

    public function setDeliveredBy(?User $deliveredBy): self
    {
        $this->deliveredBy = $deliveredBy;

        return $this;
    }

    public function getApprovedBy(): ?User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?User $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }
}