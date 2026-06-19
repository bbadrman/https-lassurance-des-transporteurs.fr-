<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $raison = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $activite = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $assurer = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $souhAssurer = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $codepostal = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tele = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ancienne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $creatAt = null;

     #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->creatAt === null) {
            $this->creatAt = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): static
    {
        $this->raison = $raison;

        return $this;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(?string $activite): static
    {
        $this->activite = $activite;

        return $this;
    }

    public function getAssurer(): ?string
    {
        return $this->assurer;
    }

    public function setAssurer(?string $assurer): static
    {
        $this->assurer = $assurer;

        return $this;
    }

    public function gettype(): ?string
    {
        return $this->type;
    }

    public function settype(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getsouhAssurer(): ?string
    {
        return $this->souhAssurer;
    }

    public function setsouhAssurer(?string $souhAssurer): static
    {
        $this->souhAssurer = $souhAssurer;

        return $this;
    }

    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(?string $codepostal): static
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(?string $tele): static
    {
        $this->tele = $tele;

        return $this;
    }

    public function getAncienne(): ?string
    {
        return $this->ancienne;
    }

    public function setAncienne(?string $ancienne): static
    {
        $this->ancienne = $ancienne;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeImmutable
    {
        return $this->creatAt;
    }

    public function setCreatAt(?\DateTimeImmutable $creatAt): static
    {
        $this->creatAt = $creatAt;

        return $this;
    }
}
