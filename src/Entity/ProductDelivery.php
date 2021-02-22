<?php

namespace App\Entity;

use App\Repository\ProductDeliveryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductDeliveryRepository::class)
 */
class ProductDelivery
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productDeliveries")
     */
    private $handOveredBy;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeOfDocAndNum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plateNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trialNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productDeliveries")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\Column(type="simple_array")
     */
    private $phone = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productDeliveries")
     */
    private $receivedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productDeliveries")
     */
    private $deliveredBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productDeliveries")
     */
    private $approvedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHandOveredBy(): ?User
    {
        return $this->handOveredBy;
    }

    public function setHandOveredBy(?User $handOveredBy): self
    {
        $this->handOveredBy = $handOveredBy;

        return $this;
    }

    public function getTypeOfDocAndNum(): ?string
    {
        return $this->typeOfDocAndNum;
    }

    public function setTypeOfDocAndNum(?string $typeOfDocAndNum): self
    {
        $this->typeOfDocAndNum = $typeOfDocAndNum;

        return $this;
    }

    public function getPlateNumber(): ?string
    {
        return $this->plateNumber;
    }

    public function setPlateNumber(?string $plateNumber): self
    {
        $this->plateNumber = $plateNumber;

        return $this;
    }

    public function getTrialNumber(): ?string
    {
        return $this->trialNumber;
    }

    public function setTrialNumber(?string $trialNumber): self
    {
        $this->trialNumber = $trialNumber;

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

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(?string $specification): self
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

    public function getPhone(): ?array
    {
        return $this->phone;
    }

    public function setPhone(array $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getReceivedBy(): ?User
    {
        return $this->receivedBy;
    }

    public function setReceivedBy(?User $receivedBy): self
    {
        $this->receivedBy = $receivedBy;

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
