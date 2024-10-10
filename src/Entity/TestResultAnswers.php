<?php

namespace App\Entity;

use App\Repository\TestResultAnswersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestResultAnswersRepository::class)]
class TestResultAnswers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Answer $answer = null;

    #[ORM\ManyToOne(inversedBy: 'resultAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TestResult $testResult = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getTestResult(): ?TestResult
    {
        return $this->testResult;
    }

    public function setTestResult(?TestResult $testResult): static
    {
        $this->testResult = $testResult;

        return $this;
    }
}
