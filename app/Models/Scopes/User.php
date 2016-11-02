<?php

namespace App\Models\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait User
{
    /**
     * Scope a query to only include latest users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int                                    $backDays
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest(Builder $query, int $backDays = 1)
    {
        return $query->where('created_at', '>', Carbon::now()->subDays($backDays));
    }

    /**
     * Scope a query to only include admins users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins(Builder $query)
    {
        return $query->whereAdmin(true);
    }

    /**
     * Scope a query to only include admins users or deleted users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $filterName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, $filterName)
    {
        switch ($filterName) {
            case 'admins':
                return $query->admins();
            case 'deleted':
                return $query->onlyTrashed();
            default:
                return $query;
        }
    }

    /**
     * Scope a query to only include users match to name or email attr.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $name)
    {
        return $query->where('name', 'ILIKE', "%$name%")
                  ->orWhere('email', 'ILIKE', "%$name%");
    }

    /**
     * Scope a query apply sort to query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $sortName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort(Builder $query, $sortName)
    {
        switch ($sortName) {
            case 'name_asc':
                return $query->orderBy('name', 'asc');
            case 'recent_sign_in':
                return $query->orderBy('last_sign_in_at', 'desc');
            case 'oldest_sign_in':
                return $query->orderBy('last_sign_in_at', 'asc');
            case 'id_desc':
                return $query->orderBy('id', 'desc');
            case 'id_asc':
                return $query->orderBy('id', 'asc');
            case 'updated_desc':
                return $query->orderBy('updated_at', 'desc');
            case 'updated_asc':
                return $query->orderBy('updated_at', 'asc');
            default:
                return $query;
        }
    }
}
