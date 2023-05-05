<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'formation')]
    private \Doctrine\ORM\PersistentCollection $idformation;

    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'formation')]
    private \Doctrine\ORM\PersistentCollection $quiz;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getIdFormation(): ?user
    {
        return $this->idFormation;
    }

    public function setIdFormation(?user $idFormation): self
    {
        $this->idFormation = $idFormation;

        return $this;
    }
}
