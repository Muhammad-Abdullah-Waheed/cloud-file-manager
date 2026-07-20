@extends('layouts.app')

@section('content')

<div class="flex h-[calc(100vh-64px)]">

    {{-- Sidebar --}}
    <aside class="w-64 bg-base-200 border-e border-base-300 flex-col p-4 gap-2 hidden md:flex">

        <div class="text-xs font-bold uppercase tracking-widest text-base-content/40 px-3 mb-2">
            {{ __('admin.panel') }}
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg bg-base-300 font-medium">
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

        @if(auth()->user()->hasPermission('manage-users'))
            <a href="{{ route('admin.upgrade-requests.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-base-300 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
                {{ __('admin.upgrade_requests') }}
            </a>
        @endif

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
            <h1 class="text-2xl font-bold">{{ __('admin.users') }}</h1>
            <div class="badge badge-primary badge-outline">
                {{ auth()->user()->role->name }}
            </div>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
            <div class="join w-full max-w-md">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('admin.search_placeholder') }}"
                       class="input input-bordered join-item w-full" />
                <button type="submit" class="btn btn-primary join-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost join-item">✕</a>
                @endif
            </div>
        </form>

        {{-- Users Table --}}
        <div class="overflow-x-auto rounded-xl border border-base-200">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin.name') }}</th>
                        <th>{{ __('admin.email') }}</th>
                        <th>{{ __('admin.role') }}</th>
                        <th>{{ __('admin.storage') }}</th>
                        <th>{{ __('admin.joined') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-base-content/40 text-sm">{{ $user->id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                                            <span class="text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <span class="font-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-sm text-base-content/70">{{ $user->email }}</td>
                            <td>
                                <span class="badge
                                    {{ $user->role->name === 'admin' ? 'badge-error' : ($user->role->name === 'manager' ? 'badge-warning' : 'badge-ghost') }}
                                    badge-sm">
                                    {{ $user->role->name }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $pct = $user->storage_limit > 0
                                        ? round(($user->storage_used / $user->storage_limit) * 100)
                                        : 0;
                                    $usedGB  = number_format($user->storage_used / 1073741824, 2);
                                    $limitGB = number_format($user->storage_limit / 1073741824, 1);
                                @endphp
                                <div class="flex items-center gap-2 min-w-32">
                                    <progress class="progress progress-primary w-20"
                                              value="{{ $pct }}" max="100"></progress>
                                    <span class="text-xs text-base-content/60 whitespace-nowrap">
                                        {{ $usedGB }}/{{ $limitGB }} GB
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm text-base-content/60">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-sm btn-outline">
                                    {{ __('admin.view_drive') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-base-content/40">
                                {{ __('admin.no_users_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $users->links() }}
            </div>
        @endif

    </main>
</div>

@endsection