<?php

namespace Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Billing\Features\ApproveUpgradeFeature;
use Modules\Billing\Features\RejectUpgradeFeature;
use Modules\Billing\Features\RequestUpgradeFeature;
use Modules\Billing\Models\UpgradeRequest;
use Modules\Billing\Repositories\Interfaces\UpgradeRequestRepositoryInterface;
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
            __($sent ? 'billing::upgrade.requested' : 'billing::upgrade.already_pending'),
        );
    }

    public function index(UpgradeRequestRepositoryInterface $requests): View
    {
        return view('billing::upgrade-requests', [
            'requests' => $requests->getPendingPaginated(),
        ]);
    }

    public function approve(UpgradeRequest $upgradeRequest, ApproveUpgradeFeature $feature): RedirectResponse
    {
        abort_unless($upgradeRequest->isPending(), 422);

        $feature->handle($upgradeRequest, auth()->user());

        return back()->with('success', __('billing::upgrade.approved'));
    }

    public function reject(UpgradeRequest $upgradeRequest, RejectUpgradeFeature $feature): RedirectResponse
    {
        abort_unless($upgradeRequest->isPending(), 422);

        $feature->handle($upgradeRequest, auth()->user());

        return back()->with('success', __('billing::upgrade.rejected'));
    }
}
