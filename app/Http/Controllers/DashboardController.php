<?php

namespace App\Http\Controllers;

use App\Features\GetRootFoldersFeature;

class DashboardController extends Controller
{
    public function index(GetRootFoldersFeature $feature)
    {
        $folders = $feature->handle(auth()->id());

        return view('dashboard.index', compact('folders'));
    }
}