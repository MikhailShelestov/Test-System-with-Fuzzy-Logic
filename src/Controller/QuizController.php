<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuizType;
use App\Services\QuizProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    public function __construct(
        private readonly QuizProcessor $quizProcessor,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'app_quiz')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(
            QuizType::class,
            null,
            [
                'questions' => $this->getQuestionList(),
                'method' => 'POST',
                'action' => $this->generateUrl('app_quiz'),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->quizProcessor->proceedQuizResults($data);

            return $this->redirectToRoute('app_quiz_results', ['quizToken' => $data['quizToken']]);
        }

        return $this->render('quiz/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getQuestionList(): array
    {
        return $this->entityManager->getRepository(Question::class)->findAll();
    }
}
