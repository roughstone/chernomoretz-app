<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    protected $fillable = [
        'firstName', 'lastName', 'photos', 'birthday', 'description', 'sport'
    ];
}
