<?php

namespace Modules\Drive\Features;

use Modules\Drive\Models\Folder;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

class CreateFolderFeature
{
    public function __construct(
        private FolderRepositoryInterface $folderRepository,
    ) {}

    public function handle(array $data, int $userId): Folder
    {
        return $this->folderRepository->create([
            'name' => $data['name'],
            'user_id' => $userId,
            'parent_id' => $data['parent_id'] ?? null,
        ]);
    }
}
