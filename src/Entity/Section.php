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
     * @ORM\OneToMany(targetEntity=ConsumptionRequest::class, mappedBy="section")
     */
    private $consumptionRequests;

    public function __construct()
    {
        $this->consumptionRequests = new ArrayCollection();
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
     * @return Collection|ConsumptionRequest[]
     */
    public function getConsumptionRequests(): Collection
    {
        return $this->consumptionRequests;
    }

    public function addConsumptionRequest(ConsumptionRequest $consumptionRequest): self
    {
        if (!$this->consumptionRequests->contains($consumptionRequest)) {
            $this->consumptionRequests[] = $consumptionRequest;
            $consumptionRequest->setSection($this);
        }

        return $this;
    }

    public function removeConsumptionRequest(ConsumptionRequest $consumptionRequest): self
    {
        if ($this->consumptionRequests->removeElement($consumptionRequest)) {
            // set the owning side to null (unless already changed)
            if ($consumptionRequest->getSection() === $this) {
                $consumptionRequest->setSection(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }
}
