<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeTrashedItems extends Command
{
    protected $signature   = 'trash:purge';
    protected $description = 'Permanently delete items trashed more than 30 days ago';

    public function handle(): void
    {
        // Files trashed more than 30 days ago
        File::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->with('versions')
            ->get()
            ->each(function (File $file) {
                foreach ($file->versions as $version) {
                    if (Storage::exists($version->path)) {
                        Storage::delete($version->path);
                    }
                }
                $file->forceDelete();
            });

        // Folders trashed more than 30 days ago
        Folder::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->get()
            ->each(fn (Folder $folder) => $folder->forceDelete());

        $this->info('Trashed items purged successfully.');
    }
}