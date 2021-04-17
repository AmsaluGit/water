<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="sections")
     */
    private $department;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=StockRequest::class, mappedBy="section")
     */
    private $stockRequests;

    public function __construct()
    {
        $this->stockRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|StockRequest[]
     */
    public function getStockRequests(): Collection
    {
        return $this->stockRequests;
    }

    public function addStockRequest(StockRequest $stockRequest): self
    {
        if (!$this->stockRequests->contains($stockRequest)) {
            $this->stockRequests[] = $stockRequest;
            $stockRequest->setSection($this);
        }

        return $this;
    }

    public function removeStockRequest(StockRequest $stockRequest): self
    {
        if ($this->stockRequests->removeElement($stockRequest)) {
            // set the owning side to null (unless already changed)
            if ($stockRequest->getSection() === $this) {
                $stockRequest->setSection(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
