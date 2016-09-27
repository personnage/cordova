<?php

namespace App\Models;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

    /**
     * Get all of the photos that are assigned this tag.
     */
    public function photos()
    {
        return $this->morphedByMany(Photo::class, 'taggable');
    }
}
