<?php

namespace Database\Seeders;

use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
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

        $surveyLessons = Lesson::where('type', LessonType::Survey)->get();
        foreach ($surveyLessons as $lesson) {
            $quiz = Quiz::create([
                'quizzable_type' => Lesson::class,
                'quizzable_id' => $lesson->id,
                'title' => null,
                'passing_score' => 0,
                'max_attempts' => null,
                'time_limit_minutes' => null,
                'show_correct_answers' => false,
                'is_survey' => true,
            ]);
            $quizCount++;

            $questionCount += $this->createSurveyQuestions($quiz);
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
            [
                'text' => 'Оберіть зображення, що відповідає нормальній картині:',
                'type' => QuestionType::ImageSelect,
                'answers' => [
                    ['text' => 'Норма', 'is_correct' => true, 'image' => 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=200'],
                    ['text' => 'Патологія А', 'is_correct' => false, 'image' => 'https://images.unsplash.com/photo-1530497610245-94d3c16cda28?w=200'],
                    ['text' => 'Патологія Б', 'is_correct' => false, 'image' => 'https://images.unsplash.com/photo-1516549655169-df83a0774514?w=200'],
                ],
            ],
            [
                'text' => 'Розмістіть етапи діагностики в правильному порядку:',
                'type' => QuestionType::Ordering,
                'answers' => [
                    ['text' => '1. Збір анамнезу', 'is_correct' => true, 'order' => 1],
                    ['text' => '2. Фізикальне обстеження', 'is_correct' => true, 'order' => 2],
                    ['text' => '3. Лабораторні дослідження', 'is_correct' => true, 'order' => 3],
                    ['text' => '4. Інструментальні методи', 'is_correct' => true, 'order' => 4],
                ],
            ],
            [
                'text' => 'Перетягніть симптоми до відповідних категорій захворювань:',
                'type' => QuestionType::DragDrop,
                'answers' => [
                    ['text' => 'Гострий біль', 'is_correct' => true, 'category' => 'Гострий стан'],
                    ['text' => 'Тупий біль', 'is_correct' => true, 'category' => 'Хронічний стан'],
                    ['text' => 'Раптовий початок', 'is_correct' => true, 'category' => 'Гострий стан'],
                    ['text' => 'Поступовий розвиток', 'is_correct' => true, 'category' => 'Хронічний стан'],
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
                    'answer_image' => $answerData['image'] ?? null,
                    'is_correct' => $answerData['is_correct'],
                    'category' => $answerData['category'] ?? null,
                    'order' => $answerData['order'] ?? $answerOrder + 1,
                ]);
            }
        }

        return $createdCount;
    }

    private function createSurveyQuestions(Quiz $quiz): int
    {
        $surveyQuestions = [
            [
                'text' => 'Оцініть якість матеріалу модуля',
                'type' => QuestionType::SingleChoice,
                'answers' => [
                    ['text' => '⭐ 1 - Погано', 'is_correct' => true],
                    ['text' => '⭐⭐ 2 - Незадовільно', 'is_correct' => true],
                    ['text' => '⭐⭐⭐ 3 - Задовільно', 'is_correct' => true],
                    ['text' => '⭐⭐⭐⭐ 4 - Добре', 'is_correct' => true],
                    ['text' => '⭐⭐⭐⭐⭐ 5 - Відмінно', 'is_correct' => true],
                ],
            ],
            [
                'text' => 'Що найбільше сподобалось у цьому модулі?',
                'type' => QuestionType::MultipleChoice,
                'answers' => [
                    ['text' => 'Якість відеоматеріалів', 'is_correct' => true],
                    ['text' => 'Практичні приклади', 'is_correct' => true],
                    ['text' => 'Структура подачі матеріалу', 'is_correct' => true],
                    ['text' => 'Додаткові матеріали', 'is_correct' => true],
                ],
            ],
            [
                'text' => 'Чи порекомендували б ви цей курс колегам?',
                'type' => QuestionType::SingleChoice,
                'answers' => [
                    ['text' => 'Так, обов\'язково', 'is_correct' => true],
                    ['text' => 'Скоріше так', 'is_correct' => true],
                    ['text' => 'Можливо', 'is_correct' => true],
                    ['text' => 'Скоріше ні', 'is_correct' => true],
                    ['text' => 'Ні', 'is_correct' => true],
                ],
            ],
        ];

        $createdCount = 0;

        foreach ($surveyQuestions as $i => $template) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'type' => $template['type'],
                'question_text' => $template['text'],
                'question_image' => null,
                'order' => $i + 1,
                'points' => 0,
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
