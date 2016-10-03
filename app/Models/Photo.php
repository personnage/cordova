<?php declare(strict_types=1);

namespace App\Models;

use DB;
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
        'title', 'description', 'provider', 'extern_id', 'location',
    ];

    protected $hidden = ['location'];

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
     * Get owner (user) to currently news.
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

    public function newQuery()
    {
        $x = 'ST_X(ST_AsText(location)) as latitude';
        $y = 'ST_Y(ST_AsText(location)) as longitude';

        return parent::newQuery()->addSelect('*', DB::raw(join(',', compact('x', 'y'))));
    }

    /**
     * Set the user's first name.
     *
     * @param  array  $value
     * @return void
     */
    public function setLocationAttribute(array $value)
    {
        list($latitude, $longitude) = array_values($value);

        $this->attributes['location'] = DB::raw(
            "ST_GeographyFromText('SRID=4326;POINT({$latitude} {$longitude})')"
        );
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
