<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column]
    private ?int $noteMaximalequestion = null;


    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\OneToMany(mappedBy: 'idQuestion', targetEntity: Reponses::class)]
    private Collection $idqreponses;

    public function __construct()
    {
        $this->idqreponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getNoteMaximalequestion(): ?int
    {
        return $this->noteMaximalequestion;
    }

    public function setNoteMaximalequestion(int $noteMaximalequestion): self
    {
        $this->noteMaximalequestion = $noteMaximalequestion;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection<int, Reponses>
     */
    public function getIdqreponses(): Collection
    {
        return $this->idqreponses;
    }

    public function addIdqreponse(Reponses $idqreponse): self
    {
        if (!$this->idqreponses->contains($idqreponse)) {
            $this->idqreponses->add($idqreponse);
            $idqreponse->setIdQuestion($this);
        }

        return $this;
    }

    public function removeIdqreponse(Reponses $idqreponse): self
    {
        if ($this->idqreponses->removeElement($idqreponse)) {
            // set the owning side to null (unless already changed)
            if ($idqreponse->getIdQuestion() === $this) {
                $idqreponse->setIdQuestion(null);
            }
        }

        return $this;
    }
}
