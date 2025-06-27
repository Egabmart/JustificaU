<?php

namespace App\Mail;

use App\Models\Justificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JustificacionRechazada extends Mailable
{
    use Queueable, SerializesModels;

    public $justificacion;

    public function __construct(Justificacion $justificacion)
    {
        $this->justificacion = $justificacion;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resolución de Justificación UAM',
        );
    }

    public function content(): Content
    {
        // Apuntamos a la nueva vista de rechazo
        return new Content(
            view: 'emails.justificacion_rechazada',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}