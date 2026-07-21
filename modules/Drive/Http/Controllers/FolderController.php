<?php

namespace Modules\Drive\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Drive\Features\CreateFolderFeature;
use Modules\Drive\Features\DeleteFolderFeature;
use Modules\Drive\Features\GetFolderContentsFeature;
use Modules\Drive\Features\RenameFolderFeature;
use Modules\Drive\Http\Requests\CreateFolderRequest;
use Modules\Drive\Http\Requests\RenameFolderRequest;
use Modules\Drive\Models\Folder;

class FolderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFolderRequest $request, CreateFolderFeature $feature)
    {
        $feature->handle($request->validated(), auth()->id());

        return redirect()->back()->with('success', __('drive::folder.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RenameFolderRequest $request, Folder $folder, RenameFolderFeature $feature)
    {
        $feature->handle($folder->id, $request->validated()['name']);

        return redirect()->back()->with('success', __('drive::folder.renamed'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder, DeleteFolderFeature $feature)
    {
        $feature->handle($folder->id);

        return redirect()->back()->with('success', __('drive::folder.deleted'));
    }

    public function show(Folder $folder, GetFolderContentsFeature $feature)
    {
        $contents = $feature->handle($folder, auth()->id());

        return view('drive::dashboard.index', [
            'folders'       => $contents['folders'],
            'files'         => $contents['files'],
            'ancestors'     => $contents['ancestors'],
            'currentFolder' => $folder,
        ]);
    }
}
