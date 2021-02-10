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
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="consumptionRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=UnitOfMeasure::class, inversedBy="consumptionRequests")
     */
    private $unitOfMeasure;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionApproval::class, mappedBy="consumptionRequest", orphanRemoval=true)
     */
    private $consumptionApprovals;

    public function __construct()
    {
        $this->consumptionApprovals = new ArrayCollection();
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
}
