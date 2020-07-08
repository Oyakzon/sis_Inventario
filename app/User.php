<?php

namespace sis_Inventario;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table='users';

    protected $primaryKey='id';
    
    protected $fillable = [
        'name', 'role', 'email', 'password','phone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
