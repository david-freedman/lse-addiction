<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\Certificate\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

final class GenerateCertificatePdfAction
{
    public function __invoke(Certificate $certificate): Response
    {
        $certificate->load(['student', 'course.teacher']);

        $pdf = Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate,
        ])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        $filename = "certificate-{$certificate->certificate_number}.pdf";

        return $pdf->download($filename);
    }
}
