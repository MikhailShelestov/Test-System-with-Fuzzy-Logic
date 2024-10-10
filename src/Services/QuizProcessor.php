<?php

namespace App\Services;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\TestResult;
use App\Entity\TestResultAnswers;
use App\Form\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class QuizProcessor
{
    private const INITIAL_SCORE = 0;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected CacheInterface $cache,
        protected QuizTokenService $quizTokenService,
    ) {
    }

    public function proceedQuizResults(array $quizResults): void
    {
        $testResult = new TestResult();
        $testResult->setToken($quizResults['quizToken']);

        $quizScore = self::INITIAL_SCORE;

        $questionWithAnswersList = array_filter($quizResults, function ($answers, $key) {
            return str_contains($key, QuizType::QUESTION_PREFIX);
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($questionWithAnswersList as $questionKey => $givenAnswers) {
            $question = $this->getQuestionFromKey($questionKey);
            $this->saveQuestionResultAnswers($question, $testResult, $givenAnswers);

            if (!$givenAnswers) {
                $this->saveQuestionResultAnswer($question, $testResult);
                continue;
            }

            $rightAnswers = $question->getAnswers()->filter(fn(Answer $answer) => $answer->isRightAnswer());

            $answerSets = $this->generateAnswerSets(
                array_values(
                    $rightAnswers->map(fn(Answer $answer) => $answer->getId())->toArray()
                )
            );


            $quizScore += $this->calculateScore($givenAnswers, $rightAnswers, $answerSets);
        }

        $testResult->setScore($quizScore);

        $this->entityManager->persist($testResult);
        $this->entityManager->flush();
    }


    private function getQuestionList(): array
    {
        $questions = $this->entityManager->getRepository(Question::class)->findAll();

        return array_combine(
            array_map(fn($question) => $question->getId(), $questions),
            $questions
        );
    }

    private function getQuestionFromKey(string $questionKey): Question
    {
        $questionId = substr($questionKey, strlen(QuizType::QUESTION_PREFIX));

        return $this->getQuestionList()[$questionId];
    }

    private function generateAnswerSets(array $rightAnswers): array
    {
        // To store all generated subsets
        $subsets = [];
        $totalOptions = count($rightAnswers);

        // Use bitwise masks to generate subsets
        for ($mask = 1; $mask < (1 << $totalOptions); $mask++) {
            $currentSubset = [];

            // Check each bit of the mask to determine which elements to include
            for ($bitPosition = 0; $bitPosition < $totalOptions; $bitPosition++) {
                if ($mask & (1 << $bitPosition)) {
                    $currentSubset[] = $rightAnswers[$bitPosition];
                }
            }

            $subsets[] = $currentSubset;
        }

        return $subsets;
    }

    private function saveQuestionResultAnswers(Question $question, TestResult $testResult, array $givenAnswers): void
    {
        foreach ($givenAnswers as $answerId) {
            $answer = $this->entityManager->getRepository(Answer::class)->find($answerId);
            $this->saveQuestionResultAnswer($question, $testResult, $answer);
        }
    }

    private function saveQuestionResultAnswer(Question $question, TestResult $testResult, ?Answer $answer = null): void
    {
        $resultAnswer = new TestResultAnswers();
        $resultAnswer->setQuestion($question);
        $resultAnswer->setAnswer($answer);

        $this->entityManager->persist($resultAnswer);

        $testResult->addResultAnswer($resultAnswer);
    }

    private function calculateScore($givenAnswers, $rightAnswers, $answerSets): float
    {
        return in_array($givenAnswers, $answerSets)
            ? (count($givenAnswers) / count($rightAnswers))
            : self::INITIAL_SCORE;
    }
}