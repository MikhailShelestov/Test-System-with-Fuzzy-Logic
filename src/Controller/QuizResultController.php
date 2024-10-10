<?php

namespace App\Controller;

use App\Entity\TestResult;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizResultController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/results', name: 'app_quiz_results')]
    public function __invoke(Request $request): Response
    {
        /** @var TestResult $testResult */
        $testResult = $this->entityManager->getRepository(TestResult::class)->findOneBy(
            ['token' => $request->get('quizToken')]
        );

        return $this->render('quiz/result.html.twig', [
            'score' => round($testResult->getScore(), 2),
            'correctQuestions' => $this->getCorrectQuestionsList($testResult),
            'incorrectQuestions' => $this->getIncorrectQuestionsList($testResult),
        ]);
    }

    private function getCorrectQuestionsList(TestResult $testResult): array
    {
        $questionsAndAnswers = $this->getQuizResultAnswersGrouped($testResult);

        return array_column(
            array_filter($questionsAndAnswers, function ($result) {
                return array_reduce($result['answers'], function ($carry, $answer) {
                    return $carry && $answer?->isRightAnswer();
                }, true);
            }),
            'question'
        );
    }

    private function getIncorrectQuestionsList(TestResult $testResult): array
    {
        $questionsAndAnswers = $this->getQuizResultAnswersGrouped($testResult);

        return array_column(
            array_filter($questionsAndAnswers, function ($result) {
                return array_reduce($result['answers'], function ($carry, $answer) {
                    return $carry || !$answer?->isRightAnswer();
                }, false);
            }),
            'question'
        );
    }

    private function getQuizResultAnswersGrouped(TestResult $testResult): array
    {
        $questionsAndAnswers = [];

        foreach ($testResult->getResultAnswers() as $resultAnswer) {
            $question = $resultAnswer->getQuestion();
            $questionsAndAnswers[$question->getId()]['question'] = $question;
            $questionsAndAnswers[$question->getId()]['answers'][] = $resultAnswer->getAnswer();
        }

        return $questionsAndAnswers;
    }
}
