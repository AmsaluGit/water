<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=Store::class, inversedBy="stocks")
     */
    private $store;

 

   

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePurchased;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stocks")
     */
    private $registeredBy;

    /**
     * @ORM\OneToMany(targetEntity=StockApproval::class, mappedBy="stock", orphanRemoval=true)
     */
    private $stockApprovals;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $approvalStatus;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeOfDelivery;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeOfDocAndNum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $driver;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackPlateNum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trailerNum;

   


    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $receivedBy;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class)
     */
    private $deliveredBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $approvedBy;

    /**
     * @ORM\OneToMany(targetEntity=StockList::class, mappedBy="stock", cascade={"remove"})
     */
    private $stockLists;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    public function __construct()
    {
        $this->stockApprovals = new ArrayCollection();
        $this->stockLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    /**
     * @return Collection|StockApproval[]
     */
    public function getStockApprovals(): Collection
    {
        return $this->stockApprovals;
    }

    public function addStockApproval(StockApproval $stockApproval): self
    {
        if (!$this->stockApprovals->contains($stockApproval)) {
            $this->stockApprovals[] = $stockApproval;
            $stockApproval->setStock($this);
        }

        return $this;
    }

    public function removeStockApproval(StockApproval $stockApproval): self
    {
        if ($this->stockApprovals->removeElement($stockApproval)) {
            // set the owning side to null (unless already changed)
            if ($stockApproval->getStock() === $this) {
                $stockApproval->setStock(null);
            }
        }

        return $this;
    }
    // public function __toString(){
    //     return $this->id;
    // }

    public function getApprovalStatus(): ?int
    {
        return $this->approvalStatus;
    }

    public function setApprovalStatus(int $approvalStatus): self
    {
        $this->approvalStatus = $approvalStatus;

        return $this;
    }

    public function getApprovedQuantity(): ?int
    {
        return $this->approvedQuantity;
    }

    public function setApprovedQuantity(?int $approvedQuantity): self
    {
        $this->approvedQuantity = $approvedQuantity;

        return $this;
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

    public function getTypeOfDocAndNum(): ?string
    {
        return $this->typeOfDocAndNum;
    }

    public function setTypeOfDocAndNum(?string $typeOfDocAndNum): self
    {
        $this->typeOfDocAndNum = $typeOfDocAndNum;

        return $this;
    }

    public function getDriver(): ?string
    {
        return $this->driver;
    }

    public function setDriver(?string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getTrackPlateNum(): ?string
    {
        return $this->trackPlateNum;
    }

    public function setTrackPlateNum(?string $trackPlateNum): self
    {
        $this->trackPlateNum = $trackPlateNum;

        return $this;
    }

    public function getTrailerNum(): ?string
    {
        return $this->trailerNum;
    }

    public function setTrailerNum(?string $trailerNum): self
    {
        $this->trailerNum = $trailerNum;

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

    public function getDeliveredBy(): ?Customer
    {
        return $this->deliveredBy;
    }

    public function setDeliveredBy(?Customer $deliveredBy): self
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
     * @return Collection|StockList[]
     */
    public function getStockLists(): Collection
    {
        return $this->stockLists;
    }

    public function addStockList(StockList $stockList): self
    {
        if (!$this->stockLists->contains($stockList)) {
            $this->stockLists[] = $stockList;
            $stockList->setStock($this);
        }

        return $this;
    }

    public function removeStockList(StockList $stockList): self
    {
        if ($this->stockLists->removeElement($stockList)) {
            // set the owning side to null (unless already changed)
            if ($stockList->getStock() === $this) {
                $stockList->setStock(null);
            }
        }

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }
}
