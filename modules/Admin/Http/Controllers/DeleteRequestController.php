<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Admin\Features\ApproveDeleteRequestFeature;
use Modules\Admin\Features\GetDeleteRequestsFeature;
use Modules\Admin\Features\RejectDeleteRequestFeature;
use Modules\Admin\Features\RequestDeletionFeature;
use Modules\Admin\Models\DeleteRequest;
use Modules\Auth\Models\User;
use Modules\Admin\Repositories\Interfaces\DeleteRequestRepositoryInterface;
use Illuminate\Http\Request;
use Modules\Drive\Models\File;
use Modules\Drive\Models\Folder;

class DeleteRequestController extends Controller
{
    public function index(GetDeleteRequestsFeature $feature)
    {
        $result = $feature->handle(auth()->user());

        return view('admin::delete-requests', $result);
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
            return back()->with('error', __('admin::admin.delete_request_already_pending'));
        }

        return back()->with('success', __('admin::admin.delete_request_sent'));
    }

    public function approve(DeleteRequest $deleteRequest, ApproveDeleteRequestFeature $feature)
    {
        abort_unless($deleteRequest->isPending(), 422);

        $feature->handle($deleteRequest, auth()->user());

        return back()->with('success', __('admin::admin.delete_request_approved'));
    }

    public function reject(DeleteRequest $deleteRequest, RejectDeleteRequestFeature $feature)
    {
        abort_unless($deleteRequest->isPending(), 422);

        $feature->handle($deleteRequest, auth()->user());

        return back()->with('success', __('admin::admin.delete_request_rejected'));
    }
}