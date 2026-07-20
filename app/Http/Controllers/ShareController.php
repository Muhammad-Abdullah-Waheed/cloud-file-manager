<?php

namespace App\Http\Controllers;

use App\Features\RevokeShareFeature;
use App\Features\ShareItemFeature;
use App\Features\UpdateSharePermissionFeature;
use App\Http\Requests\ShareItemRequest;
use App\Http\Requests\UpdateShareRequest;
use App\Models\Shared;
use App\Repositories\Interfaces\ShareRepositoryInterface;

class ShareController extends Controller
{
    public function index(ShareRepositoryInterface $shares)
    {
        $sharedWithMe = $shares->getSharesForUser(auth()->id());

        return view('share.index', compact('sharedWithMe'));
    }

    public function store(ShareItemRequest $request, ShareItemFeature $feature)
    {
        try {
            $feature->handle($request->validated(), auth()->id());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('share.shared_successfully'));
    }

    public function update(UpdateShareRequest $request, Shared $shared, UpdateSharePermissionFeature $feature)
    {
        $feature->handle($shared->id, $request->validated()['permission']);

        return redirect()->back()->with('success', __('share.permission_updated'));
    }

    public function destroy(Shared $shared, RevokeShareFeature $feature)
    {
        $feature->handle($shared->id);

        return redirect()->back()->with('success', __('share.revoked'));
    }
}