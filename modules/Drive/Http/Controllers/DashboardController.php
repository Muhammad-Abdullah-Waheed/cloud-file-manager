<?php

namespace Modules\Drive\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Drive\Features\GetRootFilesFeature;
use Modules\Drive\Features\GetRootFoldersFeature;

class DashboardController extends Controller
{
    public function index(GetRootFoldersFeature $foldersFeature, GetRootFilesFeature $filesFeature)
    {
        return view('drive::dashboard.index', [
            'folders'       => $foldersFeature->handle(auth()->id()),
            'files'         => $filesFeature->handle(auth()->id()),
            'ancestors'     => [],
            'currentFolder' => null,
        ]);
    }
}
