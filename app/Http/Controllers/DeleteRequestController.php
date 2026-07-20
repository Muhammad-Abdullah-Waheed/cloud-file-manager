<?php

namespace App\Http\Controllers;

use App\Features\ApproveDeleteRequestFeature;
use App\Features\GetDeleteRequestsFeature;
use App\Features\RejectDeleteRequestFeature;
use App\Features\RequestDeletionFeature;
use App\Models\DeleteRequest;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;

class DeleteRequestController extends Controller
{
    public function index(GetDeleteRequestsFeature $feature)
    {
        $result = $feature->handle(auth()->user());

        return view('admin.delete-requests', $result);
    }

    public function store(Request $request, RequestDeletionFeature $feature)
    {
        $request->validate([
            'reason'      => ['required', 'string', 'min:10', 'max:1000'],
            'target_type' => ['required', 'in:file,folder'],
            'target_id'   => ['required', 'integer'],
        ]);

        $target = match ($request->target_type) {
            'file'   => File::findOrFail($request->target_id),
            'folder' => Folder::findOrFail($request->target_id),
        };

        $submitted = $feature->handle(auth()->user(), $target, $request->reason);

        if (! $submitted) {
            return back()->with('error', __('admin.delete_request_already_pending'));
        }

        return back()->with('success', __('admin.delete_request_sent'));
    }

    public function approve(DeleteRequest $deleteRequest, ApproveDeleteRequestFeature $feature)
    {
        abort_unless($deleteRequest->isPending(), 422);

        $feature->handle($deleteRequest, auth()->user());

        return back()->with('success', __('admin.delete_request_approved'));
    }

    public function reject(DeleteRequest $deleteRequest, RejectDeleteRequestFeature $feature)
    {
        abort_unless($deleteRequest->isPending(), 422);

        $feature->handle($deleteRequest, auth()->user());

        return back()->with('success', __('admin.delete_request_rejected'));
    }
}