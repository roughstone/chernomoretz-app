<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $table = 'coachs';

    protected $fillable = [
        'firstName', 'lastName', 'photos', 'birthday', 'description', 'position'
    ];
}
