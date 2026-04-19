<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Project;
use App\Models\Organization;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateService
{
    /**
     * Генерация сертификата для опубликованного проекта
     */
    public function generateForProject(Project $project): Certificate
    {
        $year = now()->year;
        $number = $this->generateNumber($year);

        $certificate = Certificate::create([
            'number' => $number,
            'project_id' => $project->id,
            'organization_id' => $project->organization_id,
            'nomination_id' => $project->nomination_id,
            'year' => (string) $year,
            'issued_at' => now(),
            'expires_at' => now()->addYear(1),
        ]);

        $pdfPath = $this->generatePdf($certificate);
        $certificate->update(['pdf_path' => $pdfPath]);

        // QR-код ведёт на страницу проекта в каталоге
        $qrUrl = config('app.url') . "/catalog/{$project->id}";
        $qrPath = $this->generateQrCode($certificate->id, $qrUrl);
        $certificate->update(['qr_code' => $qrPath]);

        return $certificate;
    }

    /**
     * Генерация уникального номера сертификата
     */
    public function generateNumber(string $year): string
    {
        $lastCert = Certificate::where('year', $year)->orderByDesc('id')->first();
        $nextNum = $lastCert ? ((int) Str::afterLast(Str::before($lastCert->number, '-'), '-') + 1) : 1;
        return sprintf('CERT-%s-%05d', $year, $nextNum);
    }

    /**
     * Генерация PDF-сертификата
     */
    public function generatePdf(Certificate $certificate): string
    {
        $project = $certificate->project;
        $org = $certificate->organization;
        $nomination = $certificate->nomination;

        $data = [
            'certificateNumber' => $certificate->number,
            'organizationName' => $org->full_name,
            'projectTitle' => $project->title,
            'nominationName' => $nomination->name,
            'issuedDate' => $certificate->issued_at?->format('d.m.Y') ?? now()->format('d.m.Y'),
        ];

        $pdf = Pdf::loadView('pdf.certificate', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = "certificates/{$certificate->number}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Генерация QR-кода
     */
    public function generateQrCode(int $certificateId, string $url): string
    {
        $qrCode = QrCode::size(200)->format('png')->generate($url);
        $filename = "certificates/qr_{$certificateId}.png";
        Storage::disk('public')->put($filename, $qrCode);
        return $filename;
    }

    /**
     * Отзыв сертификата
     */
    public function revoke(Certificate $certificate, ?string $reason = null): void
    {
        $certificate->update([
            'is_revoked' => true,
            'revoke_reason' => $reason,
        ]);
    }

    /**
     * Поиск сертификата
     */
    public function findByNumber(string $number): ?Certificate
    {
        return Certificate::with(['organization', 'project', 'nomination'])
            ->where('number', $number)
            ->first();
    }
}
