<?php

namespace App\Http\Controllers;

use App\Features\DeleteFileFeature;
use App\Features\DeleteFolderFeature;
use App\Features\DownloadFileFeature;
use App\Features\GetUserDriveFeature;
use App\Features\GetUsersFeature;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * List all users with optional search by name / email.
     */
    public function index(Request $request, GetUsersFeature $feature)
    {
        $users = $feature->handle(['search' => $request->input('search')]);

        return view('admin.user', compact('users'));
    }

    /**
     * Show the root drive of a specific user.
     */
    public function showUser(User $user, GetUserDriveFeature $feature)
    {
        $data = $feature->handle($user->id, null);

        return view('admin.show-user', array_merge($data, [
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

        return view('admin.show-user', array_merge($data, [
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
            ->with('success', __('admin.file_deleted'));
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
            ->with('success', __('admin.folder_deleted'));
    }
}
