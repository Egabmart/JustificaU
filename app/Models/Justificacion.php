<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Importar BelongsTo

use App\States\Justificacion\AprobadaState;
use App\States\Justificacion\CreadaState;
use App\States\Justificacion\EnviadaState;
use App\States\Justificacion\ExpiradaState;
use App\States\Justificacion\JustificacionState;
use App\States\Justificacion\RechazadaState;

class Justificacion extends Model
{
    use HasFactory;

    public const STATUS_CREADA = 'CREADA';
    public const STATUS_ENVIADA = 'ENVIADA';
    public const STATUS_APROBADA = 'APROBADA';
    public const STATUS_RECHAZADA = 'RECHAZADA';
    public const STATUS_EXPIRADA = 'EXPIRADA';

    protected $fillable = [
    'user_id',
    'student_name',
    'student_id',
    'clase',
    'grupo',
    'profesor',
    'fecha',
    'hora_inicio',
    'hora_fin',
    'reason',
    'constancia_path',
    'status',
    'rejection_reason',
    ];

    protected $attributes = [
        'status' => self::STATUS_CREADA,
    ];  

    /**
     * Una justificación pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Obtiene la instancia del estado actual de la justificación.
     */
    public function state(): JustificacionState
    {
        $status = $this->status ?? self::STATUS_CREADA;

        $stateClass = static::stateMap()[$status] ?? CreadaState::class;

        return app($stateClass, ['justificacion' => $this]);
    }

    /**
     * Mapa entre estados y sus clases correspondientes.
     *
     * @return array<string, class-string<JustificacionState>>
     */
    public static function stateMap(): array
    {
        return [
            self::STATUS_CREADA => CreadaState::class,
            self::STATUS_ENVIADA => EnviadaState::class,
            self::STATUS_APROBADA => AprobadaState::class,
            self::STATUS_RECHAZADA => RechazadaState::class,
            self::STATUS_EXPIRADA => ExpiradaState::class,
        ];
    }

    /**
     * Etiquetas legibles de los estados.
     *
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_CREADA => 'Creada',
            self::STATUS_ENVIADA => 'Enviada',
            self::STATUS_APROBADA => 'Aprobada',
            self::STATUS_RECHAZADA => 'Rechazada',
            self::STATUS_EXPIRADA => 'Expirada',
        ];
    }

    /**
     * Clases CSS recomendadas para mostrar badges de estado.
     *
     * @return array<string, string>
     */
    public static function statusBadgeClasses(): array
    {
        return [
            self::STATUS_CREADA => 'bg-gray-200 text-gray-700',
            self::STATUS_ENVIADA => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APROBADA => 'bg-green-100 text-green-800',
            self::STATUS_RECHAZADA => 'bg-red-100 text-red-800',
            self::STATUS_EXPIRADA => 'bg-gray-300 text-gray-800',
        ];
    }

    public function statusLabel(): string
    {
        return static::statusLabels()[$this->status] ?? ucfirst(strtolower((string) $this->status));
    }

    public function statusBadgeClass(): string
    {
        return static::statusBadgeClasses()[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}