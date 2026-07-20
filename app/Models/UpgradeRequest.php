<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

class UpgradeRequest extends Model
{
    protected $fillable = [
        'requester_id',
        'reason',
        'status',
        'actioned_by',
        'actioned_at',
    ];

    protected $casts = [
        'actioned_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function actionedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actioned_by');
    }

    /**
     * @param  Builder<UpgradeRequest>  $query
     * @return Builder<UpgradeRequest>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function storeRequest(array $data): self
    {
        return static::query()->create($data);
    }

    public static function existsPendingFor(int $requesterId): bool
    {
        return static::query()
            ->where('requester_id', $requesterId)
            ->pending()
            ->exists();
    }

    public function markApproved(int $adminId): void
    {
        $this->update([
            'status'      => 'approved',
            'actioned_by' => $adminId,
            'actioned_at' => now(),
        ]);
    }

    public function markRejected(int $adminId): void
    {
        $this->update([
            'status'      => 'rejected',
            'actioned_by' => $adminId,
            'actioned_at' => now(),
        ]);
    }

    public static function pendingPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return static::with('requester')
            ->pending()
            ->latest()
            ->paginate($perPage);
    }
}
