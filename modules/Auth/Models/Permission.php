<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * @return BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany(Role::class);
    }
}
