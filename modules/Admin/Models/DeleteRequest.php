<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Pagination\LengthAwarePaginator;

class DeleteRequest extends Model
{
    protected $fillable = [
        'requester_id',
        'target_type',
        'target_id',
        'reason',
        'status',
        'actioned_by',
        'actioned_at',
    ];

    protected $casts = [
        'actioned_at' => 'datetime',
    ];

    /**
     * @param  array<string, mixed>  $data
     */
    public static function storeRequest(array $data): self
    {
        return static::query()->create($data);
    }

    public static function existsPendingFor(int $requesterId, string $targetType, int $targetId): bool
    {
        return static::where('requester_id', $requesterId)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
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
        return static::with(['requester', 'target', 'actionedBy'])
            ->pending()
            ->latest()
            ->paginate($perPage);
    }

    public static function historyForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return static::with(['target', 'actionedBy'])
            ->where('requester_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function actionedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actioned_by');
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}