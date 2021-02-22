<?php

namespace App\Entity;

use App\Repository\SellsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SellsRepository::class)
 */
class Sells
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeOfDelivery;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paymentVoucherNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plateNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $trailNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $driver;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="sells")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $specification;

    /**
     * @ORM\Column(type="integer", nullable=true)
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
     * @ORM\Column(type="text")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sells")
     */
    private $receivedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sells")
     */
    private $deliveredBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sells")
     */
    private $approvedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaceOfDelivery(): ?string
    {
        return $this->placeOfDelivery;
    }

    public function setPlaceOfDelivery(?string $placeOfDelivery): self
    {
        $this->placeOfDelivery = $placeOfDelivery;

        return $this;
    }

    public function getPaymentVoucherNumber(): ?string
    {
        return $this->paymentVoucherNumber;
    }

    public function setPaymentVoucherNumber(string $paymentVoucherNumber): self
    {
        $this->paymentVoucherNumber = $paymentVoucherNumber;

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

    public function getTrailNumber(): ?string
    {
        return $this->trailNumber;
    }

    public function setTrailNumber(string $trailNumber): self
    {
        $this->trailNumber = $trailNumber;

        return $this;
    }

    public function getDriver(): ?string
    {
        return $this->driver;
    }

    public function setDriver(string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function setSpecification(string $specification): self
    {
        $this->specification = $specification;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

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
