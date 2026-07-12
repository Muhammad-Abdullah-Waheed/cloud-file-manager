<?php

namespace App\Http\Controllers;

use App\Features\GetTrashedItemsFeature;
use App\Features\PermanentDeleteFileFeature;
use App\Features\PermanentDeleteFolderFeature;
use App\Features\RestoreFileFeature;
use App\Features\RestoreFolderFeature;
use App\Models\File;
use App\Models\Folder;

class TrashController extends Controller
{
    public function index(GetTrashedItemsFeature $feature)
    {
        $items = $feature->handle(auth()->id());

        return view('trash.index', [
            'files' => $items['files'],
            'folders' => $items['folders'],
        ]);
    }

    public function restoreFile(File $file, RestoreFileFeature $feature)
    {
        $this->authorize('delete', $file);
        $feature->handle($file);

        return redirect()->back()->with('success', __('trash.file_restored'));
    }

    public function restoreFolder(Folder $folder, RestoreFolderFeature $feature)
    {
        $this->authorize('delete', $folder);
        $feature->handle($folder);

        return redirect()->back()->with('success', __('trash.folder_restored'));
    }

    public function destroyFile(File $file, PermanentDeleteFileFeature $feature)
    {
        $this->authorize('delete', $file);
        $feature->handle($file);

        return redirect()->back()->with('success', __('trash.file_permanently_deleted'));
    }

    public function destroyFolder(Folder $folder, PermanentDeleteFolderFeature $feature)
    {
        $this->authorize('delete', $folder);
        $feature->handle($folder);

        return redirect()->back()->with('success', __('trash.folder_permanently_deleted'));
    }
}
