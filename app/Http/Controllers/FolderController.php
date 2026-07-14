<?php

namespace App\Http\Controllers;

use App\Features\CreateFolderFeature;
use App\Features\DeleteFolderFeature;
use App\Features\GetFolderContentsFeature;
use App\Features\RenameFolderFeature;
use App\Http\Requests\CreateFolderRequest;
use App\Http\Requests\RenameFolderRequest;
use App\Models\Folder;

class FolderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFolderRequest $request, CreateFolderFeature $feature)
    {
        $this->authorize('create', Folder::class);
        $folder = $feature->handle($request->validated(), auth()->id());

        return redirect()->back()->with('success', __('folder.created'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RenameFolderRequest $request, Folder $folder, RenameFolderFeature $feature)
    {
        $this->authorize('update', $folder);
        $feature->handle($folder->id, $request->validated()['name']);

        return redirect()->back()->with('success', __('folder.renamed'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder, DeleteFolderFeature $feature)
    {
        $this->authorize('delete', $folder);
        $feature->handle($folder->id);

        return redirect()->back()->with('success', __('folder.deleted'));
    }


public function show(Folder $folder, GetFolderContentsFeature $feature)
{
    $this->authorize('view', $folder);
    $contents = $feature->handle($folder, auth()->id());
    return view('dashboard.index', [
        'folders'       => $contents['folders'],
        'files'         => $contents['files'],
        'ancestors'     => $contents['ancestors'],
        'currentFolder' => $folder,
    ]);
}
}
