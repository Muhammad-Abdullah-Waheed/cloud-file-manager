<?php

namespace App\Http\Controllers;

use App\Features\GetRootFoldersFeature;
use App\Features\GetRootFilesFeature;

class DashboardController extends Controller
{
    public function index(GetRootFoldersFeature $foldersFeature, GetRootFilesFeature $filesFeature)
    {
        $folders = $foldersFeature->handle(auth()->id());
        $files = $filesFeature->handle(auth()->id());

        return view('dashboard.index', compact('folders', 'files'));
    }
}