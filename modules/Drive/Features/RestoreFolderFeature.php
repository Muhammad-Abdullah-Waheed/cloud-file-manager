<?php

namespace Modules\Drive\Features;

use Modules\Drive\Models\Folder;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

class RestoreFolderFeature
{
    public function __construct(
        private FolderRepositoryInterface $folders,
    ) {}

    public function handle(Folder $folder): void
    {
        $this->folders->restore($folder->id);
    }
}
