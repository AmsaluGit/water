<?php

namespace App\Entity;

use App\Repository\StockRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRequestRepository::class)
 */
class StockRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="stockRequests")
     */
    private $requestingDept;

    // /**
    //  * @ORM\Column(type="string", length=255, nullable=true)
    //  */
    // private $section;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stockRequests")
     */
    private $requestedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stockRequests")
     */
    private $storeKeeper;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stockRequests")
     */
    private $approvedBy;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $requestStatus;

    /**
     * @ORM\OneToMany(targetEntity=StockRequestList::class, mappedBy="stockRequest",cascade={"remove"})
     */
    private $stockRequestLists;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $approvalStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="stockRequests")
     */
    private $section;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $note;

    public function __construct()
    {
        $this->stockRequestLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getRequestingDept(): ?Department
    {
        return $this->requestingDept;
    }

    public function setRequestingDept(?Department $requestingDept): self
    {
        $this->requestingDept = $requestingDept;

        return $this;
    }

    // public function getSection(): ?string
    // {
    //     return $this->section;
    // }

    // public function setSection(?string $section): self
    // {
    //     $this->section = $section;

    //     return $this;
    // }

    public function getRequestedBy(): ?User
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(?User $requestedBy): self
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }

    public function getStoreKeeper(): ?User
    {
        return $this->storeKeeper;
    }

    public function setStoreKeeper(?User $storeKeeper): self
    {
        $this->storeKeeper = $storeKeeper;

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

    public function getRequestStatus(): ?int
    {
        return $this->requestStatus;
    }

    public function setRequestStatus(?int $requestStatus): self
    {
        $this->requestStatus = $requestStatus;

        return $this;
    }

    /**
     * @return Collection|StockRequestList[]
     */
    public function getStockRequestLists(): Collection
    {
        return $this->stockRequestLists;
    }

    public function addStockRequestList(StockRequestList $stockRequestList): self
    {
        if (!$this->stockRequestLists->contains($stockRequestList)) {
            $this->stockRequestLists[] = $stockRequestList;
            $stockRequestList->setStockRequest($this);
        }

        return $this;
    }

    public function removeStockRequestList(StockRequestList $stockRequestList): self
    {
        if ($this->stockRequestLists->removeElement($stockRequestList)) {
            // set the owning side to null (unless already changed)
            if ($stockRequestList->getStockRequest() === $this) {
                $stockRequestList->setStockRequest(null);
            }
        }

        return $this;
    }

    public function getApprovalStatus(): ?int
    {
        return $this->approvalStatus;
    }

    public function setApprovalStatus(?int $approvalStatus): self
    {
        $this->approvalStatus = $approvalStatus;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
