<?php

namespace App\Http\Controllers;

use App\Features\GetRootFilesFeature;
use App\Features\GetRootFoldersFeature;

class DashboardController extends Controller
{
    public function index(GetRootFoldersFeature $foldersFeature, GetRootFilesFeature $filesFeature)
    {
        return view('dashboard.index', [
            'folders'       => $foldersFeature->handle(auth()->id()),
            'files'         => $filesFeature->handle(auth()->id()),
            'ancestors'     => [],
            'currentFolder' => null,
        ]);
    }
}
