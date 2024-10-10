<?php

namespace App\Services;

use App\Entity\TestResult;
use Doctrine\ORM\EntityManagerInterface;

readonly class QuizTokenService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function generateToken(): string
    {
        return md5($this->entityManager->getRepository(TestResult::class)->count());
    }
}