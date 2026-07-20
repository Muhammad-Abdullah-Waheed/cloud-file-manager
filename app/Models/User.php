<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'storage_limit',
        'storage_used',
    ];

    protected $with = ['role'];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return BelongsTo<Role, User>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user belongs to a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role->name === $roleName;
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role->permissions->contains('name', $permission);
    }

    public static function findByEmailAddress(string $email): ?self
    {
        return static::where('email', $email)->first();
    }

    public static function searchByTerm(?string $term, int $perPage = 20): LengthAwarePaginator
    {
        return static::with('role')
            ->when($term, fn ($q) => $q->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            }))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public static function addStorageUsed(int $userId, int $bytes): void
    {
        static::where('id', $userId)->increment('storage_used', $bytes);
    }

    public static function removeStorageUsed(int $userId, int $bytes): void
    {
        static::where('id', $userId)->decrement('storage_used', $bytes);
    }

    /**
     * @return Collection<int, User>
     */
    public static function admins(): Collection
    {
        return static::whereHas('role', fn ($q) => $q->where('name', 'admin'))->get();
    }
}
