@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto p-6">

    @php($retentionDays = (int) config('storage.trash_retention_days', 2))
    @php($bypassLock = auth()->user()->hasPermission('delete-any-file'))

    <h1 class="text-2xl font-bold mb-6">{{ __('trash.title') }}</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4"><span>{{ session('success') }}</span></div>
    @endif

    @if($folders->isEmpty() && $files->isEmpty())
        <div class="flex flex-col items-center justify-center h-64 text-base-content/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-3" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <p class="text-lg">{{ __('trash.empty') }}</p>
        </div>
    @endif

    {{-- Deleted Folders --}}
    @if($folders->isNotEmpty())
        <h2 class="text-sm font-semibold text-base-content/60 uppercase tracking-wide mb-3">
            {{ __('trash.folders') }}
        </h2>
        <div class="overflow-x-auto rounded-xl border border-base-200 mb-8">
            <table class="table w-full">
                <tbody>
                    @foreach($folders as $folder)
                        <tr>
                            <td class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary"
                                     fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                </svg>
                                {{ $folder->name }}
                            </td>
                            <td class="text-sm text-base-content/60">
                                {{ $folder->deleted_at->diffForHumans() }}
                            </td>
                            <td class="text-end">
                                <form method="POST"
                                      action="{{ route('trash.folders.restore', $folder) }}"
                                      class="inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-ghost btn-xs">{{ __('trash.restore') }}</button>
                                </form>
                                @php($folderAvailableAt = $folder->deleted_at->copy()->addDays($retentionDays))
                                @if(!$bypassLock && $folderAvailableAt->isFuture())
                                    <span class="tooltip tooltip-left" data-tip="{{ __('trash.retention_locked', ['time' => $folderAvailableAt->diffForHumans()]) }}">
                                        <button class="btn btn-ghost btn-xs text-error" disabled>
                                            {{ __('trash.delete_permanently') }}
                                        </button>
                                    </span>
                                @else
                                    <button onclick="openPermanentDeleteModal('{{ route('trash.folders.destroy', $folder) }}', '{{ addslashes($folder->name) }}')"
                                            class="btn btn-ghost btn-xs text-error">
                                        {{ __('trash.delete_permanently') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Deleted Files --}}
    @if($files->isNotEmpty())
        <h2 class="text-sm font-semibold text-base-content/60 uppercase tracking-wide mb-3">
            {{ __('trash.files') }}
        </h2>
        <div class="overflow-x-auto rounded-xl border border-base-200">
            <table class="table w-full">
                <tbody>
                    @foreach($files as $file)
                        <tr>
                            <td class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                {{ $file->name }}
                            </td>
                            <td class="text-sm text-base-content/60">
                                {{ $file->deleted_at->diffForHumans() }}
                            </td>
                            <td class="text-end">
                                <form method="POST"
                                      action="{{ route('trash.files.restore', $file) }}"
                                      class="inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-ghost btn-xs">{{ __('trash.restore') }}</button>
                                </form>
                                @php($fileAvailableAt = $file->deleted_at->copy()->addDays($retentionDays))
                                @if(!$bypassLock && $fileAvailableAt->isFuture())
                                    <span class="tooltip tooltip-left" data-tip="{{ __('trash.retention_locked', ['time' => $fileAvailableAt->diffForHumans()]) }}">
                                        <button class="btn btn-ghost btn-xs text-error" disabled>
                                            {{ __('trash.delete_permanently') }}
                                        </button>
                                    </span>
                                @else
                                    <button onclick="openPermanentDeleteModal('{{ route('trash.files.destroy', $file) }}', '{{ addslashes($file->name) }}')"
                                            class="btn btn-ghost btn-xs text-error">
                                        {{ __('trash.delete_permanently') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

{{-- Permanent Delete Confirm Modal --}}
<dialog id="modal_permanent_delete" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-2 text-error">{{ __('trash.delete_permanently') }}</h3>
        <p class="text-base-content/70 mb-4">
            {{ __('trash.confirm_permanent') }} <strong id="permanent_delete_name"></strong>?
        </p>
        <form method="POST" id="permanent_delete_form">
            @csrf @method('DELETE')
            <div class="modal-action">
                <button type="button"
                        onclick="document.getElementById('modal_permanent_delete').close()"
                        class="btn btn-ghost">{{ __('folder.cancel') }}</button>
                <button type="submit" class="btn btn-error">
                    {{ __('trash.delete_permanently') }}
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function openPermanentDeleteModal(action, name) {
    document.getElementById('permanent_delete_form').action = action;
    document.getElementById('permanent_delete_name').textContent = name;
    document.getElementById('modal_permanent_delete').showModal();
}
</script>

@endsection