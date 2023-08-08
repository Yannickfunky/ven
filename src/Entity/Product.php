<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $Price = null;

    #[ORM\Column]
    private ?bool $valid = null;

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'ManyToMareturn')]
    private Collection $yes;

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'product')]
    private Collection $paniers;

    #[ORM\Column(type:'integer')]
    private ?int $quantityInCart = 0;

    public function __construct()
    {
        $this->yes = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->Price;
    }

    public function setPrice(string $Price): static
    {
        $this->Price = $Price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): static
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Panier $ye): static
    {
        if (!$this->yes->contains($ye)) {
            $this->yes->add($ye);
            $ye->addManyToMareturn($this);
        }

        return $this;
    }

    public function removeYe(Panier $ye): static
    {
        if ($this->yes->removeElement($ye)) {
            $ye->removeManyToMareturn($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->addProduct($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            $panier->removeProduct($this);
        }

        return $this;
    } 

    public function getQuantityInCart(): ?int
    {
        return $this->quantityInCart;
    }

    public function setQuantityInCart(int $quantityInCart): self
    {
        $this->quantityInCart = $quantityInCart;

        return $this;

        
    } 

}
?>