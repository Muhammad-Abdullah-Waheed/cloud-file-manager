<?php

namespace App\Features;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class DownloadFileFeature
{
    public function __construct(
        private FileRepositoryInterface $files,
    ) {}

    public function handle(File $file): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $version = $file->currentVersion;

        abort_if(! $version, 404);
        abort_if(! Storage::exists($version->path), 404);

        return Storage::download($version->path, $file->name);
    }
}