<?php declare(strict_types=1);

namespace App\Models;

use DB;
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

    /**
     * Get the user that owns the photo.
     *
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
}
