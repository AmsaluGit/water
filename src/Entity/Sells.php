<?php

namespace App\Entity;

use App\Repository\SellsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="text", nullable = true)
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

    /**
     * @ORM\OneToMany(targetEntity=SellsList::class, mappedBy="sells")
     */
    private $sellsLists;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $ApprovalStatus;

    public function __construct()
    {
        $this->sellsLists = new ArrayCollection();
    }

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

    /**
     * @return Collection|SellsList[]
     */
    public function getSellsLists(): Collection
    {
        return $this->sellsLists;
    }

    public function addSellsList(SellsList $sellsList): self
    {
        if (!$this->sellsLists->contains($sellsList)) {
            $this->sellsLists[] = $sellsList;
            $sellsList->setSells($this);
        }

        return $this;
    }

    public function removeSellsList(SellsList $sellsList): self
    {
        if ($this->sellsLists->removeElement($sellsList)) {
            // set the owning side to null (unless already changed)
            if ($sellsList->getSells() === $this) {
                $sellsList->setSells(null);
            }
        }

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
