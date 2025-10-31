<?php

namespace App\Models\Users;

use App\Models\User;

class Admin extends User
{
    protected $table = 'users';

    protected $attributes = [
        'role' => User::ROLE_ADMIN,
    ];
}