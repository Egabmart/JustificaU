<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Importar BelongsTo

class Justificacion extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'student_name',
    'student_id',
    'clase',
    'grupo',
    'fecha',
    'hora_inicio',
    'hora_fin',
    'reason',
    'constancia_path',
    'status',
    'rejection_reason',
    ];  

    /**
     * Una justificación pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}