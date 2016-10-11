<?php declare(strict_types=1);

namespace App\Models;

use DB;

class PhotoLocation extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city', 'location',
    ];

    protected $hidden = ['location'];

    /**
     * Get the photo that owns the location.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photo()
    {
       return $this->belongsTo(Photo::class);
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
}