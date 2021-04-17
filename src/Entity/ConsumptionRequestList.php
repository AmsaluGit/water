<?php

namespace App\Entity;

use App\Repository\ConsumptionRequestListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumptionRequestListRepository::class)
 */
class ConsumptionRequestList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="consumptionRequestLists")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=UnitOfMeasure::class, inversedBy="consumptionRequestLists")
     */
    private $unitOfMeasure;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeNumber;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $available;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $issue;

    /**
     * @ORM\ManyToOne(targetEntity=ConsumptionRequest::class, inversedBy="consumptionRequestLists")
     */
    private $consumptionRequest;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $approvedQuantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remark;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $approvalStatus;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCodeNumber(): ?string
    {
        return $this->codeNumber;
    }

    public function setCodeNumber(string $codeNumber): self
    {
        $this->codeNumber = $codeNumber;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(int $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getIssue(): ?int
    {
        return $this->issue;
    }

    public function setIssue(int $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    public function getConsumptionRequest(): ?ConsumptionRequest
    {
        return $this->consumptionRequest;
    }

    public function setConsumptionRequest(?ConsumptionRequest $consumptionRequest): self
    {
        $this->consumptionRequest = $consumptionRequest;

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

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

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
}
