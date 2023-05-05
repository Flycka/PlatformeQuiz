<?php

namespace App\Entity;

use App\Repository\ReponsesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponsesRepository::class)]
class Reponses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reponse = null;

    #[ORM\Column(nullable: true)]
    private ?int $noteReponse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $annotationQuestion = null;



    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $apprenant = null;

    #[ORM\ManyToOne(inversedBy: 'idqreponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questions $idQuestion = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdQuestion(): ?Questions
    {
        return $this->idQuestion;
    }

    public function setIdQuestion($idQuestion): self
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getNoteReponse(): ?int
    {
        return $this->noteReponse;
    }

    public function setNoteReponse(?int $noteReponse): self
    {
        $this->noteReponse = $noteReponse;

        return $this;
    }

    public function getAnnotationQuestion(): ?string
    {
        return $this->annotationQuestion;
    }

    public function setAnnotationQuestion(?string $annotationQuestion): self
    {
        $this->annotationQuestion = $annotationQuestion;

        return $this;
    }


    public function getApprenant(): ?user
    {
        return $this->apprenant;
    }

    public function setApprenant(?user $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getQuizId(): ?Quiz
    {
        return $this->quiz_id;
    }

    public function setQuizId(?Quiz $quiz_id): self
    {
        $this->quiz_id = $quiz_id;

        return $this;
    }
}
