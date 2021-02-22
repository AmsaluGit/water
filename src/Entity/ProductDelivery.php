<?php

namespace App\Entity;

use App\Repository\ProductDeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=ProductDeliveryList::class, mappedBy="productDelivery")
     */
    private $productDeliveryLists;

    public function __construct()
    {
        $this->productDeliveryLists = new ArrayCollection();
    }

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

    /**
     * @return Collection|ProductDeliveryList[]
     */
    public function getProductDeliveryLists(): Collection
    {
        return $this->productDeliveryLists;
    }

    public function addProductDeliveryList(ProductDeliveryList $productDeliveryList): self
    {
        if (!$this->productDeliveryLists->contains($productDeliveryList)) {
            $this->productDeliveryLists[] = $productDeliveryList;
            $productDeliveryList->setProductDelivery($this);
        }

        return $this;
    }

    public function removeProductDeliveryList(ProductDeliveryList $productDeliveryList): self
    {
        if ($this->productDeliveryLists->removeElement($productDeliveryList)) {
            // set the owning side to null (unless already changed)
            if ($productDeliveryList->getProductDelivery() === $this) {
                $productDeliveryList->setProductDelivery(null);
            }
        }

        return $this;
    }
}
