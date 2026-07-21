<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Admin\Features\GetUsersFeature;
use Modules\Auth\Models\User;
use Illuminate\Http\Request;
use Modules\Drive\Features\DeleteFileFeature;
use Modules\Drive\Features\DeleteFolderFeature;
use Modules\Drive\Features\DownloadFileFeature;
use Modules\Drive\Features\GetUserDriveFeature;
use Modules\Drive\Models\File;
use Modules\Drive\Models\Folder;

class AdminController extends Controller
{
    /**
     * List all users with optional search by name / email.
     */
    public function index(Request $request, GetUsersFeature $feature)
    {
        $users = $feature->handle(['search' => $request->input('search')]);

        return view('admin::user', compact('users'));
    }

    /**
     * Show the root drive of a specific user.
     */
    public function showUser(User $user, GetUserDriveFeature $feature)
    {
        $data = $feature->handle($user->id, null);

        return view('admin::show-user', array_merge($data, [
            'user'          => $user,
            'currentFolder' => null,
        ]));
    }

    /**
     * Browse a folder inside another user's drive.
     */
    public function showFolder(User $user, Folder $folder, GetUserDriveFeature $feature)
    {
        abort_if($folder->user_id !== $user->id, 404);

        $data = $feature->handle($user->id, $folder->id);

        return view('admin::show-user', array_merge($data, [
            'user'          => $user,
            'currentFolder' => $folder,
        ]));
    }

    /**
     * Download any user's file (admin + manager).
     */
    public function downloadFile(User $user, File $file, DownloadFileFeature $feature)
    {
        abort_if($file->user_id !== $user->id, 404);

        return $feature->handle($file);
    }

    /**
     * Soft-delete a user's file (admin only).
     */
    public function destroyFile(User $user, File $file, DeleteFileFeature $feature)
    {
        abort_if($file->user_id !== $user->id, 404);

        $feature->handle($file);

        return redirect()
            ->back()
            ->with('success', __('admin::admin.file_deleted'));
    }

    /**
     * Soft-delete a user's folder (admin only).
     */
    public function destroyFolder(User $user, Folder $folder, DeleteFolderFeature $feature)
    {
        abort_if($folder->user_id !== $user->id, 404);

        $feature->handle($folder->id);

        return redirect()
            ->back()
            ->with('success', __('admin::admin.folder_deleted'));
    }
}
