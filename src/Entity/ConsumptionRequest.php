<?php

namespace App\Entity;

use App\Repository\ConsumptionRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumptionRequestRepository::class)
 */
class ConsumptionRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consumptionRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $requester;

    /**
     * @ORM\Column(type="datetime")
     */
    private $requestedDate;
 
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionApproval::class, mappedBy="consumptionRequest", orphanRemoval=true)
     */
    private $consumptionApprovals;

    /**
     * @ORM\Column(type="smallint", nullable = true)
     */
    private $approvalStatus;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consRequest")
     */
    private $approvedBy;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionDelivery::class, mappedBy="requestNo")
     */
    private $consumptionDeliveries;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionRequestList::class, mappedBy="consumptionRequest", cascade="remove")
     */
    private $consumptionRequestLists;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="consumptionRequests")
     */
    private $section;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $serialNo;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="consumptionRequests")
    //  */
    // private $section;


    public function __construct()
    {
        $this->consumptionApprovals = new ArrayCollection();
        $this->consumptionDeliveries = new ArrayCollection();
        $this->consumptionRequestLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequester(): ?User
    {
        return $this->requester;
    }

    public function setRequester(?User $requester): self
    {
        $this->requester = $requester;

        return $this;
    }

    public function getRequestedDate(): ?\DateTimeInterface
    {
        return $this->requestedDate;
    }

    public function setRequestedDate(\DateTimeInterface $requestedDate): self
    {
        $this->requestedDate = $requestedDate;

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

    /**
     * @return Collection|ConsumptionApproval[]
     */
    public function getConsumptionApprovals(): Collection
    {
        return $this->consumptionApprovals;
    }

    public function addConsumptionApproval(ConsumptionApproval $consumptionApproval): self
    {
        if (!$this->consumptionApprovals->contains($consumptionApproval)) {
            $this->consumptionApprovals[] = $consumptionApproval;
            $consumptionApproval->setConsumptionRequest($this);
        }

        return $this;
    }

    public function removeConsumptionApproval(ConsumptionApproval $consumptionApproval): self
    {
        if ($this->consumptionApprovals->removeElement($consumptionApproval)) {
            // set the owning side to null (unless already changed)
            if ($consumptionApproval->getConsumptionRequest() === $this) {
                $consumptionApproval->setConsumptionRequest(null);
            }
        }

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

  

  
  

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

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
     * @return Collection|ConsumptionDelivery[]
     */
    public function getConsumptionDeliveries(): Collection
    {
        return $this->consumptionDeliveries;
    }

    public function addConsumptionDelivery(ConsumptionDelivery $consumptionDelivery): self
    {
        if (!$this->consumptionDeliveries->contains($consumptionDelivery)) {
            $this->consumptionDeliveries[] = $consumptionDelivery;
            $consumptionDelivery->setRequestNo($this);
        }

        return $this;
    }

    public function removeConsumptionDelivery(ConsumptionDelivery $consumptionDelivery): self
    {
        if ($this->consumptionDeliveries->removeElement($consumptionDelivery)) {
            // set the owning side to null (unless already changed)
            if ($consumptionDelivery->getRequestNo() === $this) {
                $consumptionDelivery->setRequestNo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsumptionRequestList[]
     */
    public function getConsumptionRequestLists(): Collection
    {
        return $this->consumptionRequestLists;
    }

    public function addConsumptionRequestList(ConsumptionRequestList $consumptionRequestList): self
    {
        if (!$this->consumptionRequestLists->contains($consumptionRequestList)) {
            $this->consumptionRequestLists[] = $consumptionRequestList;
            $consumptionRequestList->setConsumptionRequest($this);
        }

        return $this;
    }

    public function removeConsumptionRequestList(ConsumptionRequestList $consumptionRequestList): self
    {
        if ($this->consumptionRequestLists->removeElement($consumptionRequestList)) {
            // set the owning side to null (unless already changed)
            if ($consumptionRequestList->getConsumptionRequest() === $this) {
                $consumptionRequestList->setConsumptionRequest(null);
            }
        }

        return $this;
    }

    // public function getSection(): ?Section
    // {
    //     return $this->section;
    // }

    // public function setSection(?Section $section): self
    // {
    //     $this->section = $section;

    //     return $this;
    // }

    // public function __toString(){
    //     return $this->;
    // }

    // public function getSection(): ?Section
    // {
    //     return $this->section;
    // }

    // public function setSection(?Section $section): self
    // {
    //     $this->section = $section;

    //     return $this;
    // }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function __toString(){
        return strval($this->id);
    }

    public function getSerialNo(): ?int
    {
        return $this->serialNo;
    }

    public function setSerialNo(?int $serialNo): self
    {
        $this->serialNo = $serialNo;

        return $this;
    }
 
}
