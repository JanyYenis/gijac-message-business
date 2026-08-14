<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ReporteCampanaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $rutaExcel,       // ruta relativa en storage, ej: reportes-campanas/xxx.xlsx
        public int $totalContactos,
        public float $promedioApertura,
        public string $nivelGeneral,
        public ?array $analisisIa = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte de predicción de campaña — ' . now()->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.campanas.reporte-campana',
            with: [
                'totalContactos' => $this->totalContactos,
                'promedioApertura' => $this->promedioApertura,
                'nivelGeneral' => $this->nivelGeneral,
                'explicacion' => $this->analisisIa['explicacion'] ?? null,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('local', $this->rutaExcel)
                ->as('reporte-campana-' . now()->format('Y-m-d-His') . '.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
