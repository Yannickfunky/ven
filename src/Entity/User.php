<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, \Serializable, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Panier $product = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Panier $panier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLES_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function isAdmin()
    {
        return in_array('ROLES\ADMIN', $this->getRoles());
    }

    public function getUserIdentifier(): string
    {
            return (string) $this->email;
    }
public function eraseCredentials (){}

public function serialize()
{
    return serialize(array(
        $this->id,
        $this->username,
        $this->email,
        $this->password
    ));
}

public function unserialize($serialised):void
{
    list(
        $this->id,
        $this->username,
        $this->email,
        $this->password
    ) = unserialize($serialised);
    }

public function getProduct(): ?Panier
{
    return $this->Product;
}

public function setProduct(?Panier $product): static
{
// unset the owning side of the relation if necessary
    if ($product === null && $this->Product !== null) {
        $this->Product->setUser(null);
    }

// set the owning side of the relation if necessary
    if ($product !== null && $Product->getUser() !== $this) {
        $product->setUser($this);
    }

    $this->Product = $product;

    return $this;
}

public function getPanier(): ?Panier
{
    return $this->panier;
}

public function setPanier(?Panier $panier): static
{
// unset the owning side of the relation if necessary
    if ($panier === null && $this->panier !== null) {
        $this->panier->setUser(null);
    }

// set the owning side of the relation if necessary
    if ($panier !== null && $panier->getUser() !== $this) {
        $panier->setUser($this);
    }

    $this->panier = $panier;

    return $this;
}
}
?>