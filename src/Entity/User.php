<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    private ?string $gsm = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $adresse = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commande::class)]
    private Collection $commandes;

    #[ORM\Column]
    private bool $actif = true;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Avis::class, orphanRemoval: true)]
    private Collection $avis;

    public function __construct()
{
    $this->commandes = new ArrayCollection();
    $this->avis = new ArrayCollection();
}
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getGsm(): ?string
    {
        return $this->gsm;
    }

    public function setGsm(string $gsm): static
    {
        $this->gsm = $gsm;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; 

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
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

    public function eraseCredentials(): void {}

    public function getCommandes(): Collection
  {
    return $this->commandes;
  }

    public function getAvis(): Collection
  {
    return $this->avis;
  }

   public function addAvis(Avis $avis): static
  {
    if (!$this->avis->contains($avis)) {
        $this->avis->add($avis);
        $avis->setUser($this);
    }

    return $this;
  }

    public function removeAvis(Avis $avis): static
  {
    if ($this->avis->removeElement($avis)) {
        if ($avis->getUser() === $this) {
            $avis->setUser(null);
        }
    }

    return $this;
  }

  public function isActif(): bool
{
    return $this->actif;
}

public function setActif(bool $actif): self
{
    $this->actif = $actif;
    return $this;
}
public function isEnabled(): bool
{
    return $this->actif;
}

}