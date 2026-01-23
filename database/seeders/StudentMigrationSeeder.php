<?php

namespace Database\Seeders;

use App\Domains\Student\Enums\ConsentType;
use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentConsent;
use App\Domains\Student\Models\StudentProfileFieldValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['full_name' => 'Дворянова Тетяна Миколаївна', 'birthday' => '1982-09-03', 'email' => 'sputnik772008@gmail.com', 'education_level' => 'specialist', 'institution' => 'Луганський державний медичний університет', 'diploma_number' => 'АН №27897318', 'workplace' => 'ПП «Клініка Медіком»', 'position' => 'Лікар-рентгенолог', 'phone' => '+380505276637'],
            ['full_name' => 'Коваленко Ілона Сергіївна', 'birthday' => '1993-09-27', 'email' => 'milenonka@gmail.com', 'education_level' => 'specialist', 'institution' => 'Національний медичний університет ім ОО Богомольця', 'diploma_number' => 'С16 №052665', 'workplace' => 'Клініка "Меділенд"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380955630373'],
            ['full_name' => 'Черняков Павло Олександрович', 'birthday' => '1989-01-12', 'email' => 'pavel-chernyakov@ukr.net', 'education_level' => 'specialist', 'institution' => 'Харківський національний медичний університет', 'diploma_number' => 'ХА №43465907', 'workplace' => 'ТОВ МЕДІКАЛ 2021', 'position' => 'Лікар-рентгенолог', 'phone' => '+380661647625'],
            ['full_name' => 'Шеїна Уляна Іванівна', 'birthday' => '1982-05-08', 'email' => 'uisheina@gmail.com', 'education_level' => 'specialist', 'institution' => 'Івано-Франківський державний медичний університет', 'diploma_number' => 'ВА №27682881', 'workplace' => 'ТОВ Медікал 2021', 'position' => 'Лікар-рентгенолог', 'phone' => '+380954416399'],
            ['full_name' => 'Слуцька Анна Ігорівна', 'birthday' => '1983-07-28', 'email' => 'Annaslutskaya280783@gmail.com', 'education_level' => 'specialist', 'institution' => 'Українська медична стоматологiчна академiя', 'diploma_number' => 'ТА №30013445', 'workplace' => 'КП ПОКЛ ім. Скліфосовського ПОР', 'position' => 'Лікар-рентгенолог', 'phone' => '+380663235185'],
            ['full_name' => 'Бантюкова Тетяна Миколаївна', 'birthday' => '1976-03-15', 'email' => 'evaisobaki@gmail.com', 'education_level' => 'specialist', 'institution' => 'Луганський державний медичний університет', 'diploma_number' => 'АН №11066770', 'workplace' => 'Клініка "Меділенд"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380508600658'],
            ['full_name' => 'Заєць Наталія Юріівна', 'birthday' => '1981-05-25', 'email' => 'n.zayetz@gmail.com', 'education_level' => 'specialist', 'institution' => 'Кримський державний медичний університет', 'diploma_number' => 'KP №27659885', 'workplace' => 'МЦ «Добробут»', 'position' => 'Лікар-рентгенолог', 'phone' => '+380507400808'],
            ['full_name' => 'Виженко Ганна Віталіївна', 'birthday' => '1977-07-29', 'email' => 'annavyzhen@gmail.com', 'education_level' => 'specialist', 'institution' => 'Дніпропетровська державна медична академія', 'diploma_number' => 'НР №12535549', 'workplace' => 'КП 1 МКЛ ПМР', 'position' => 'Лікар-рентгенолог', 'phone' => '+380664157545'],
            ['full_name' => 'Савчук Яна Михайлівна', 'birthday' => '1993-04-25', 'email' => 'bagira199354@gmail.com', 'education_level' => 'specialist', 'institution' => 'Івано-Франківський національний медичний університет', 'diploma_number' => 'С16 №027147', 'workplace' => 'Діагностичний центр «Вітамін»', 'position' => 'Лікар-рентгенолог', 'phone' => '+380997332335'],
            ['full_name' => 'Сидор Марʼяна Михайлівна', 'birthday' => '1997-12-12', 'email' => 'marianka199777@gmail.com', 'education_level' => 'master', 'institution' => 'Тернопільський національний медичний університет ім. І.Я. Горбачевського', 'diploma_number' => 'М23 №029807', 'workplace' => 'ТКМЛ 2, ФК ВІТАМІН', 'position' => 'Лікар-рентгенолог', 'phone' => '+380982764522'],
            ['full_name' => 'Генсіцька Людмила Русланівна', 'birthday' => '1996-09-23', 'email' => '3091luv@gmail.com', 'education_level' => 'specialist', 'institution' => 'Івано-Франківський національний медичний університет', 'diploma_number' => 'С19 №016304', 'workplace' => 'CRG clinic', 'position' => 'Лікар-рентгенолог', 'phone' => '+380951472913'],
            ['full_name' => 'Попп Ольга Русланівна', 'birthday' => '1993-05-12', 'email' => 'olhapopp12@gmail.com', 'education_level' => 'specialist', 'institution' => 'Івано-Франківський національний медичний університет', 'diploma_number' => 'С16 №027109', 'workplace' => 'КНП ЦМКЛ м. Івано-Франківськ', 'position' => 'Лікар-рентгенолог', 'phone' => '+380507371994'],
            ['full_name' => 'Ковчун Віктор Юрійович', 'birthday' => '1989-08-19', 'email' => 'vu.kovchun@gmail.com', 'education_level' => 'specialist', 'institution' => 'Навчально-науковий медичний інститут СумДУ', 'diploma_number' => 'СМ №43183138', 'workplace' => 'Сумський державний університет медичний інститут', 'position' => 'Асистент кафедри онкології та радіології', 'phone' => '+380971136927'],
            ['full_name' => 'Кириченко Сергій Вікторович', 'birthday' => '1975-02-04', 'email' => 'kyrychenko.sergii@gmail.com', 'education_level' => 'master', 'institution' => 'Навчально-науковий медичний інститут СумДУ', 'diploma_number' => 'МА НХ №016236', 'workplace' => 'ТОВ "Медея Суми", МЦ "Медея"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380974383862'],
            ['full_name' => 'Коханчук Олена Григорівна', 'birthday' => '1975-05-11', 'email' => 'olenakokhanchuk@gmail.com', 'education_level' => 'master', 'institution' => 'Кримський державний медичний університет імені С. І. Георгієвського', 'diploma_number' => 'ЛВ В С №008004', 'workplace' => 'МЦ "Омега-Київ"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380667777059'],
            ['full_name' => 'Безугла Ірина Олександрівна', 'birthday' => '1992-05-14', 'email' => 'ogmiraira@gmail.com', 'education_level' => 'specialist', 'institution' => 'ДЗ "Дніпропетровська медична академія МОЗ України", ХМАПО', 'diploma_number' => 'С15 №020853', 'workplace' => 'КНП "Уманська ЦРЛ"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380955013638'],
            ['full_name' => 'Бондарь Аліна Миколаївна', 'birthday' => '1995-08-09', 'email' => 'bondaralina265@gmail.com', 'education_level' => 'specialist', 'institution' => 'Українська Медична Стоматологічна Академія', 'diploma_number' => 'СА №013665', 'workplace' => 'Полтавська обласна клінічна лікарня', 'position' => 'Лікар-рентгенолог', 'phone' => '+380932657269'],
            ['full_name' => 'Котлярова Марина Олександрівна', 'birthday' => '1993-01-23', 'email' => 'marynakotliarova@gmail.com', 'education_level' => 'specialist', 'institution' => 'Харківський національний медичний університет', 'diploma_number' => 'С16 №058934', 'workplace' => 'ДЦ «Іннова Діагностика»', 'position' => 'Лікар-рентгенолог', 'phone' => '+380664244388'],
            ['full_name' => 'Оробець Ірина Миколаївна', 'birthday' => '1971-09-08', 'email' => 'orobetsira@gmail.com', 'education_level' => 'specialist', 'institution' => 'Московська медична академія імені Сеченова', 'diploma_number' => 'ЕВ №144624', 'workplace' => 'МДЦ "ЛУР"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380674731956'],
            ['full_name' => 'Дросик Микола Миколайович', 'birthday' => '1989-07-31', 'email' => 'mdrosyk89@gmail.com', 'education_level' => 'specialist', 'institution' => 'Івано-Франківський Національний Медичний Університет', 'diploma_number' => 'ВА №43238285', 'workplace' => 'ТКМЛ №2', 'position' => 'Лікар-рентгенолог', 'phone' => '+380969348576'],
            ['full_name' => 'Шамова Тетяна Олександрівна', 'birthday' => '1956-02-24', 'email' => 'shamovatatyana@gmail.com', 'education_level' => 'specialist', 'institution' => 'Івано-Франківський Національний Медичний Університет', 'diploma_number' => 'Г-ІІ №228749', 'workplace' => 'МЦ "Омега-Київ"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380972470114'],
            ['full_name' => 'Щур Віталій Васильович', 'birthday' => '2001-03-24', 'email' => 'shchurvitaliy4@gmail.com', 'education_level' => 'master', 'institution' => 'Львівський Національний Медичний Університет', 'diploma_number' => 'М24 №079300', 'workplace' => 'КНП ЛОКДЦ', 'position' => 'Лікар-рентгенолог', 'phone' => '+380963938269'],
            ['full_name' => 'Крохмаль Костянтин Євгенович', 'birthday' => '1985-06-20', 'email' => 'constantine.krokhmal@gmail.com', 'education_level' => 'specialist', 'institution' => 'Луганський Державний Медичний Університет', 'diploma_number' => 'АН №350864439', 'workplace' => 'МЦ "Омега-Київ"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380506580836'],
            ['full_name' => 'Жибер Костянтин Олександрович', 'birthday' => '1990-06-16', 'email' => 'zhyberkos@gmail.com', 'education_level' => 'specialist', 'institution' => 'Луганський Державний Медичний Університет', 'diploma_number' => 'С16 №031496', 'workplace' => 'Рівненський обласний протипухлинний центр', 'position' => 'Лікар-рентгенолог', 'phone' => '+380505774797'],
            ['full_name' => 'Жук Яна Борисівна', 'birthday' => null, 'email' => 'yana2510@gmail.com', 'education_level' => 'specialist', 'institution' => 'Буковинська державна медична академія', 'diploma_number' => 'РН №24668022', 'workplace' => 'ТОВ МЦ "Асклепій Київ"', 'position' => 'Лікар-рентгенолог', 'phone' => '+380509860368'],
            ['full_name' => 'Пастушенко Валерія Романівна', 'birthday' => null, 'email' => 'valeriyapastushenko@gmail.com', 'education_level' => 'master', 'institution' => 'ВНМУ ім. М. І. Пирогова', 'diploma_number' => 'М22 №42915', 'workplace' => null, 'position' => 'Лікар-рентгенолог', 'phone' => '+380687394089'],
            ['full_name' => 'Стельмах Тетяна Андріївна', 'birthday' => null, 'email' => 'stelmakhtatyana@gmai.com', 'education_level' => 'specialist', 'institution' => 'НМУ імені О.О.Богомольця', 'diploma_number' => 'C22 №002599', 'workplace' => 'КНП КМКЛ№6', 'position' => 'Лікар-рентгенолог', 'phone' => '+380639894644'],
            ['full_name' => 'Мартинюк Ольга Володимирівна', 'birthday' => null, 'email' => 'martunyk.olia98@gmail.com', 'education_level' => 'specialist', 'institution' => 'ПДМУ', 'diploma_number' => 'М22 №045143', 'workplace' => 'Вишгородська ЦРЛ', 'position' => 'Лікар-рентгенолог', 'phone' => '+380961752742'],
            ['full_name' => 'Гнатів Марʼяна', 'birthday' => null, 'email' => 'mgnativ01@gmail.com', 'education_level' => 'specialist', 'institution' => 'Львівський медичний інститут', 'diploma_number' => 'М22 №046166', 'workplace' => 'Західноукраїнський спеціалізований дитячий медичний центр', 'position' => 'Лікар-рентгенолог', 'phone' => '+380963813260'],
            ['full_name' => 'Зоріна Вероніка Олегівна', 'birthday' => null, 'email' => 'bessrebrennikova1983@gmail.com', 'education_level' => 'master', 'institution' => 'ДДМА', 'diploma_number' => 'НР №27955007', 'workplace' => 'Слобожанська ЦРЛ', 'position' => 'Лікар-рентгенолог', 'phone' => '+380982435030'],
            ['full_name' => 'Федьо Марія', 'birthday' => null, 'email' => 'masha1001fp@gmail.com', 'education_level' => 'master', 'institution' => 'НМУ ім О.О. Богомольця', 'diploma_number' => 'М25 №050520', 'workplace' => 'УК "Оберіг"', 'position' => 'лікар-інтерн-радіолог', 'phone' => '+380996049515'],
            ['full_name' => 'Череда Яна Володимірівна', 'birthday' => null, 'email' => 'yanachereda160299@gmail.com', 'education_level' => 'master', 'institution' => 'ПДМУ', 'diploma_number' => 'М22 №045430', 'workplace' => 'ПОКЛ ім.М.В. Скліфосовського', 'position' => 'Лікар-рентгенолог', 'phone' => '+380994532252'],
            ['full_name' => 'Варіння Анастасія Сергіївна', 'birthday' => null, 'email' => 'sapronova67@gmail.com', 'education_level' => 'master', 'institution' => 'Харківський національний медичний університет', 'diploma_number' => 'M24 №071147', 'workplace' => 'Європейський радіологічний центр', 'position' => 'лікар-інтерн-радіолог', 'phone' => '+380985545922'],
            ['full_name' => 'Поліщук Анастасія Ігорівна', 'birthday' => null, 'email' => 'polischyk12@gmail.com', 'education_level' => 'master', 'institution' => 'Вінницький медичний університет імені М.І. Пирогова', 'diploma_number' => 'М25 №051165', 'workplace' => 'ДНП «ЛНМУ ім. Данила Галицького»', 'position' => 'лікар-інтерн-радіолог', 'phone' => '+380983534134'],
            ['full_name' => 'Пітула Софія Ростиславівна', 'birthday' => null, 'email' => 'sophiya.pitula@gmail.com', 'education_level' => 'master', 'institution' => 'ЛНМУ', 'diploma_number' => null, 'workplace' => null, 'position' => 'лікар-інтерн-радіолог', 'phone' => '+380970484997'],
            ['full_name' => 'Шнайдер Анастасія Олександрівна', 'birthday' => null, 'email' => 'germy12344@gmail.com', 'education_level' => 'specialist', 'institution' => 'ДВНЗ «Тернопільський національний медичний університет ім. І.Я. Горбачевського МОЗ України»', 'diploma_number' => 'С16 №042130', 'workplace' => 'ТОВ «Діагностичний центр Медекс»', 'position' => 'Лікар-рентгенолог', 'phone' => '+380930530686'],
            ['full_name' => 'Бородюх Марія Андріївна', 'birthday' => null, 'email' => 'marie_bor@icloud.com', 'education_level' => 'specialist', 'institution' => 'ЛНМУ', 'diploma_number' => 'М23 №035703', 'workplace' => 'Львівський обласний госпіталь ветеранів війни та репресованих', 'position' => 'Лікар-рентгенолог', 'phone' => '+380973111905'],
            ['full_name' => 'Костриця Оксана Іванівна', 'birthday' => null, 'email' => 'oksana.kostritsa@gmail.com', 'education_level' => 'specialist', 'institution' => 'НМУ ім Богомольця', 'diploma_number' => 'С17 №38874', 'workplace' => 'КНП КЛ 15 Подільського району міста Києва', 'position' => 'Лікар-рентгенолог', 'phone' => '+380637466379'],
            ['full_name' => 'Пестременко Наталя Миколаївна', 'birthday' => null, 'email' => 'natalie007187@gmail.com', 'education_level' => 'specialist', 'institution' => 'ХНМУ', 'diploma_number' => 'C21 №006089', 'workplace' => 'КНП "Міська клінічна багатопрофільна лікарня №17" Харківської міської ради', 'position' => 'Лікар-рентгенолог', 'phone' => '+380954493443'],
            ['full_name' => 'Гавриш Василь Іванович', 'birthday' => null, 'email' => 'gavrush0rtg@gmail.com', 'education_level' => 'specialist', 'institution' => 'ІФНМУ', 'diploma_number' => 'C17 №026050', 'workplace' => 'м24', 'position' => 'Лікар-рентгенолог', 'phone' => '+380989445274'],
            ['full_name' => 'Топіха Альона Володимирівна', 'birthday' => null, 'email' => 'topikha.aliona@gmail.com', 'education_level' => 'master', 'institution' => 'НМУ ім Богомольця', 'diploma_number' => 'М24 №066772', 'workplace' => 'ДНП Національний інститут раку', 'position' => 'лікар-інтерн-радіолог', 'phone' => '+380963600717'],
            ['full_name' => 'Кандимова Катерина Олегівна', 'birthday' => null, 'email' => 'kandymova.neuromed@gmail.com', 'education_level' => 'specialist', 'institution' => 'ВНМУ', 'diploma_number' => 'С20 №015477', 'workplace' => 'Нейромед', 'position' => 'Лікар-рентгенолог', 'phone' => '+380665989042'],
            ['full_name' => 'Михайлова Анастасія Олексіївна', 'birthday' => null, 'email' => 'nastya902012@gmail.com', 'education_level' => 'specialist', 'institution' => 'ЛНМУ ім. Данила Галицького', 'diploma_number' => 'ВК №45316882', 'workplace' => 'ТОВ мрт плюс', 'position' => 'Лікар-рентгенолог', 'phone' => '+380687356865'],
            ['full_name' => 'Бессараб Вікторія Вікторівна', 'birthday' => null, 'email' => 'vika-b-b@ukr.net', 'education_level' => 'specialist', 'institution' => 'ДонДМУ ім. М.Горького', 'diploma_number' => 'НК №28894558', 'workplace' => 'ДУ «ТМО МВС України по м. Києву та Київській області»', 'position' => 'Лікар-рентгенолог', 'phone' => '+380958365974'],
            ['full_name' => 'Розумняк Ангеліна Ільнурівна', 'birthday' => null, 'email' => 'buharova.angelinaa@gmail.com', 'education_level' => 'master', 'institution' => 'НМУ ім Богомольця', 'diploma_number' => 'МВ №5555555', 'workplace' => 'КМКЛ 18', 'position' => 'Лікар-рентгенолог', 'phone' => '+380956601738'],
            ['full_name' => 'Ванесса Федірко', 'birthday' => null, 'email' => 'vanessa.fed21@gmail.com', 'education_level' => 'master', 'institution' => 'НМУ ім Богомольця', 'diploma_number' => 'М23 №029406', 'workplace' => 'МКЛ 1', 'position' => 'лікар-інтерн хірург', 'phone' => '+380632558229'],
            ['full_name' => 'Чернушкіна Діана Андріївна', 'birthday' => null, 'email' => 'cernuskinadiana@gmail.com', 'education_level' => 'specialist', 'institution' => null, 'diploma_number' => null, 'workplace' => null, 'position' => 'Лікар-рентгенолог', 'phone' => '+380936494501'],
            ['full_name' => 'Гавриленко Аліна Русланівна', 'birthday' => null, 'email' => 'alonagavrilenko98@gmail.com', 'education_level' => 'specialist', 'institution' => 'ЗДМУ', 'diploma_number' => 'С21 №007385', 'workplace' => 'КНП "МЛ9" ЗМР', 'position' => 'Лікар-рентгенолог', 'phone' => '+380975339088'],
        ];

        $profileFields = DB::table('profile_fields')
            ->whereIn('key', ['education_level', 'institution', 'diploma_number', 'workplace', 'position'])
            ->pluck('id', 'key');

        $created = 0;
        $skipped = 0;

        foreach ($students as $data) {
            $nameParts = $this->parseFullName($data['full_name']);

            $student = Student::firstOrCreate(
                ['email' => mb_strtolower($data['email'])],
                [
                    'number' => Student::generateNumber(),
                    'phone' => $data['phone'],
                    'name' => $nameParts['name'],
                    'surname' => $nameParts['surname'],
                    'patronymic' => $nameParts['patronymic'],
                    'birthday' => $data['birthday'],
                    'email_verified_at' => now(),
                    'phone_verified_at' => now(),
                ]
            );

            if (!$student->wasRecentlyCreated) {
                $skipped++;
                continue;
            }

            $created++;

            $this->seedProfileFields($student, $data, $profileFields);
            $this->seedConsents($student);
        }

        $this->command->info("Migration complete: {$created} created, {$skipped} skipped (already existed)");
    }

    /**
     * @return array{surname: string, name: string, patronymic: string|null}
     */
    private function parseFullName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName));

        return [
            'surname' => $parts[0] ?? '',
            'name' => $parts[1] ?? '',
            'patronymic' => $parts[2] ?? null,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @param \Illuminate\Support\Collection<string, int> $profileFields
     */
    private function seedProfileFields(Student $student, array $data, $profileFields): void
    {
        $mappings = [
            'education_level' => $data['education_level'],
            'institution' => $data['institution'],
            'diploma_number' => $data['diploma_number'],
            'workplace' => $data['workplace'],
            'position' => $data['position'],
        ];

        foreach ($mappings as $key => $value) {
            if ($value === null || !isset($profileFields[$key])) {
                continue;
            }

            StudentProfileFieldValue::create([
                'student_id' => $student->id,
                'profile_field_id' => $profileFields[$key],
                'value' => $value,
            ]);
        }
    }

    private function seedConsents(Student $student): void
    {
        foreach (ConsentType::cases() as $type) {
            StudentConsent::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'consent_type' => $type->value,
                ],
                [
                    'ip_address' => '127.0.0.1',
                    'document_version' => '1.0',
                    'consented_at' => now(),
                ]
            );
        }
    }
}
