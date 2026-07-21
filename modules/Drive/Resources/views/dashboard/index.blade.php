@extends('core::layouts.app')

@section('content')

<div class="flex h-[calc(100vh-64px)]">

    {{-- Sidebar --}}
    <aside class="w-64 bg-base-200 border-e border-base-300 flex-col p-4 gap-2 hidden md:flex">

        <button onclick="document.getElementById('modal_new_folder').showModal()"
                class="btn btn-primary w-full gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('drive::dashboard.new') }}
        </button>

        <label for="file_upload_input" class="btn btn-outline w-full gap-2 mb-4 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            {{ __('drive::file.upload') }}
        </label>

        <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data" id="upload_form">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder?->id ?? '' }}">
            <input type="file" id="file_upload_input" name="file" class="hidden"
                   onchange="document.getElementById('upload_form').submit()" />
        </form>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg {{ is_null($currentFolder) ? 'bg-base-300 font-medium' : 'hover:bg-base-300' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
            {{ __('drive::dashboard.my_drive') }}
        </a>

        <a href="{{ route('share.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            {{ __('drive::dashboard.shared') }}
        </a>

        <a href="{{ route('trash.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            {{ __('drive::dashboard.trash') }}
        </a>

        {{-- Storage bar --}}
        <div class="mt-auto">
            @php
                $user    = auth()->user();
                $used    = $user->storage_used;
                $limit   = $user->storage_limit;
                $percent = $limit > 0 ? round(($used / $limit) * 100) : 0;
                $usedKB  = number_format($used / 1024, 2);
                $limitKB = number_format($limit / 1024, 2);
            @endphp
            <div class="text-xs text-base-content/60 mb-1">{{ __('drive::dashboard.storage') }}</div>
            <progress class="progress progress-primary w-full"
                      value="{{ $percent }}" max="100"></progress>
            <div class="text-xs text-base-content/60 mt-1">
                {{ $usedKB }} KB {{ __('drive::dashboard.of') }} {{ $limitKB }} KB
            </div>
        </div>

    </aside>

    {{-- Main content --}}
    <main class="flex-1 overflow-y-auto p-6">

        @if(session('success'))
            <div class="alert alert-success mb-4"><span>{{ session('success') }}</span></div>
        @endif
        @if(session('error'))
            <div class="alert alert-error mb-4"><span>{{ session('error') }}</span></div>
        @endif

        {{-- Breadcrumb --}}
        <div class="text-sm breadcrumbs mb-4">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}">{{ __('drive::dashboard.my_drive') }}</a>
                </li>
                @foreach($ancestors as $ancestor)
                    <li>
                        <a href="{{ route('folders.show', $ancestor) }}">{{ $ancestor->name }}</a>
                    </li>
                @endforeach
                @if($currentFolder)
                    <li>{{ $currentFolder->name }}</li>
                @endif
            </ul>
        </div>

        {{-- Folders --}}
        @if($folders->isNotEmpty())
            <h2 class="text-sm font-semibold text-base-content/60 uppercase tracking-wide mb-3">
                {{ __('drive::folder.folders') }}
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-8">
                @foreach($folders as $folder)
                    <div class="group relative flex flex-col items-center p-3 rounded-xl
                                hover:bg-base-200 transition-colors">

                        <a href="{{ route('folders.show', $folder) }}"
                           class="flex flex-col items-center w-full">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-12 w-12 text-primary mb-2" fill="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2
                                         2H5a2 2 0 01-2-2V7z" />
                            </svg>
                            <span class="text-xs text-center truncate w-full">{{ $folder->name }}</span>
                        </a>

                        <div class="dropdown dropdown-end absolute top-1 end-1
                                    opacity-0 group-hover:opacity-100 transition-opacity">
                            <button tabindex="0" class="btn btn-ghost btn-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                     fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="5" r="1.5"/>
                                    <circle cx="12" cy="12" r="1.5"/>
                                    <circle cx="12" cy="19" r="1.5"/>
                                </svg>
                            </button>
                            <ul tabindex="0"
                                class="dropdown-content menu bg-base-100 rounded-box
                                       z-10 w-40 p-1 shadow-lg border border-base-200">
                                <li>
                                    <button onclick="openRenameModal({{ $folder->id }}, '{{ addslashes($folder->name) }}')"
                                            class="text-sm">{{ __('drive::folder.rename') }}</button>
                                </li>
                                <li>
                                    <button onclick="openShareModal('folder', {{ $folder->id }})"
                                            class="text-sm">{{ __('share.share') }}</button>
                                </li>
                                <li>
                                    <button onclick="openFolderDeleteModal({{ $folder->id }}, '{{ addslashes($folder->name) }}')"
                                            class="text-sm text-error">{{ __('drive::folder.delete') }}</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Files --}}
        @if($files->isNotEmpty())
            <h2 class="text-sm font-semibold text-base-content/60 uppercase tracking-wide mb-3">
                {{ __('drive::file.files') }}
            </h2>
            <div class="overflow-x-auto rounded-xl border border-base-200">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>{{ __('drive::file.name') }}</th>
                            <th>{{ __('drive::file.type') }}</th>
                            <th>{{ __('drive::file.size') }}</th>
                            <th>{{ __('drive::file.uploaded_at') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 text-base-content/40" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    {{ $file->name }}
                                </td>
                                <td class="text-sm text-base-content/60">{{ $file->mime_type }}</td>
                                <td class="text-sm text-base-content/60">
                                    @if($file->currentVersion)
                                        {{ number_format($file->currentVersion->size / 1024, 1) }} KB
                                    @else —
                                    @endif
                                </td>
                                <td class="text-sm text-base-content/60">
                                    {{ $file->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-1 justify-end">
                                        <a href="{{ route('files.download', $file) }}"
                                           class="btn btn-ghost btn-xs">{{ __('drive::file.download') }}</a>
                                        <button onclick="openShareModal('file', {{ $file->id }})"
                                                class="btn btn-ghost btn-xs">{{ __('share.share') }}</button>
                                        <button onclick="openFileDeleteModal({{ $file->id }}, '{{ addslashes($file->name) }}')"
                                                class="btn btn-ghost btn-xs text-error">{{ __('drive::file.delete') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Empty state --}}
        @if($folders->isEmpty() && $files->isEmpty())
            <div class="flex flex-col items-center justify-center h-96 text-base-content/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                </svg>
                <p class="text-lg font-medium">{{ __('drive::dashboard.empty_title') }}</p>
                <p class="text-sm mt-1">{{ __('drive::dashboard.empty_subtitle') }}</p>
            </div>
        @endif

    </main>
</div>

{{-- New Folder Modal --}}
<dialog id="modal_new_folder" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">{{ __('drive::folder.new_folder') }}</h3>
        <form method="POST" action="{{ route('folders.store') }}">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder?->id ?? '' }}">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text">{{ __('drive::folder.folder_name') }}</span></label>
                <input type="text" name="name" autofocus class="input input-bordered w-full" required />
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('modal_new_folder').close()"
                        class="btn btn-ghost">{{ __('drive::folder.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('drive::folder.create') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Rename Folder Modal --}}
<dialog id="modal_rename_folder" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">{{ __('drive::folder.rename') }}</h3>
        <form method="POST" id="rename_form">
            @csrf
            @method('PATCH')
            <div class="form-control mb-4">
                <label class="label"><span class="label-text">{{ __('drive::folder.folder_name') }}</span></label>
                <input type="text" name="name" id="rename_input"
                       class="input input-bordered w-full" required />
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('modal_rename_folder').close()"
                        class="btn btn-ghost">{{ __('drive::folder.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('drive::folder.rename') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Delete Folder Modal --}}
<dialog id="modal_delete_folder" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-2">{{ __('drive::folder.delete') }}</h3>
        <p class="text-base-content/70 mb-4">
            {{ __('drive::folder.delete_confirm') }} <strong id="delete_folder_name"></strong>?
        </p>
        <form method="POST" id="delete_folder_form">
            @csrf @method('DELETE')
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('modal_delete_folder').close()"
                        class="btn btn-ghost">{{ __('drive::folder.cancel') }}</button>
                <button type="submit" class="btn btn-error">{{ __('drive::folder.delete') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Delete File Modal --}}
<dialog id="modal_delete_file" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-2">{{ __('drive::file.delete') }}</h3>
        <p class="text-base-content/70 mb-4">
            {{ __('drive::file.delete_confirm') }} <strong id="delete_file_name"></strong>?
        </p>
        <form method="POST" id="delete_file_form">
            @csrf @method('DELETE')
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('modal_delete_file').close()"
                        class="btn btn-ghost">{{ __('drive::file.cancel') }}</button>
                <button type="submit" class="btn btn-error">{{ __('drive::file.delete') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Share Modal --}}
<dialog id="modal_share" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">{{ __('share.share') }}</h3>
        <form method="POST" action="{{ route('share.store') }}" id="share_form">
            @csrf
            <input type="hidden" name="shared_type" id="share_type" value="">
            <input type="hidden" name="shared_id" id="share_id" value="">
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">{{ __('share.share_with') }}</span>
                </label>
                <input type="email" name="receiver_email" dir="ltr"
                       placeholder="{{ __('share.email_placeholder') }}"
                       class="input input-bordered w-full" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">{{ __('share.permission') }}</span>
                </label>
                <select name="permission" class="select select-bordered w-full">
                    <option value="read">{{ __('share.read') }}</option>
                    <option value="write">{{ __('share.write') }}</option>
                </select>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('modal_share').close()"
                        class="btn btn-ghost">{{ __('share.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('share.share') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function openRenameModal(id, name) {
    document.getElementById('rename_form').action = '/folders/' + id;
    document.getElementById('rename_input').value = name;
    document.getElementById('modal_rename_folder').showModal();
}
function openFolderDeleteModal(id, name) {
    document.getElementById('delete_folder_form').action = '/folders/' + id;
    document.getElementById('delete_folder_name').textContent = name;
    document.getElementById('modal_delete_folder').showModal();
}
function openFileDeleteModal(id, name) {
    document.getElementById('delete_file_form').action = '/files/' + id;
    document.getElementById('delete_file_name').textContent = name;
    document.getElementById('modal_delete_file').showModal();
}
function openShareModal(type, id) {
    document.getElementById('share_type').value = type;
    document.getElementById('share_id').value = id;
    document.getElementById('modal_share').showModal();
}
</script>

@endsection