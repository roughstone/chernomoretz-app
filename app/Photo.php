<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';

    protected $fillable = [
        'title', 'gallery_id', 'photos'
    ];

    public function gallery() {
        return $this->belongsTo(Gallery::class);
    }
}
