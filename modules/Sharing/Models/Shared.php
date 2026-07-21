<?php

namespace Modules\Sharing\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Shared extends Model
{
    protected $table = 'folder_file_shares';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'permission',
        'shared_type',
        'shared_id',
    ];

    /**
     * @param  array<string, mixed>  $data
     */
    public static function storeShare(array $data): self
    {
        return static::query()->create($data);
    }

    public static function findShareRecord(int $senderId, int $receiverId, string $type, int $id): ?self
    {
        return static::query()
            ->where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('shared_type', $type)
            ->where('shared_id', $id)
            ->first();
    }

    /**
     * @return Collection<int, Shared>
     */
    public static function forReceiver(int $userId): Collection
    {
        return static::where('receiver_id', $userId)
            ->with('sender')
            ->get();
    }

    public static function updatePermissionById(int $shareId, string $permission): void
    {
        static::where('id', $shareId)->update(['permission' => $permission]);
    }

    public static function revokeById(int $shareId): void
    {
        static::findOrFail($shareId)->delete();
    }

    public static function findRecordById(int $shareId): ?self
    {
        return static::query()->find($shareId);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class);
    }

    public function shared()
    {
        return $this->morphTo();
    }
}
