<?php

namespace Database\Factories;

use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    private array $ukrainianFirstNames = [
        'Олександр', 'Андрій', 'Максим', 'Дмитро', 'Олексій',
        'Іван', 'Сергій', 'Артем', 'Микола', 'Владислав',
        'Олена', 'Марія', 'Анна', 'Юлія', 'Катерина',
        'Наталія', 'Ірина', 'Вікторія', 'Тетяна', 'Оксана',
    ];

    private array $ukrainianLastNames = [
        'Шевченко', 'Коваленко', 'Бондаренко', 'Ткаченко', 'Мельник',
        'Кравченко', 'Олійник', 'Лисенко', 'Павленко', 'Гончаренко',
        'Савченко', 'Романенко', 'Зінченко', 'Марченко', 'Федоренко',
    ];

    private array $ukrainianCities = [
        'Київ', 'Львів', 'Харків', 'Одеса', 'Дніпро',
        'Запоріжжя', 'Вінниця', 'Полтава', 'Черкаси', 'Суми',
        'Чернігів', 'Житомир', 'Рівне', 'Луцьк', 'Тернопіль',
        'Хмельницький', 'Івано-Франківськ', 'Ужгород', 'Чернівці', 'Миколаїв',
    ];

    public function definition(): array
    {
        return [
            'number' => Student::generateNumber(),
            'name' => $this->faker->randomElement($this->ukrainianFirstNames),
            'surname' => $this->faker->randomElement($this->ukrainianLastNames),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '+380'.str_pad($this->faker->numberBetween(500000000, 999999999), 9, '0', STR_PAD_LEFT),
            'city' => $this->faker->randomElement($this->ukrainianCities),
            'birthday' => $this->faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'phone_verified_at' => null,
        ]);
    }

    public function emailVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
            'phone_verified_at' => null,
        ]);
    }

    public function phoneVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'phone_verified_at' => now(),
        ]);
    }

    public function withoutContactDetails(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => null,
            'surname' => null,
            'city' => null,
            'birthday' => null,
        ]);
    }

    public function recentlyCreated(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => now()->subDays($this->faker->numberBetween(0, 7)),
        ]);
    }
}
