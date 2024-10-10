<?php

namespace App\Form;

use App\Entity\Question;
use App\Services\QuizTokenService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public const QUESTION_PREFIX = 'question_';

    public function __construct(
        private readonly QuizTokenService $quizTokenService
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var \App\Entity\Question $question */
        foreach ($options['questions'] as $question) {
            $builder
                ->add(self::QUESTION_PREFIX.$question->getId(), ChoiceType::class, [
                    'label' => $question->getStatement(),
                    'choices' => $this->getAnswersOptions($question),
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2 select2-mount-single select2-w-50 float le',
                    ],
                ]);
        }

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Check the results',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('quizToken', HiddenType::class, [
                'data' => $this->getQuizToken(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'questions' => [],
        ]);
    }

    private function getAnswersOptions(Question $question): array
    {
        $answers = [];

        foreach ($question->getAnswers() as $answer) {
            $answers[$answer->getValue()] = $answer->getId();
        }

        return $answers;
    }

    private function getQuizToken(): string
    {
        return $this->quizTokenService->generateToken();
    }
}
