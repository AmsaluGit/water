<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 */
class Setting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $stockApprovalLevel;

    /**
     * @ORM\Column(type="smallint")
     */
    private $consumptionApprovalLevel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStockApprovalLevel(): ?int
    {
        return $this->stockApprovalLevel;
    }

    public function setStockApprovalLevel(int $stockApprovalLevel): self
    {
        $this->stockApprovalLevel = $stockApprovalLevel;

        return $this;
    }

    public function getConsumptionApprovalLevel(): ?int
    {
        return $this->consumptionApprovalLevel;
    }

    public function setConsumptionApprovalLevel(int $consumptionApprovalLevel): self
    {
        $this->consumptionApprovalLevel = $consumptionApprovalLevel;

        return $this;
    }
}
