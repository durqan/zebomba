<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSessions extends Model
{
    use HasFactory;

    protected $table = 'users_sessions';

    protected $fillable =
        [
            'access_token'
        ];
}
