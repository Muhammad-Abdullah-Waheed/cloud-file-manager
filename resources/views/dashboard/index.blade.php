@extends('layouts.app')

@section('content')

<div class="flex h-[calc(100vh-64px)]">

    {{-- Sidebar --}}
    <aside class="w-64 bg-base-200 border-e border-base-300 flex flex-col p-4 gap-2 hidden md:flex">

        <button class="btn btn-primary w-full gap-2 mb-4">
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

        {{-- Storage bar --}}
        <div class="mt-auto">
            <div class="text-xs text-base-content/60 mb-1">{{ __('dashboard.storage') }}</div>
            <progress class="progress progress-primary w-full" value="0" max="100"></progress>
            <div class="text-xs text-base-content/60 mt-1">0 GB {{ __('dashboard.of') }} 5 GB</div>
        </div>

    </aside>

    {{-- Main content --}}
    <main class="flex-1 overflow-y-auto p-6">

        {{-- Breadcrumb --}}
        <div class="text-sm breadcrumbs mb-4">
            <ul>
                <li>{{ __('dashboard.my_drive') }}</li>
            </ul>
        </div>

        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center h-96 text-base-content/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
            <p class="text-lg font-medium">{{ __('dashboard.empty_title') }}</p>
            <p class="text-sm mt-1">{{ __('dashboard.empty_subtitle') }}</p>
        </div>

    </main>

</div>

@endsection