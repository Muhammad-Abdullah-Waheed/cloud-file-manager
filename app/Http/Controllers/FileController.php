<?php

namespace App\Http\Controllers;

use App\Features\DeleteFileFeature;
use App\Features\DownloadFileFeature;
use App\Features\UploadFileFeature;
use App\Http\Requests\UploadFileRequest;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UploadFileRequest $request, UploadFileFeature $feature)
    {
        $this->authorize('create', File::class);
        try {
            $feature->handle(
                $request->file('file'),
                auth()->id(),
                $request->validated()['parent_id'] ?? null,
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('file.uploaded'));
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file, DownloadFileFeature $feature)
    {
        $this->authorize('view', $file);

        return $feature->handle($file);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file, DeleteFileFeature $feature)
    {
        $this->authorize('delete', $file);
        $feature->handle($file);

        return redirect()->back()->with('success', __('file.deleted'));
    }
}
