@extends('layouts.app')

@section('content')

<div class="flex h-[calc(100vh-64px)]">

    {{-- Sidebar --}}
    <aside class="w-64 bg-base-200 border-e border-base-300 flex-col p-4 gap-2 hidden md:flex">

        <button onclick="document.getElementById('modal_new_folder').showModal()"
                class="btn btn-primary w-full gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('dashboard.new') }}
        </button>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg bg-base-300 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
            {{ __('dashboard.my_drive') }}
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
            {{ __('dashboard.shared') }}
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            {{ __('dashboard.trash') }}
        </a>

        <div class="mt-auto">
            <div class="text-xs text-base-content/60 mb-1">{{ __('dashboard.storage') }}</div>
            <progress class="progress progress-primary w-full" value="0" max="100"></progress>
            <div class="text-xs text-base-content/60 mt-1">0 GB {{ __('dashboard.of') }} 5 GB</div>
        </div>

    </aside>

    {{-- Main content --}}
    <main class="flex-1 overflow-y-auto p-6">

        {{-- Flash messages — auto dismiss after 5s --}}
        @if(session('success'))
            <div id="flash-success" class="alert alert-success mb-4 transition-opacity duration-500">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div id="flash-error" class="alert alert-error mb-4 transition-opacity duration-500">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="text-sm breadcrumbs mb-4">
            <ul>
                <li>{{ __('dashboard.my_drive') }}</li>
            </ul>
        </div>

        @if($folders->isNotEmpty())

            <h2 class="text-sm font-semibold text-base-content/60 uppercase tracking-wide mb-3">
                {{ __('folder.folders') }}
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-8">
                @foreach($folders as $folder)
                    <div class="group relative flex flex-col items-center p-3 rounded-xl
                                hover:bg-base-200 cursor-pointer transition-colors">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-12 w-12 text-primary mb-2" fill="currentColor"
                             viewBox="0 0 24 24">
                            <path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2
                                     2H5a2 2 0 01-2-2V7z" />
                        </svg>

                        <span class="text-xs text-center truncate w-full">
                            {{ $folder->name }}
                        </span>

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
                                            class="text-sm">
                                        {{ __('folder.rename') }}
                                    </button>
                                </li>
                                <li>
                                    <button onclick="openDeleteModal({{ $folder->id }}, '{{ addslashes($folder->name) }}')"
                                            class="text-sm text-error">
                                        {{ __('folder.delete') }}
                                    </button>
                                </li>
                            </ul>
                        </div>

                    </div>
                @endforeach
            </div>

        @else

            <div class="flex flex-col items-center justify-center h-96 text-base-content/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                </svg>
                <p class="text-lg font-medium">{{ __('dashboard.empty_title') }}</p>
                <p class="text-sm mt-1">{{ __('dashboard.empty_subtitle') }}</p>
            </div>

        @endif

    </main>
</div>

{{-- New Folder Modal --}}
<dialog id="modal_new_folder" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">{{ __('folder.new_folder') }}</h3>
        <form method="POST" action="{{ route('folders.store') }}">
            @csrf
            <input type="hidden" name="parent_id" value="">
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">{{ __('folder.folder_name') }}</span>
                </label>
                <input type="text" name="name" autofocus
                       value="{{ old('name') }}"
                       class="input input-bordered w-full @error('slug') input-error @enderror"
                       required />
                @error('slug')
                    <p style="color: red;">{{ __('folder.name_taken') }}</p>
                @enderror
            </div>
            <div class="modal-action">
                <button type="button"
                        onclick="document.getElementById('modal_new_folder').close()"
                        class="btn btn-ghost">{{ __('folder.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('folder.create') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Rename Modal --}}
<dialog id="modal_rename_folder" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">{{ __('folder.rename') }}</h3>
        <form method="POST" id="rename_form">
            @csrf
            @method('PATCH')
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">{{ __('folder.folder_name') }}</span>
                </label>
                <input type="text" name="name" id="rename_input"
                       class="input input-bordered w-full @error('slug') input-error @enderror"
                       required />
                @error('slug')
                    <p style="color: red;">{{ __('folder.name_taken') }}</p>
                @enderror
            </div>
            <div class="modal-action">
                <button type="button"
                        onclick="document.getElementById('modal_rename_folder').close()"
                        class="btn btn-ghost">{{ __('folder.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('folder.rename') }}</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Delete Modal --}}
<dialog id="modal_delete_folder" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-2">{{ __('folder.delete') }}</h3>
        <p class="text-base-content/70 mb-4">
            {{ __('folder.delete_confirm') }} <strong id="delete_folder_name"></strong>?
        </p>
        <form method="POST" id="delete_form">
            @csrf
            @method('DELETE')
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

<script>
    // Auto-dismiss flash messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function () {
        ['flash-success', 'flash-error'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) {
                setTimeout(function () {
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 500);
                }, 5000);
            }
        });

        // Reopen new folder modal if it had a validation error
        @if($errors->has('slug') && !old('_method'))
            document.getElementById('modal_new_folder').showModal();
        @endif

        // Reopen rename modal if it had a validation error
        @if($errors->has('slug') && old('_method') === 'PATCH')
            document.getElementById('modal_rename_folder').showModal();
        @endif
    });

    function openRenameModal(id, name) {
        document.getElementById('rename_form').action = '/folders/' + id;
        document.getElementById('rename_input').value = name;
        document.getElementById('modal_rename_folder').showModal();
    }

    function openDeleteModal(id, name) {
        document.getElementById('delete_form').action = '/folders/' + id;
        document.getElementById('delete_folder_name').textContent = name;
        document.getElementById('modal_delete_folder').showModal();
    }
</script>

@endsection