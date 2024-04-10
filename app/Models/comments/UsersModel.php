<?php

namespace App\Models\comments;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';

    protected $allowedFields = ['email', 'password', 'first_name', 'second_name'];

    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email]',
        'first_name' => 'required',
        'second_name' => 'required',
    ];

    protected $validationMessages = [
        'email' => [
            'valid_email' => 'Email некорректен',
            'is_unique' => 'Введенный email уже существует'
        ],
        'password' => [

        ]
    ];
}
