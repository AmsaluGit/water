<?php

namespace App\Entity;

use App\Repository\UnitOfMeasureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnitOfMeasureRepository::class)
 */
class UnitOfMeasure
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="unitOfMeasure")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=StockRequest::class, mappedBy="unitOfMeasure")
     */
    private $stockRequests;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionRequest::class, mappedBy="unitOfMeasure")
     */
    private $consumptionRequests;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionDelivery::class, mappedBy="unitOfMeasure")
     */
    private $consumptionDeliveries;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->stockRequests = new ArrayCollection();
        $this->consumptionRequests = new ArrayCollection();
        $this->consumptionDeliveries = new ArrayCollection();
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
     * @return Collection|Stock[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getUnitOfMeasure() === $this) {
                $stock->setUnitOfMeasure(null);
            }
        }

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
            $stockRequest->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeStockRequest(StockRequest $stockRequest): self
    {
        if ($this->stockRequests->removeElement($stockRequest)) {
            // set the owning side to null (unless already changed)
            if ($stockRequest->getUnitOfMeasure() === $this) {
                $stockRequest->setUnitOfMeasure(null);
            }
        }

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
            $consumptionRequest->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeConsumptionRequest(ConsumptionRequest $consumptionRequest): self
    {
        if ($this->consumptionRequests->removeElement($consumptionRequest)) {
            // set the owning side to null (unless already changed)
            if ($consumptionRequest->getUnitOfMeasure() === $this) {
                $consumptionRequest->setUnitOfMeasure(null);
            }
        }

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
            $consumptionDelivery->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeConsumptionDelivery(ConsumptionDelivery $consumptionDelivery): self
    {
        if ($this->consumptionDeliveries->removeElement($consumptionDelivery)) {
            // set the owning side to null (unless already changed)
            if ($consumptionDelivery->getUnitOfMeasure() === $this) {
                $consumptionDelivery->setUnitOfMeasure(null);
            }
        }

        return $this;
    }
}