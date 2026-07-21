<?php

namespace Modules\Sharing\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Sharing\Features\RevokeShareFeature;
use Modules\Sharing\Features\ShareItemFeature;
use Modules\Sharing\Features\UpdateSharePermissionFeature;
use Modules\Sharing\Http\Requests\ShareItemRequest;
use Modules\Sharing\Http\Requests\UpdateShareRequest;
use Modules\Sharing\Models\Shared;
use Modules\Sharing\Repositories\Interfaces\ShareRepositoryInterface;

class ShareController extends Controller
{
    public function index(ShareRepositoryInterface $shares)
    {
        $sharedWithMe = $shares->getSharesForUser(auth()->id());

        return view('sharing::index', compact('sharedWithMe'));
    }

    public function store(ShareItemRequest $request, ShareItemFeature $feature)
    {
        try {
            $feature->handle($request->validated(), auth()->id());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('sharing::share.shared_successfully'));
    }

    public function update(UpdateShareRequest $request, Shared $shared, UpdateSharePermissionFeature $feature)
    {
        $feature->handle($shared->id, $request->validated()['permission']);

        return redirect()->back()->with('success', __('sharing::share.permission_updated'));
    }

    public function destroy(Shared $shared, RevokeShareFeature $feature)
    {
        $feature->handle($shared->id);

        return redirect()->back()->with('success', __('sharing::share.revoked'));
    }
}