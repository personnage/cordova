<?php declare(strict_types=1);

namespace App\Models;

class Like extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     *  Get all of the owning likeable models.
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Get author (user) to currently like.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
