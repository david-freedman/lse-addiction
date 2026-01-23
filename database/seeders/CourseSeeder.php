<?php

namespace Database\Seeders;

use App\Domains\Course\Enums\CourseLabel;
use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Teacher\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $teachers = Teacher::all();

        Course::create([
            'name' => 'Сучасні методи діагностики та лікування гострого коронарного синдрому',
            'slug' => Str::slug('suchasni-metody-diagnostyky-gks'),
            'number' => '1014782',
            'description' => 'Поглиблений курс з кардіології, що охоплює найновіші протоколи діагностики ГКС, інтерпретацію ЕКГ, біомаркери пошкодження міокарда та сучасні підходи до реваскularizації. Інтерактивні клінічні випадки та практичні рекомендації від провідних кардіологів України.',
            'price' => 4500.00,
            'old_price' => 6000.00,
            'discount_percentage' => 25,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'upcoming',
            'starts_at' => now()->addDays(7),
            'label' => CourseLabel::MasterClass->value,
            'banner' => 'https://images.unsplash.com/photo-1628348068343-c6a848d2b6dd?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Неврологічні ускладнення цукрового діабету: від діагностики до терапії',
            'slug' => Str::slug('nevrologichni-uskladnennya-diabetu'),
            'number' => '1014783',
            'description' => 'Комплексний огляд діабетичної нейропатії, автономних порушень та когнітивних розладів при ЦД. Розглядаються сучасні діагностичні критерії, диференційна діагностика та доказові методи лікування. Особлива увага приділяється мультидисциплінарному підходу до ведення пацієнтів.',
            'price' => 1.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'recorded',
            'starts_at' => now()->subDays(15),
            'label' => CourseLabel::Recorded->value,
            'banner' => 'https://images.unsplash.com/photo-1559757175-5700dde675bc?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Малоінвазивна хірургія: лапароскопічні та ендоскопічні техніки',
            'slug' => Str::slug('maloinvazyvna-khirurhiya'),
            'number' => '1014784',
            'description' => 'Практичний курс з оволодіння сучасними малоінвазивними хірургічними методиками. Детальний розбір показань, протипоказань, технічних аспектів та можливих ускладнень. Включає відеодемонстрації операцій та розбір складних клінічних випадків.',
            'price' => 2,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'upcoming',
            'starts_at' => now()->addDays(14),
            'label' => CourseLabel::PracticalTraining->value,
            'banner' => 'https://images.unsplash.com/photo-1551076805-e1869033e561?q=80&w=2064',
        ]);

        Course::create([
            'name' => 'Педіатрія невідкладних станів: алгоритми надання допомоги',
            'slug' => Str::slug('pediatriya-nevidkladnykh-staniv'),
            'number' => '1014785',
            'description' => 'Систематизований підхід до невідкладних станів у дітей різного віку. Охоплює респіраторні кризи, судомні стани, анафілаксію, травми та отруєння. Акцент на швидкій оцінці стану, алгоритмах прийняття рішень та практичних навичках реанімації.',
            'price' => 3800.00,
            'old_price' => 4500.00,
            'discount_percentage' => 15,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'upcoming',
            'starts_at' => now()->addDays(10),
            'label' => CourseLabel::Intensive->value,
            'banner' => 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?q=80&w=2091',
        ]);

        Course::create([
            'name' => 'Сучасна імунотерапія в онкології: персоналізований підхід',
            'slug' => Str::slug('imunoterapiya-v-onkolohiyi'),
            'number' => '1014786',
            'description' => 'Актуальні дані про застосування чекпоінт-інгібіторів, CAR-T клітинної терапії та таргетних препаратів у лікуванні солідних пухлин та гематологічних захворювань. Розбір біомаркерів відповіді на терапію, управління імун-опосередкованими побічними ефектами та комбінованих стратегій лікування.',
            'price' => 5600.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'recorded',
            'starts_at' => now()->subDays(20),
            'label' => CourseLabel::Recorded->value,
            'banner' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Ендокринологія репродуктивної системи: від діагностики до лікування',
            'slug' => Str::slug('endokrynolohiya-reproduktyvnoyi-systemy'),
            'number' => '1014787',
            'description' => 'Комплексний курс з репродуктивної ендокринології, що включає синдром полікістозних яєчників, гіперпролактинемію, гіпотиреоз та його вплив на фертильність. Сучасні підходи до стимуляції овуляції, корекції гормональних порушень та підготовки до вагітності.',
            'price' => 4200.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'upcoming',
            'starts_at' => now()->addDays(21),
            'label' => CourseLabel::OnlineCourse->value,
            'banner' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Когнітивно-поведінкова терапія в лікуванні депресивних розладів',
            'slug' => Str::slug('kohnityvno-povedinkova-terapiya'),
            'number' => '1014788',
            'description' => 'Практичний курс з КПТ для психіатрів та психотерапевтів. Основні техніки когнітивної реструктуризації, поведінкової активації та роботи з автоматичними думками. Структуровані протоколи терапії великого депресивного розладу з доказовою базою ефективності.',
            'price' => 2800.00,
            'old_price' => 3500.00,
            'discount_percentage' => 20,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'recorded',
            'starts_at' => now()->subDays(5),
            'label' => CourseLabel::VideoCourse->value,
            'banner' => 'https://images.unsplash.com/photo-1527689368864-3a821dbccc34?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Регіонарна анестезія в ортопедії та травматології',
            'slug' => Str::slug('rehionarna-anesteziya'),
            'number' => '1014789',
            'description' => 'Сучасні техніки провідникової та нейроаксіальної анестезії для ортопедичних втручань. УЗД-навігація при виконанні блокад, вибір місцевих анестетиків, попередження та лікування ускладнень. Мультимодальна аналгезія в периопераційному періоді.',
            'price' => 6800.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'upcoming',
            'starts_at' => now()->addDays(28),
            'label' => CourseLabel::MasterClass->value,
            'banner' => 'https://images.unsplash.com/photo-1516549655169-df83a0774514?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Інтерпретація КТ та МРТ: нейрорадіологія',
            'slug' => Str::slug('interpretatsiya-kt-mrt'),
            'number' => '1014790',
            'description' => 'Систематичний підхід до інтерпретації нейровізуалізації. Детальний розбір патологічних змін при інсультах, пухлинах ЦНС, демієлінізуючих захворюваннях та травмах. Практичні навички читання знімків, складання протоколів дослідження та клінічні кореляції.',
            'price' => 5200.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'recorded',
            'starts_at' => now()->subDays(12),
            'label' => CourseLabel::Recorded->value,
            'banner' => 'https://images.unsplash.com/photo-1530497610245-94d3c16cda28?q=80&w=2064',
        ]);

        Course::create([
            'name' => 'Антибіотикорезистентність: раціональна антибіотикотерапія 2025',
            'slug' => Str::slug('antybiotikorezystentnist-2025'),
            'number' => '1014791',
            'description' => 'Актуальні дані про механізми резистентності, епідеміологію мультирезистентних збудників в Україні та світі. Принципи антибіотичного стюардшипу, де-ескалаційна терапія, комбінації антибіотиків. Клінічні рекомендації для лікування інфекцій різних локалізацій з урахуванням локальних даних резистентності.',
            'price' => 3600.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => 'active',
            'type' => 'upcoming',
            'starts_at' => now()->addDays(18),
            'label' => CourseLabel::OnlineCourse->value,
            'banner' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=2025',
        ]);

        Course::create([
            'name' => 'Ультразвукова діагностика серця (чернетка)',
            'slug' => Str::slug('uzd-sertsya-chernetka'),
            'number' => '1014792',
            'description' => 'Курс в процесі розробки. Буде охоплювати базові та розширені техніки ехокардіографії.',
            'price' => 4000.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => CourseStatus::Draft->value,
            'type' => 'upcoming',
            'starts_at' => now()->addDays(60),
            'label' => CourseLabel::OnlineCourse->value,
            'banner' => 'https://images.unsplash.com/photo-1628348068343-c6a848d2b6dd?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Основи клінічної фармакології (архів)',
            'slug' => Str::slug('osnovy-klinichnoyi-farmakologiyi-arkhiv'),
            'number' => '1014793',
            'description' => 'Архівний курс з клінічної фармакології. Доступний для перегляду записів.',
            'price' => 2500.00,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => CourseStatus::Archived->value,
            'type' => 'recorded',
            'starts_at' => now()->subMonths(6),
            'label' => CourseLabel::VideoCourse->value,
            'banner' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?q=80&w=2070',
        ]);

        Course::create([
            'name' => 'Тестовий прихований курс',
            'slug' => Str::slug('testovyi-prykhovanyi-kurs'),
            'number' => '1014794',
            'description' => 'Прихований курс для тестування функціоналу.',
            'price' => 0,
            'teacher_id' => $teachers->random()->id,
            'author_id' => $users->random()->id,
            'status' => CourseStatus::Hidden->value,
            'type' => 'recorded',
            'starts_at' => now()->subDays(30),
            'label' => CourseLabel::Recorded->value,
            'banner' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070',
        ]);
    }
}
