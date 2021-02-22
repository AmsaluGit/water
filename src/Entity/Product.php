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

    /**
     * @ORM\OneToMany(targetEntity=StockRequest::class, mappedBy="product")
     */
    private $stockRequests;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionDelivery::class, mappedBy="product")
     */
    private $consumptionDeliveries;

    /**
     * @ORM\OneToMany(targetEntity=ProductDelivery::class, mappedBy="product")
     */
    private $productDeliveries;

    /**
     * @ORM\OneToMany(targetEntity=Sells::class, mappedBy="product")
     */
    private $sells;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->consumptionRequests = new ArrayCollection();
        $this->stockRequests = new ArrayCollection();
        $this->consumptionDeliveries = new ArrayCollection();
        $this->productDeliveries = new ArrayCollection();
        $this->sells = new ArrayCollection();
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
    public function __toString(){
        return $this->name;
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
            $stockRequest->setProduct($this);
        }

        return $this;
    }

    public function removeStockRequest(StockRequest $stockRequest): self
    {
        if ($this->stockRequests->removeElement($stockRequest)) {
            // set the owning side to null (unless already changed)
            if ($stockRequest->getProduct() === $this) {
                $stockRequest->setProduct(null);
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
            $consumptionDelivery->setProduct($this);
        }

        return $this;
    }

    public function removeConsumptionDelivery(ConsumptionDelivery $consumptionDelivery): self
    {
        if ($this->consumptionDeliveries->removeElement($consumptionDelivery)) {
            // set the owning side to null (unless already changed)
            if ($consumptionDelivery->getProduct() === $this) {
                $consumptionDelivery->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductDelivery[]
     */
    public function getProductDeliveries(): Collection
    {
        return $this->productDeliveries;
    }

    public function addProductDelivery(ProductDelivery $productDelivery): self
    {
        if (!$this->productDeliveries->contains($productDelivery)) {
            $this->productDeliveries[] = $productDelivery;
            $productDelivery->setProduct($this);
        }

        return $this;
    }

    public function removeProductDelivery(ProductDelivery $productDelivery): self
    {
        if ($this->productDeliveries->removeElement($productDelivery)) {
            // set the owning side to null (unless already changed)
            if ($productDelivery->getProduct() === $this) {
                $productDelivery->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sells[]
     */
    public function getSells(): Collection
    {
        return $this->sells;
    }

    public function addSell(Sells $sell): self
    {
        if (!$this->sells->contains($sell)) {
            $this->sells[] = $sell;
            $sell->setProduct($this);
        }

        return $this;
    }

    public function removeSell(Sells $sell): self
    {
        if ($this->sells->removeElement($sell)) {
            // set the owning side to null (unless already changed)
            if ($sell->getProduct() === $this) {
                $sell->setProduct(null);
            }
        }

        return $this;
    }
}
