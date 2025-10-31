<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Importar HasMany

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_STUDENT = 'estudiante';
    public const ROLE_TEACHER = 'docente';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'cif',
        'facultad',
        'carrera',
        'role',
    ];

    protected $attributes = [
        'role' => self::ROLE_STUDENT,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Un usuario puede tener muchas justificaciones.
     */
    public function justificaciones(): HasMany
    {
        return $this->hasMany(Justificacion::class);
    }
}