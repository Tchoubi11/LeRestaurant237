<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;


   #[ORM\Column(type: 'integer')]
private int $nombrePersonnes = 1;

#[ORM\Column(type: 'float')]
private float $prixTotal = 0;

#[ORM\Column(type: 'float')]
private float $prixLivraison = 5;

#[ORM\Column(type: 'datetime')]
private ?\DateTimeInterface $dateCommande = null;

#[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'commandes')]
#[ORM\JoinColumn(nullable: false)]
private ?Menu $menu = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTime $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getMenu(): ?Menu
   {
    return $this->menu;
   }

   public function setMenu(?Menu $menu): static
   {
    $this->menu = $menu;

    return $this;
   }

   public function getNombrePersonnes(): ?int
   {
    return $this->nombrePersonnes;
   }

   public function setNombrePersonnes(int $nombrePersonnes): self
   {
    $this->nombrePersonnes = $nombrePersonnes;

    return $this;
   }

   public function getPrixTotal(): ?float
  {
    return $this->prixTotal;
  }

  public function setPrixTotal(float $prixTotal): self
  {
    $this->prixTotal = $prixTotal;

    return $this;
  }
  
  public function getPrixLivraison(): ?float
  {
    return $this->prixLivraison;
  }

  public function setPrixLivraison(float $prixLivraison): self
  {
    $this->prixLivraison = $prixLivraison;

    return $this;
  }
}
