<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rede extends Model
{
    protected $table = 'redes';

    protected $fillable = [
        'nome',
        'link',
        'password'
        ];
}
