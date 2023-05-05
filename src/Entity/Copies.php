<?php

namespace App\Entity;

use App\Repository\CopiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CopiesRepository::class)]
class Copies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'copies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $iduser = null;

    #[ORM\ManyToOne(inversedBy: 'copies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $idquiz = null;

    #[ORM\Column(nullable: true)]
    private ?string $annotationGlobale = null;

    #[ORM\Column(nullable: true)]
    private ?int $noteMaxQuiz = null;

    #[ORM\OneToMany(mappedBy: 'copie', targetEntity: Reponses::class)]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdquiz(): ?Quiz
    {
        return $this->idquiz;
    }

    public function setIdquiz(?Quiz $idquiz): self
    {
        $this->idquiz = $idquiz;

        return $this;
    }

    public function getAnnotationGlobale(): ?string
    {
        return $this->annotationGlobale;
    }

    public function setAnnotationGlobale(?string $annotationGlobale): self
    {
        $this->annotationGlobale = $annotationGlobale;

        return $this;
    }

    public function getNoteMaxQuiz(): ?int
    {
        return $this->noteMaxQuiz;
    }

    public function setNoteMaxQuiz(?int $noteMaxQuiz): self
    {
        $this->noteMaxQuiz = $noteMaxQuiz;

        return $this;
    }

    /**
     * @return Collection<int, Reponses>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponses $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setCopie($this);
        }

        return $this;
    }

    public function removeReponse(Reponses $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getCopie() === $this) {
                $reponse->setCopie(null);
            }
        }

        return $this;
    }
}
