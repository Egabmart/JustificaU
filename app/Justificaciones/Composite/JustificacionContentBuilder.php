<?php

namespace App\Justificaciones\Composite;

use App\Models\Justificacion;
use Illuminate\Support\Carbon;

class JustificacionContentBuilder
{
    public function fromJustificacion(
        Justificacion $justificacion,
        array $attachments = [],
        array $comments = [],
        array $sections = []
    ): JustificacionComposite {
        $root = new JustificacionComposite(
            sprintf('Justificación de %s', $justificacion->student_name ?? 'Estudiante'),
            [
                'id' => $justificacion->getKey(),
                'status' => $justificacion->status,
                'status_label' => $justificacion->statusLabel(),
            ]
        );

        $root->add($this->buildStudentSection($justificacion));
        $root->add($this->buildClassSection($justificacion));
        $root->add($this->buildStatusSection($justificacion));
        $root->add($this->buildReasonComment($justificacion));

        if ($justificacion->rejection_reason) {
            $root->add($this->buildRejectionComment($justificacion));
        }

        if ($justificacion->constancia_path) {
            $root->add(new AttachmentComponent(
                'Constancia Adjunta',
                [
                    'file_name' => basename($justificacion->constancia_path),
                    'path' => $justificacion->constancia_path,
                    'url' => asset('storage/' . $justificacion->constancia_path),
                    'description' => 'Documento proporcionado por el estudiante.',
                ]
            ));
        }

        foreach ($sections as $section) {
            $root->add($this->normalizeSection($section));
        }

        foreach ($attachments as $attachment) {
            $root->add($this->normalizeAttachment($attachment));
        }

        foreach ($comments as $comment) {
            $root->add($this->normalizeComment($comment));
        }

        return $root;
    }

    protected function buildStudentSection(Justificacion $justificacion): SectionComponent
    {
        return new SectionComponent(
            'Datos del Estudiante',
            [
                'items' => [
                    [
                        'label' => 'Nombre',
                        'value' => $justificacion->student_name,
                    ],
                    [
                        'label' => 'Carnet (CIF)',
                        'value' => $justificacion->student_id,
                    ],
                    [
                        'label' => 'Carrera',
                        'value' => optional($justificacion->user)->carrera,
                    ],
                ],
            ]
        );
    }

    protected function buildClassSection(Justificacion $justificacion): SectionComponent
    {
        $horaInicio = $this->formatTime($justificacion->hora_inicio);
        $horaFin = $this->formatTime($justificacion->hora_fin);
        $fecha = $this->formatDate($justificacion->fecha);

        return new SectionComponent(
            'Detalles de la Clase',
            [
                'items' => [
                    [
                        'label' => 'Clase',
                        'value' => $justificacion->clase,
                    ],
                    [
                        'label' => 'Grupo',
                        'value' => $justificacion->grupo,
                    ],
                    [
                        'label' => 'Profesor',
                        'value' => $justificacion->profesor ?? 'No asignado',
                    ],
                    [
                        'label' => 'Fecha',
                        'value' => $fecha,
                    ],
                    [
                        'label' => 'Horario',
                        'value' => trim(sprintf('%s - %s', $horaInicio, $horaFin), ' -'),
                    ],
                ],
            ]
        );
    }

    protected function buildStatusSection(Justificacion $justificacion): SectionComponent
    {
        return new SectionComponent(
            'Estado de la Solicitud',
            [
                'items' => [
                    [
                        'label' => 'Estado Actual',
                        'value' => $justificacion->statusLabel(),
                    ],
                    [
                        'label' => 'Última Actualización',
                        'value' => optional($justificacion->updated_at)->format('d/m/Y H:i'),
                    ],
                ],
            ]
        );
    }

    protected function buildReasonComment(Justificacion $justificacion): CommentComponent
    {
        return new CommentComponent(
            'Motivo del Estudiante',
            [
                'text' => $justificacion->reason,
                'author' => $justificacion->student_name,
                'created_at' => optional($justificacion->created_at)->format('d/m/Y H:i'),
            ]
        );
    }

    protected function buildRejectionComment(Justificacion $justificacion): CommentComponent
    {
        return new CommentComponent(
            'Motivo del Rechazo',
            [
                'text' => $justificacion->rejection_reason,
                'author' => 'Administrador',
                'created_at' => optional($justificacion->updated_at)->format('d/m/Y H:i'),
            ]
        );
    }

    protected function normalizeAttachment(mixed $attachment): AttachmentComponent
    {
        if ($attachment instanceof AttachmentComponent) {
            return $attachment;
        }

        if (is_string($attachment)) {
            return new AttachmentComponent(
                basename($attachment),
                [
                    'file_name' => basename($attachment),
                    'path' => $attachment,
                    'url' => asset('storage/' . ltrim($attachment, '/')),
                ]
            );
        }

        $payload = [
            'file_name' => $attachment['file_name'] ?? $attachment['name'] ?? 'Archivo',
            'path' => $attachment['path'] ?? null,
            'url' => $attachment['url'] ?? null,
            'description' => $attachment['description'] ?? null,
        ];

        return new AttachmentComponent($attachment['label'] ?? $payload['file_name'], $payload);
    }

    protected function normalizeComment(mixed $comment): CommentComponent
    {
        if ($comment instanceof CommentComponent) {
            return $comment;
        }

        if (is_string($comment)) {
            return new CommentComponent('Comentario', ['text' => $comment]);
        }

        return new CommentComponent(
            $comment['label'] ?? 'Comentario',
            [
                'text' => $comment['text'] ?? '',
                'author' => $comment['author'] ?? null,
                'created_at' => $comment['created_at'] ?? null,
            ]
        );
    }

    protected function normalizeSection(mixed $section): SectionComponent
    {
        if ($section instanceof SectionComponent) {
            return $section;
        }

        $sectionComponent = new SectionComponent(
            $section['label'] ?? 'Sección',
            [
                'items' => $section['items'] ?? [],
            ]
        );

        foreach ($section['children'] ?? [] as $child) {
            $sectionComponent->add($this->normalizeChild($child));
        }

        return $sectionComponent;
    }

    protected function normalizeChild(mixed $child): JustificacionComponent
    {
        if ($child instanceof JustificacionComponent) {
            return $child;
        }

        $type = $child['type'] ?? 'comment';

        return match ($type) {
            'attachment' => $this->normalizeAttachment($child),
            'section' => $this->normalizeSection($child),
            default => $this->normalizeComment($child),
        };
    }

    protected function formatTime(mixed $time): ?string
    {
        if (empty($time)) {
            return null;
        }

        $carbon = $this->castToCarbon($time);

        return $carbon?->format('H:i');
    }

    protected function formatDate(mixed $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        $carbon = $this->castToCarbon($date);

        return $carbon?->format('d/m/Y');
    }

    protected function castToCarbon(mixed $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        if (is_string($value) || is_int($value)) {
            try {
                return Carbon::parse($value);
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }
}