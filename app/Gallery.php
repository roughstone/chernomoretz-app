<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = [
        'category', 'date', 'photos', 'title', 'description'
    ];

    public function galleryPhotos()
    {
        return $this->hasMany(Photo::class);
    }
}
