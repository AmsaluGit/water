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
     * @ORM\OneToMany(targetEntity=StockList::class, mappedBy="unitOfMeasure")
     */
    private $stockLists;

    /**
     * @ORM\OneToMany(targetEntity=StockRequestList::class, mappedBy="unitOfMeasure")
     */
    private $stockRequestLists;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionRequestList::class, mappedBy="unitOfMeasure")
     */
    private $consumptionRequestLists;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionDeliveryList::class, mappedBy="unitOfMeasure")
     */
    private $consumptionDeliveryLists;

    public function __construct()
    {
     
     
        $this->stockLists = new ArrayCollection();
        $this->stockRequestLists = new ArrayCollection();
        $this->consumptionRequestLists = new ArrayCollection();
        $this->consumptionDeliveryLists = new ArrayCollection();
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
     * @return Collection|StockList[]
     */
    public function getStockLists(): Collection
    {
        return $this->stockLists;
    }

    public function addStockList(StockList $stockList): self
    {
        if (!$this->stockLists->contains($stockList)) {
            $this->stockLists[] = $stockList;
            $stockList->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeStockList(StockList $stockList): self
    {
        if ($this->stockLists->removeElement($stockList)) {
            // set the owning side to null (unless already changed)
            if ($stockList->getUnitOfMeasure() === $this) {
                $stockList->setUnitOfMeasure(null);
            }
        }

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
            $stockRequestList->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeStockRequestList(StockRequestList $stockRequestList): self
    {
        if ($this->stockRequestLists->removeElement($stockRequestList)) {
            // set the owning side to null (unless already changed)
            if ($stockRequestList->getUnitOfMeasure() === $this) {
                $stockRequestList->setUnitOfMeasure(null);
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
            $consumptionRequestList->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeConsumptionRequestList(ConsumptionRequestList $consumptionRequestList): self
    {
        if ($this->consumptionRequestLists->removeElement($consumptionRequestList)) {
            // set the owning side to null (unless already changed)
            if ($consumptionRequestList->getUnitOfMeasure() === $this) {
                $consumptionRequestList->setUnitOfMeasure(null);
            }
        }

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
            $consumptionDeliveryList->setUnitOfMeasure($this);
        }

        return $this;
    }

    public function removeConsumptionDeliveryList(ConsumptionDeliveryList $consumptionDeliveryList): self
    {
        if ($this->consumptionDeliveryLists->removeElement($consumptionDeliveryList)) {
            // set the owning side to null (unless already changed)
            if ($consumptionDeliveryList->getUnitOfMeasure() === $this) {
                $consumptionDeliveryList->setUnitOfMeasure(null);
            }
        }

        return $this;
    }
}
