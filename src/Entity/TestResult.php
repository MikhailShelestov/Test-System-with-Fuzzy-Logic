<?php

namespace App\Entity;

use App\Repository\TestResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestResultRepository::class)]
class TestResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?float $score = null;

    /**
     * @var Collection<int, TestResultAnswers>
     */
    #[ORM\OneToMany(targetEntity: TestResultAnswers::class, mappedBy: 'testResult', orphanRemoval: true)]
    private Collection $resultAnswers;

    public function __construct()
    {
        $this->resultAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): static
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection<int, TestResultAnswers>
     */
    public function getResultAnswers(): Collection
    {
        return $this->resultAnswers;
    }

    public function addResultAnswer(TestResultAnswers $resultAnswer): static
    {
        if (!$this->resultAnswers->contains($resultAnswer)) {
            $this->resultAnswers->add($resultAnswer);
            $resultAnswer->setTestResult($this);
        }

        return $this;
    }

    public function removeResultAnswer(TestResultAnswers $resultAnswer): static
    {
        if ($this->resultAnswers->removeElement($resultAnswer)) {
            // set the owning side to null (unless already changed)
            if ($resultAnswer->getTestResult() === $this) {
                $resultAnswer->setTestResult(null);
            }
        }

        return $this;
    }
}
