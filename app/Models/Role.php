<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name'];
    protected $with = ['permissions'];

    public static function findByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);

    }

    /**
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
