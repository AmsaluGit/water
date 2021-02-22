<?php

namespace App\Entity;

use App\Repository\StockRequestRepository;
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
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="stockRequests")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $specification;

    /**
     * @ORM\ManyToOne(targetEntity=UnitOfMeasure::class, inversedBy="stockRequests")
     */
    private $unitOfMeasure;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="stockRequests")
     */
    private $requestingDept;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $section;

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

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(string $specification): self
    {
        $this->specification = $specification;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): self
    {
        $this->section = $section;

        return $this;
    }

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
}
