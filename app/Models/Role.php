<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    /**
     * Override the users relationship to maintain compatibility with Spatie
     * while also providing our custom relationship
     */
    public function users(): BelongsToMany
    {
        return parent::users();
    }

    /**
     * Custom relationship for users assigned directly via role_id
     */
    public function directUsers(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
