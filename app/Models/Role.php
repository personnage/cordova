<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes, Scopes\Role;

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
    protected $fillable = ['name', 'label'];

    /**
     * Get permissions to currently role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Detect if role contain given permission(s).
     *
     * $role->hasPermission('update-post') or $role->hasPermission($collections)
     *
     * @param  \Illuminate\Database\Eloquent\Collection|string  $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }

        return (bool) $permission->intersect($this->permissions)->count();
    }

    /**
     * Give permission to currently role.
     *
     * @param  \App\Models\Permission  $permission
     * @return \App\Models\Permission
     */
    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

    /**
     * Revoke permission to roles.
     *
     * @param  \App\Models\Permission  $permission
     * @return int
     */
    public function revokePermissionTo(Permission $permission)
    {
        return $this->permissions()->detach($permission->id);
    }
}
