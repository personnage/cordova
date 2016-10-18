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
        'location', 'place_id', 'location_type',
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
        $lat = 'ST_X(location) as latitude';
        $lng = 'ST_Y(location) as longitude';

        return parent::newQuery()->addSelect(
            '*',
            DB::raw(join(',', compact('lat', 'lng')))
        );
    }

    /**
    * Set the locations.
    *
    * @param  array  $value Lat and lng array.
    * @return void
    */
    public function setLocationAttribute(array $value)
    {
        list($latitude, $longitude) = array_values($value);

       $this->attributes['location'] = DB::raw(
           "ST_GeomFromEWKT('SRID=4326;POINT({$latitude} {$longitude})')"
       );
    }
}
