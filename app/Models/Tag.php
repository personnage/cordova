<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

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

    /**
     * Get author (user) to currently tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include tags match to name attr.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array                                 $names
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, array $names): Builder
    {
        return $query->whereRaw(join(' OR ', array_map(function (string $name): string {
            return "name ILIKE '%$name%'";
        }, $names)));
    }
}
