<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Сертифікат {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 0;
            size: 792pt 612pt;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 15px;
            background: white;
        }
        .certificate-outer {
            background: white;
            border: 4px solid #14b8a6;
            border-radius: 16px;
            padding: 8px;
        }
        .certificate-inner {
            border: 2px solid #99f6e4;
            border-radius: 12px;
            padding: 20px 30px 15px;
            text-align: center;
        }
        .logo {
            font-size: 18px;
            font-weight: bold;
            color: #14b8a6;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .header {
            font-size: 36px;
            font-weight: bold;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 3px;
        }
        .subtitle {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 15px;
        }
        .divider {
            width: 80px;
            height: 3px;
            background: #14b8a6;
            margin: 0 auto 15px;
        }
        .label {
            font-size: 12px;
            color: #94a3b8;
            font-style: italic;
            margin-bottom: 5px;
        }
        .name {
            font-size: 26px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .course-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px;
        }
        .course-name {
            font-size: 18px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 12px;
        }
        .teacher {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 15px;
        }
        .grade {
            margin-bottom: 18px;
        }
        .grade-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .grade-value {
            font-size: 22px;
            font-weight: bold;
            color: #14b8a6;
            letter-spacing: 1px;
        }
        .footer-line {
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 8px;
        }
        .details-table td {
            text-align: center;
            padding: 0 10px;
            width: 33.33%;
        }
        .detail-label {
            font-size: 10px;
            color: #94a3b8;
            margin-bottom: 2px;
        }
        .detail-value {
            font-size: 12px;
            color: #334155;
            font-weight: bold;
        }
        .company {
            font-size: 11px;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .verification {
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="certificate-outer">
        <div class="certificate-inner">
            <div class="logo">LIFESCANEDUCATION</div>
            <div class="header">Сертифікат</div>
            <div class="subtitle">про успішне завершення курсу</div>
            <div class="divider"></div>
            <div class="label">Цей сертифікат підтверджує, що</div>
            <div class="name">{{ $certificate->student->name }} {{ $certificate->student->surname }}</div>
            <div class="course-label">успішно завершив(-ла) курс</div>
            <div class="course-name">«{{ $certificate->course->name }}»</div>
            <div class="teacher">Викладач: {{ $certificate->course->teacher?->full_name ?? 'Не вказано' }}</div>
            <div class="grade">
                <div class="grade-label">Оцінка</div>
                <div class="grade-value">{{ $certificate->grade_level->label() }}</div>
            </div>

            <div class="footer-line">
                <table class="details-table">
                    <tr>
                        <td>
                            <div class="detail-label">Тривалість навчання</div>
                            <div class="detail-value">{{ $certificate->formatted_study_hours }}</div>
                        </td>
                        <td>
                            <div class="detail-label">Дата видачі</div>
                            <div class="detail-value">{{ $certificate->formatted_issued_at }}</div>
                        </td>
                        <td>
                            <div class="detail-label">Номер сертифіката</div>
                            <div class="detail-value">{{ $certificate->certificate_number }}</div>
                        </td>
                    </tr>
                </table>
                <div class="company">LifeScanEducation</div>
                <div class="verification">Перевірити: {{ config('app.url') }}/verify/{{ $certificate->certificate_number }}</div>
            </div>
        </div>
    </div>
</body>
</html>
