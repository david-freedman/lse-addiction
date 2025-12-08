<?php

namespace Database\Seeders;

use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Enums\QuestionType;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Quiz\Models\QuizAnswer;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quizCount = 0;
        $questionCount = 0;

        $modules = Module::where('has_final_test', true)->get();
        foreach ($modules as $module) {
            $quiz = Quiz::create([
                'quizzable_type' => Module::class,
                'quizzable_id' => $module->id,
                'title' => 'Фінальний тест модуля',
                'passing_score' => 70,
                'max_attempts' => 3,
                'time_limit_minutes' => 30,
                'show_correct_answers' => true,
            ]);
            $quizCount++;

            $questionCount += $this->createQuestionsForQuiz($quiz, 5);
        }

        $quizLessons = Lesson::where('type', LessonType::Quiz)->get();
        foreach ($quizLessons as $lesson) {
            $quiz = Quiz::create([
                'quizzable_type' => Lesson::class,
                'quizzable_id' => $lesson->id,
                'title' => null,
                'passing_score' => 60,
                'max_attempts' => null,
                'time_limit_minutes' => 15,
                'show_correct_answers' => true,
            ]);
            $quizCount++;

            $questionCount += $this->createQuestionsForQuiz($quiz, 3);
        }

        $this->command->info("Created {$quizCount} quizzes with {$questionCount} questions");
    }

    private function createQuestionsForQuiz(Quiz $quiz, int $count): int
    {
        $questionTemplates = [
            [
                'text' => 'Який з наведених варіантів є правильним твердженням?',
                'type' => QuestionType::SingleChoice,
                'answers' => [
                    ['text' => 'Правильна відповідь', 'is_correct' => true],
                    ['text' => 'Неправильна відповідь A', 'is_correct' => false],
                    ['text' => 'Неправильна відповідь B', 'is_correct' => false],
                    ['text' => 'Неправильна відповідь C', 'is_correct' => false],
                ],
            ],
            [
                'text' => 'Оберіть усі правильні відповіді:',
                'type' => QuestionType::MultipleChoice,
                'answers' => [
                    ['text' => 'Правильна відповідь 1', 'is_correct' => true],
                    ['text' => 'Правильна відповідь 2', 'is_correct' => true],
                    ['text' => 'Неправильна відповідь', 'is_correct' => false],
                    ['text' => 'Ще одна неправильна', 'is_correct' => false],
                ],
            ],
            [
                'text' => 'Який основний принцип застосовується в даному випадку?',
                'type' => QuestionType::SingleChoice,
                'answers' => [
                    ['text' => 'Коректний принцип', 'is_correct' => true],
                    ['text' => 'Застарілий підхід', 'is_correct' => false],
                    ['text' => 'Некоректне твердження', 'is_correct' => false],
                ],
            ],
            [
                'text' => 'Визначте послідовність дій:',
                'type' => QuestionType::SingleChoice,
                'answers' => [
                    ['text' => 'Правильна послідовність', 'is_correct' => true],
                    ['text' => 'Помилкова послідовність A', 'is_correct' => false],
                    ['text' => 'Помилкова послідовність B', 'is_correct' => false],
                ],
            ],
            [
                'text' => 'Які симптоми характерні для даного стану?',
                'type' => QuestionType::MultipleChoice,
                'answers' => [
                    ['text' => 'Характерний симптом 1', 'is_correct' => true],
                    ['text' => 'Характерний симптом 2', 'is_correct' => true],
                    ['text' => 'Характерний симптом 3', 'is_correct' => true],
                    ['text' => 'Нехарактерний симптом', 'is_correct' => false],
                ],
            ],
        ];

        $createdCount = 0;

        for ($i = 0; $i < $count; $i++) {
            $template = $questionTemplates[$i % count($questionTemplates)];

            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'type' => $template['type'],
                'question_text' => $template['text'],
                'question_image' => null,
                'order' => $i + 1,
                'points' => $template['type'] === QuestionType::MultipleChoice ? 2 : 1,
            ]);
            $createdCount++;

            foreach ($template['answers'] as $answerOrder => $answerData) {
                QuizAnswer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['text'],
                    'answer_image' => null,
                    'is_correct' => $answerData['is_correct'],
                    'category' => null,
                    'order' => $answerOrder + 1,
                ]);
            }
        }

        return $createdCount;
    }
}
