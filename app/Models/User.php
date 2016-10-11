<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, Notifiable;

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
        'name', 'email', 'password', 'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The casts attributes.
     *
     * @var array
     */
    protected $casts = ['admin' => 'boolean'];

    /**
     * Set the user's created_by_id attr.
     *
     * @param  int|static  $value
     * @return void
     */
    public function setCreatedByIdAttribute($value)
    {
        if ($value instanceof static) {
            $this->attributes['created_by_id'] = $value->id;
        } else {
            $this->attributes['created_by_id'] = $value;
        }
    }

    /**
     * Get a user record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creator()
    {
        return $this->hasOne(static::class, 'id', 'created_by_id');
    }

    /**
     * Get a users records associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spawn()
    {
        return $this->hasMany(static::class, 'created_by_id', 'id');
    }

    /**
     * Get a tags associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Scope a query to only include users match to name, username or email attr.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $name)
    {
        $pattern = "%$name%";

        return $query->where('username', 'ILIKE', $pattern)
                      ->orWhere('email', 'ILIKE', $pattern)
                       ->orWhere('name', 'ILIKE', $pattern)
        ;
    }

    /**
     * Find user by credentials.
     *
     * @param  string    $username Email address or username.
     * @return User|null
     */
    public function findForPassport(string $username)
    {
        if (filter_var($username, \FILTER_VALIDATE_EMAIL)) {
            return static::where('email', $username)->first();
        }

        return static::where('username', $username)->first();
    }

    public function owns($related)
    {
        return $this->id == $related->user_id;
    }
}
