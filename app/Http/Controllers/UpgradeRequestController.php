<?php

namespace App\Http\Controllers;

use App\Features\ApproveUpgradeFeature;
use App\Features\RejectUpgradeFeature;
use App\Features\RequestUpgradeFeature;
use App\Models\UpgradeRequest;
use App\Repositories\Interfaces\UpgradeRequestRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpgradeRequestController extends Controller
{
    public function store(Request $request, RequestUpgradeFeature $feature): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $sent = $feature->handle(auth()->user(), $data['reason'] ?? null);

        return back()->with(
            $sent ? 'success' : 'error',
            __($sent ? 'upgrade.requested' : 'upgrade.already_pending'),
        );
    }

    public function index(UpgradeRequestRepositoryInterface $requests): View
    {
        return view('admin.upgrade-requests', [
            'requests' => $requests->getPendingPaginated(),
        ]);
    }

    public function approve(UpgradeRequest $upgradeRequest, ApproveUpgradeFeature $feature): RedirectResponse
    {
        abort_unless($upgradeRequest->isPending(), 422);

        $feature->handle($upgradeRequest, auth()->user());

        return back()->with('success', __('upgrade.approved'));
    }

    public function reject(UpgradeRequest $upgradeRequest, RejectUpgradeFeature $feature): RedirectResponse
    {
        abort_unless($upgradeRequest->isPending(), 422);

        $feature->handle($upgradeRequest, auth()->user());

        return back()->with('success', __('upgrade.rejected'));
    }
}
