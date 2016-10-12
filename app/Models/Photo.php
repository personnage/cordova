<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'provider', 'extern_id',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        // Update some attributes before creating.
        static::creating(function (Photo $photo) {
            $photo->label = $photo->label ?? str_random(10);
        });
    }

    /**
     * Get owner (user) to currently photo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the location to photo.
     */
    public function location()
    {
        return $this->hasOne(PhotoLocation::class);
    }

    /**
     * Get all of the photos comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all of the photos likes.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Scope a query to only include photos match to title or desc attr.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $name): Builder
    {
        return $query->where('title', 'ILIKE', "%$name%")
             ->orWhere('description', 'ILIKE', "%$name%");
    }

    /**
     * Scope a query apply sort to query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $sortName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort(Builder $query, string $sortName): Builder
    {
        switch ($sortName) {
            case 'name_asc':
                return $query->orderBy('name', 'asc');
            case 'updated_asc':
                return $query->orderBy('updated_at', 'asc');
            case 'updated_desc':
                return $query->orderBy('updated_at', 'desc');
            case 'id_asc':
                return $query->orderBy('id', 'asc');
            case 'id_desc':
            default:
                return $query->orderBy('id', 'desc');
        }
    }
}
