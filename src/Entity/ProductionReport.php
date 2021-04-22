<?php

namespace App\Entity;

use App\Repository\ProductionReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductionReportRepository::class)
 */
class ProductionReport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $PVR;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productionReport")
     */
    private $product;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPVR(): ?string
    {
        return $this->PVR;
    }

    public function setPVR(?string $PVR): self
    {
        $this->PVR = $PVR;

        return $this;
    }

    public function getdate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setdate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
