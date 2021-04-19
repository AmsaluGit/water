<?php

namespace App\Entity;

use App\Repository\ConsumptionDeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $deliveredBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $approvedBy;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionDeliveryList::class, mappedBy="consumptionDelivery", cascade={"remove"})
     */
    private $consumptionDeliveryLists;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $approvalStatus;
 
    /**
     * @ORM\Column(type="datetime")
     */
    private $deliveredDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeOfDelivery;

    public function __construct()
    {
        $this->consumptionDeliveryLists = new ArrayCollection();
    }

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
     * @return Collection|ConsumptionDeliveryList[]
     */
    public function getConsumptionDeliveryLists(): Collection
    {
        return $this->consumptionDeliveryLists;
    }

    public function addConsumptionDeliveryList(ConsumptionDeliveryList $consumptionDeliveryList): self
    {
        if (!$this->consumptionDeliveryLists->contains($consumptionDeliveryList)) {
            $this->consumptionDeliveryLists[] = $consumptionDeliveryList;
            $consumptionDeliveryList->setConsumptionDelivery($this);
        }

        return $this;
    }

    public function removeConsumptionDeliveryList(ConsumptionDeliveryList $consumptionDeliveryList): self
    {
        if ($this->consumptionDeliveryLists->removeElement($consumptionDeliveryList)) {
            // set the owning side to null (unless already changed)
            if ($consumptionDeliveryList->getConsumptionDelivery() === $this) {
                $consumptionDeliveryList->setConsumptionDelivery(null);
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

    public function getDeliveredDate(): ?\DateTimeInterface
    {
        return $this->deliveredDate;
    }

    public function setDeliveredDate(\DateTimeInterface $deliveredDate): self
    {
        $this->deliveredDate = $deliveredDate;

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
<<<<<<< HEAD

    public function getPlaceOfDelivery(): ?string
    {
        return $this->placeOfDelivery;
    }

    public function setPlaceOfDelivery(?string $placeOfDelivery): self
    {
        $this->placeOfDelivery = $placeOfDelivery;

        return $this;
    }
=======
>>>>>>> ABI
}
