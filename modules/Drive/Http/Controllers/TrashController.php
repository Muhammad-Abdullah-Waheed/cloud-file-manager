<?php

namespace Modules\Drive\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Drive\Features\GetTrashedItemsFeature;
use Modules\Drive\Features\PermanentDeleteFileFeature;
use Modules\Drive\Features\PermanentDeleteFolderFeature;
use Modules\Drive\Features\RestoreFileFeature;
use Modules\Drive\Features\RestoreFolderFeature;
use Modules\Drive\Models\File;
use Modules\Drive\Models\Folder;

class TrashController extends Controller
{
    public function index(GetTrashedItemsFeature $feature)
    {
        $items = $feature->handle(auth()->id());

        return view('drive::trash.index', [
            'files' => $items['files'],
            'folders' => $items['folders'],
        ]);
    }

    public function restoreFile(File $file, RestoreFileFeature $feature)
    {
        $feature->handle($file);

        return redirect()->back()->with('success', __('drive::trash.file_restored'));
    }

    public function restoreFolder(Folder $folder, RestoreFolderFeature $feature)
    {
        $feature->handle($folder);

        return redirect()->back()->with('success', __('drive::trash.folder_restored'));
    }

    public function destroyFile(File $file, PermanentDeleteFileFeature $feature)
    {
        $feature->handle($file);

        return redirect()->back()->with('success', __('drive::trash.file_permanently_deleted'));
    }

    public function destroyFolder(Folder $folder, PermanentDeleteFolderFeature $feature)
    {
        $feature->handle($folder);

        return redirect()->back()->with('success', __('drive::trash.folder_permanently_deleted'));
    }
}
