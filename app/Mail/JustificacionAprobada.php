<?php

namespace App\Mail;

use App\Models\Justificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JustificacionAprobada extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * La instancia de la justificación.
     *
     * @var \App\Models\Justificacion
     */
    public $justificacion;

    /**
     * Crea una nueva instancia del mensaje.
     *
     * @param \App\Models\Justificacion $justificacion
     * @return void
     */
    public function __construct(Justificacion $justificacion)
    {
        $this->justificacion = $justificacion;
    }

    /**
     * Define el sobre del mensaje (asunto, etc.).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resolución de Justificación UAM',
        );
    }

    /**
     * Define el contenido del mensaje.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.justificacion_aprobada',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}