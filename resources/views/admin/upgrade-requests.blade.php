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
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ __('admin.users') }}
        </a>

        <a href="{{ route('admin.delete-requests.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            {{ __('admin.delete_requests') }}
        </a>

        <a href="{{ route('admin.upgrade-requests.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg bg-base-300 font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M7 11l5-5m0 0l5 5m-5-5v12" />
            </svg>
            {{ __('admin.upgrade_requests') }}
        </a>

        <div class="divider my-1"></div>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
            {{ __('dashboard.my_drive') }}
        </a>
    </aside>

    {{-- Main --}}
    <main class="flex-1 overflow-y-auto p-6">

        @if(session('success'))
            <div class="alert alert-success mb-4"><span>{{ session('success') }}</span></div>
        @endif
        @if(session('error'))
            <div class="alert alert-error mb-4"><span>{{ session('error') }}</span></div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">{{ __('upgrade.admin_title') }}</h1>
            <div class="badge badge-primary badge-outline">
                {{ auth()->user()->role->name }}
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-base-200">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>{{ __('upgrade.user') }}</th>
                        <th>{{ __('upgrade.reason') }}</th>
                        <th class="text-end">{{ __('upgrade.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $req->requester->name }}</div>
                                <div class="text-xs text-base-content/50">{{ $req->requester->email }}</div>
                            </td>
                            <td class="text-sm text-base-content/70">{{ $req->reason ?? '—' }}</td>
                            <td class="text-end">
                                <div class="flex gap-2 justify-end">
                                    <form method="POST" action="{{ route('admin.upgrade-requests.approve', $req) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-success btn-xs">{{ __('upgrade.approve') }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.upgrade-requests.reject', $req) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-error btn-xs btn-outline">{{ __('upgrade.reject') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-12 text-base-content/40">{{ __('upgrade.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $requests->links() }}
            </div>
        @endif

    </main>
</div>

@endsection
