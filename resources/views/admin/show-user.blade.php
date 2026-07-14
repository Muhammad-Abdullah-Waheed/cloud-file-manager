@extends('layouts.app')

@section('content')

<div class="flex h-[calc(100vh-64px)]">

    {{-- Sidebar --}}
    <aside class="w-64 bg-base-200 border-e border-base-300 flex-col p-4 gap-2 hidden md:flex">

        <div class="text-xs font-bold uppercase tracking-widest text-base-content/40 px-3 mb-2">
            {{ __('admin.panel') }}
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('admin.back_to_users') }}
        </a>

        {{-- User info card --}}
        <div class="card bg-base-100 border border-base-300 mt-2">
            <div class="card-body p-4 gap-1">
                <div class="avatar placeholder mb-2">
                    <div class="bg-neutral text-neutral-content rounded-full w-10">
                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                </div>
                <p class="font-semibold text-sm truncate">{{ $user->name }}</p>
                <p class="text-xs text-base-content/60 truncate">{{ $user->email }}</p>
                <span class="badge
                    {{ $user->role->name === 'admin' ? 'badge-error' : ($user->role->name === 'manager' ? 'badge-warning' : 'badge-ghost') }}
                    badge-sm mt-1">
                    {{ $user->role->name }}
                </span>
                @php
                    $pct     = $user->storage_limit > 0
                        ? round(($user->storage_used / $user->storage_limit) * 100)
                        : 0;
                    $usedGB  = number_format($user->storage_used / 1073741824, 2);
                    $limitGB = number_format($user->storage_limit / 1073741824, 1);
                @endphp
                <div class="mt-3">
                    <div class="text-xs text-base-content/50 mb-1">{{ __('dashboard.storage') }}</div>
                    <progress class="progress progress-primary w-full" value="{{ $pct }}" max="100"></progress>
                    <div class="text-xs text-base-content/50 mt-1">{{ $usedGB }} / {{ $limitGB }} GB</div>
                </div>
            </div>
        </div>

    </aside>

    {{-- Main --}}
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
                    <a href="{{ route('admin.users.index') }}">{{ __('admin.users') }}</a>
                </li>
                <li>
                    <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                </li>
                @foreach($ancestors as $ancestor)
                    <li>
                        <a href="{{ route('admin.users.folders.show', [$user, $ancestor]) }}">
                            {{ $ancestor->name }}
                        </a>
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
                {{ __('folder.folders') }}
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-8">
                @foreach($folders as $folder)
                    <div class="group relative flex flex-col items-center p-3 rounded-xl
                                hover:bg-base-200 transition-colors">

                        <a href="{{ route('admin.users.folders.show', [$user, $folder]) }}"
                           class="flex flex-col items-center w-full">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-12 w-12 text-primary mb-2" fill="currentColor"
                                 viewBox="0 0 24 24">
                                <path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2
                                         2H5a2 2 0 01-2-2V7z" />
                            </svg>
                            <span class="text-xs text-center truncate w-full">{{ $folder->name }}</span>
                        </a>

                        @if(auth()->user()->hasPermission('delete-any-file'))
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
                                        <button onclick="openFolderDeleteModal({{ $folder->id }}, '{{ addslashes($folder->name) }}')"
                                                class="text-sm text-error">
                                            {{ __('folder.delete') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Files --}}
        @if($files->isNotEmpty())
            <h2 class="text-sm font-semibold text-base-content/60 uppercase tracking-wide mb-3">
                {{ __('file.files') }}
            </h2>
            <div class="overflow-x-auto rounded-xl border border-base-200">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>{{ __('file.name') }}</th>
                            <th>{{ __('file.type') }}</th>
                            <th>{{ __('file.size') }}</th>
                            <th>{{ __('file.uploaded_at') }}</th>
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
                                    @if(auth()->user()->hasPermission('delete-any-file'))
                                        <button onclick="openFileDeleteModal({{ $file->id }}, '{{ addslashes($file->name) }}')"
                                                class="btn btn-ghost btn-xs text-error">
                                            {{ __('file.delete') }}
                                        </button>
                                    @endif
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
                <p class="text-lg font-medium">{{ __('dashboard.empty_title') }}</p>
                <p class="text-sm mt-1">{{ __('admin.user_has_no_files', ['name' => $user->name]) }}</p>
            </div>
        @endif

    </main>
</div>

{{-- Delete Folder Modal --}}
<dialog id="modal_delete_folder" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-2">{{ __('folder.delete') }}</h3>
        <p class="text-base-content/70 mb-4">
            {{ __('folder.delete_confirm') }} <strong id="delete_folder_name"></strong>?
        </p>
        <form method="POST" id="delete_folder_form">
            @csrf @method('DELETE')
            <div class="modal-action">
                <button type="button"
                        onclick="document.getElementById('modal_delete_folder').close()"
                        class="btn btn-ghost">{{ __('folder.cancel') }}</button>
                <button type="submit" class="btn btn-error">{{ __('folder.delete') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Delete File Modal --}}
<dialog id="modal_delete_file" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-2">{{ __('file.delete') }}</h3>
        <p class="text-base-content/70 mb-4">
            {{ __('file.delete_confirm') }} <strong id="delete_file_name"></strong>?
        </p>
        <form method="POST" id="delete_file_form">
            @csrf @method('DELETE')
            <div class="modal-action">
                <button type="button"
                        onclick="document.getElementById('modal_delete_file').close()"
                        class="btn btn-ghost">{{ __('file.cancel') }}</button>
                <button type="submit" class="btn btn-error">{{ __('file.delete') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function openFolderDeleteModal(id, name) {
    document.getElementById('delete_folder_form').action =
        '/admin/users/{{ $user->id }}/folders/' + id;
    document.getElementById('delete_folder_name').textContent = name;
    document.getElementById('modal_delete_folder').showModal();
}
function openFileDeleteModal(id, name) {
    document.getElementById('delete_file_form').action =
        '/admin/users/{{ $user->id }}/files/' + id;
    document.getElementById('delete_file_name').textContent = name;
    document.getElementById('modal_delete_file').showModal();
}
</script>

@endsection