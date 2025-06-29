<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail; // <-- AÑADIR
use App\Mail\ReporteProfesor;       // <-- AÑADIR

class ReporteProfesor extends Mailable
{
    use Queueable, SerializesModels;

    public $justificacionesAprobadas;
    public $infoReporte;

    /**
     * Crea una nueva instancia del mensaje.
     *
     * @param \Illuminate\Support\Collection $justificacionesAprobadas
     * @param array $infoReporte
     * @return void
     */
    public function __construct($justificacionesAprobadas, $infoReporte)
    {
        $this->justificacionesAprobadas = $justificacionesAprobadas;
        $this->infoReporte = $infoReporte;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte de Justificaciones Aprobadas',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reporte_profesor',
        );
    }

    public function attachments(): array
    {
        return [];
    }

    
}