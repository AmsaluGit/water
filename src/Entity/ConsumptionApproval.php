<?php

namespace App\Entity;

use App\Repository\ConsumptionApprovalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumptionApprovalRepository::class)
 */
class ConsumptionApproval
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ConsumptionRequest::class, inversedBy="consumptionApprovals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $consumptionRequest;

    /**
     * @ORM\Column(type="integer")
     */
    private $approvedQuantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOfApproval;

    /**
     * @ORM\Column(type="smallint")
     */
    private $approvalResponse;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consumptionApprovals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $approvedBy;

    /**
     * @ORM\Column(type="smallint")
     */
    private $approvalLevel;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setApprovedQuantity(int $approvedQuantity): self
    {
        $this->approvedQuantity = $approvedQuantity;

        return $this;
    }

    public function getDateOfApproval(): ?\DateTimeInterface
    {
        return $this->dateOfApproval;
    }

    public function setDateOfApproval(\DateTimeInterface $dateOfApproval): self
    {
        $this->dateOfApproval = $dateOfApproval;

        return $this;
    }

    public function getApprovalResponse(): ?int
    {
        return $this->approvalResponse;
    }

    public function setApprovalResponse(int $approvalResponse): self
    {
        $this->approvalResponse = $approvalResponse;

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

    public function getApprovedBy(): ?User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?User $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    public function getApprovalLevel(): ?int
    {
        return $this->approvalLevel;
    }

    public function setApprovalLevel(int $approvalLevel): self
    {
        $this->approvalLevel = $approvalLevel;

        return $this;
    }
}
