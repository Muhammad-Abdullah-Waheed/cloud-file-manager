<?php

namespace Modules\Drive\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Drive\Features\DeleteFileFeature;
use Modules\Drive\Features\DownloadFileFeature;
use Modules\Drive\Features\UploadFileFeature;
use Modules\Billing\Exceptions\StorageQuotaExceededException;
use Modules\Drive\Http\Requests\UploadFileRequest;
use Modules\Drive\Models\File;

class FileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(UploadFileRequest $request, UploadFileFeature $feature)
    {
        try {
            $feature->handle(
                $request->file('file'),
                auth()->id(),
                $request->validated()['parent_id'] ?? null,
            );
        } catch (StorageQuotaExceededException $e) {
            return redirect()->back()->with('quota', $e->toArray());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('drive::file.uploaded'));
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file, DownloadFileFeature $feature)
    {
        return $feature->handle($file);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file, DeleteFileFeature $feature)
    {
        $feature->handle($file);

        return redirect()->back()->with('success', __('drive::file.deleted'));
    }
}
