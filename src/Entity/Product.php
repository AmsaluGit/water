<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="product")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionRequest::class, mappedBy="product", orphanRemoval=true)
     */
    private $consumptionRequests;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $stock->setProduct($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduct() === $this) {
                $stock->setProduct(null);
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
            $consumptionRequest->setProduct($this);
        }

        return $this;
    }

    public function removeConsumptionRequest(ConsumptionRequest $consumptionRequest): self
    {
        if ($this->consumptionRequests->removeElement($consumptionRequest)) {
            // set the owning side to null (unless already changed)
            if ($consumptionRequest->getProduct() === $this) {
                $consumptionRequest->setProduct(null);
            }
        }

        return $this;
    }
}
