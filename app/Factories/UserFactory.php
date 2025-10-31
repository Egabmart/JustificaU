<?php

namespace App\Factories;

use App\Models\User;
use App\Models\Users\Admin;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use InvalidArgumentException;

class UserFactory
{
    /**
     * Map de tipos de usuario disponibles y sus clases asociadas.
     */
    protected const TYPE_MAP = [
        User::ROLE_ADMIN => Admin::class,
        User::ROLE_TEACHER => Teacher::class,
        User::ROLE_STUDENT => Student::class,
    ];

    /**
     * Crea una nueva instancia de usuario sin guardarla en la base de datos.
     */
    public static function make(string $type, array $attributes = []): User
    {
        $userClass = self::resolveClass($type);

        /** @var User $user */
        $user = new $userClass();
        $user->fill($attributes);

        return $user;
    }

    /**
     * Crea y persiste un usuario en la base de datos.
     */
    public static function create(string $type, array $attributes = []): User
    {
        $user = self::make($type, $attributes);
        $user->save();

        return $user;
    }

    /**
     * Obtiene la clase asociada a un tipo de usuario.
     */
    protected static function resolveClass(string $type): string
    {
        $normalizedType = strtolower($type);

        if (! array_key_exists($normalizedType, self::TYPE_MAP)) {
            throw new InvalidArgumentException("Tipo de usuario '{$type}' no soportado.");
        }

        return self::TYPE_MAP[$normalizedType];
    }
}