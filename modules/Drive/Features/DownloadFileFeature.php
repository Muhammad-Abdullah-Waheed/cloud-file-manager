<?php

namespace Modules\Drive\Features;

use Modules\Drive\Models\File;
use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
    ) {}

    public function handle(File $file): StreamedResponse
    {
        $version = $file->currentVersion;

        abort_if(! $version, 404);
        abort_if(! Storage::exists($version->path), 404);

        return Storage::download($version->path, $file->name);
    }
}
