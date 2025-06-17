<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'student_id',
        'clase', // <--- AÑADIR
        'grupo', // <--- AÑADIR
        'hora',  // <--- AÑADIR
        'reason',
        'start_date',
        'end_date',
        'constancia_path', // <--- AÑADIR
        'status',
    ];
}