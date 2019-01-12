<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'title', 'comment', 'mon', 'tue', 'wen', 'thr', 'fri', 'sat', 'sun'
    ];
}
