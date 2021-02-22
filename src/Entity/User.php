<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserGroup", inversedBy="users")
     */
    private $userGroup;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $middleName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="registeredBy")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=StockApproval::class, mappedBy="approvedBy", orphanRemoval=true)
     */
    private $stockApprovals;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionRequest::class, mappedBy="requester", orphanRemoval=true)
     */
    private $consumptionRequests;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionApproval::class, mappedBy="approvedBy", orphanRemoval=true)
     */
    private $consumptionApprovals;

    /**
     * @ORM\OneToMany(targetEntity=StockRequest::class, mappedBy="requestedBy")
     */
    private $stockRequests;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionRequest::class, mappedBy="approvedBy")
     */
    private $consRequest;

    /**
     * @ORM\OneToMany(targetEntity=ConsumptionDelivery::class, mappedBy="receiver")
     */
    private $consumptionDeliveries;

    /**
     * @ORM\OneToMany(targetEntity=ProductDelivery::class, mappedBy="handOveredBy")
     */
    private $productDeliveries;

    /**
     * @ORM\OneToMany(targetEntity=Sells::class, mappedBy="receivedBy")
     */
    private $sells;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->stockApprovals = new ArrayCollection();
        $this->consumptionRequests = new ArrayCollection();
        $this->consumptionApprovals = new ArrayCollection();
        $this->stockRequests = new ArrayCollection();
        $this->consRequest = new ArrayCollection();
        $this->consumptionDeliveries = new ArrayCollection();
        $this->productDeliveries = new ArrayCollection();
        $this->sells = new ArrayCollection();
    }

 


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

         /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroup(): Collection
    {
        return $this->userGroup;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroup->contains($userGroup)) {
            $this->userGroup[] = $userGroup;
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroup->contains($userGroup)) {
            $this->userGroup->removeElement($userGroup);
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
    public function __toString()
    {
        return $this->firstName." ".$this->middleName." ".$this->lastName;
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
            $stock->setRegisteredBy($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getRegisteredBy() === $this) {
                $stock->setRegisteredBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StockApproval[]
     */
    public function getStockApprovals(): Collection
    {
        return $this->stockApprovals;
    }

    public function addStockApproval(StockApproval $stockApproval): self
    {
        if (!$this->stockApprovals->contains($stockApproval)) {
            $this->stockApprovals[] = $stockApproval;
            $stockApproval->setApprovedBy($this);
        }

        return $this;
    }

    public function removeStockApproval(StockApproval $stockApproval): self
    {
        if ($this->stockApprovals->removeElement($stockApproval)) {
            // set the owning side to null (unless already changed)
            if ($stockApproval->getApprovedBy() === $this) {
                $stockApproval->setApprovedBy(null);
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
            $consumptionRequest->setRequester($this);
        }

        return $this;
    }

    public function removeConsumptionRequest(ConsumptionRequest $consumptionRequest): self
    {
        if ($this->consumptionRequests->removeElement($consumptionRequest)) {
            // set the owning side to null (unless already changed)
            if ($consumptionRequest->getRequester() === $this) {
                $consumptionRequest->setRequester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsumptionApproval[]
     */
    public function getConsumptionApprovals(): Collection
    {
        return $this->consumptionApprovals;
    }

    public function addConsumptionApproval(ConsumptionApproval $consumptionApproval): self
    {
        if (!$this->consumptionApprovals->contains($consumptionApproval)) {
            $this->consumptionApprovals[] = $consumptionApproval;
            $consumptionApproval->setApprovedBy($this);
        }

        return $this;
    }

    public function removeConsumptionApproval(ConsumptionApproval $consumptionApproval): self
    {
        if ($this->consumptionApprovals->removeElement($consumptionApproval)) {
            // set the owning side to null (unless already changed)
            if ($consumptionApproval->getApprovedBy() === $this) {
                $consumptionApproval->setApprovedBy(null);
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
            $stockRequest->setRequestedBy($this);
        }

        return $this;
    }

    public function removeStockRequest(StockRequest $stockRequest): self
    {
        if ($this->stockRequests->removeElement($stockRequest)) {
            // set the owning side to null (unless already changed)
            if ($stockRequest->getRequestedBy() === $this) {
                $stockRequest->setRequestedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsumptionRequest[]
     */
    public function getConsRequest(): Collection
    {
        return $this->consRequest;
    }

    public function addConsRequest(ConsumptionRequest $consRequest): self
    {
        if (!$this->consRequest->contains($consRequest)) {
            $this->consRequest[] = $consRequest;
            $consRequest->setApprovedBy($this);
        }

        return $this;
    }

    public function removeConsRequest(ConsumptionRequest $consRequest): self
    {
        if ($this->consRequest->removeElement($consRequest)) {
            // set the owning side to null (unless already changed)
            if ($consRequest->getApprovedBy() === $this) {
                $consRequest->setApprovedBy(null);
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
            $consumptionDelivery->setReceiver($this);
        }

        return $this;
    }

    public function removeConsumptionDelivery(ConsumptionDelivery $consumptionDelivery): self
    {
        if ($this->consumptionDeliveries->removeElement($consumptionDelivery)) {
            // set the owning side to null (unless already changed)
            if ($consumptionDelivery->getReceiver() === $this) {
                $consumptionDelivery->setReceiver(null);
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
            $productDelivery->setHandOveredBy($this);
        }

        return $this;
    }

    public function removeProductDelivery(ProductDelivery $productDelivery): self
    {
        if ($this->productDeliveries->removeElement($productDelivery)) {
            // set the owning side to null (unless already changed)
            if ($productDelivery->getHandOveredBy() === $this) {
                $productDelivery->setHandOveredBy(null);
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
            $sell->setReceivedBy($this);
        }

        return $this;
    }

    public function removeSell(Sells $sell): self
    {
        if ($this->sells->removeElement($sell)) {
            // set the owning side to null (unless already changed)
            if ($sell->getReceivedBy() === $this) {
                $sell->setReceivedBy(null);
            }
        }

        return $this;
    }

    
}
