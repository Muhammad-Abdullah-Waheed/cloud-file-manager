@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">{{ __('upgrade.admin_title') }}</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4"><span>{{ session('success') }}</span></div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-base-200">
        <table class="table w-full">
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
                            {{ $req->requester->name }}
                            <span class="text-xs text-base-content/50">({{ $req->requester->email }})</span>
                        </td>
                        <td class="text-sm text-base-content/70">{{ $req->reason ?? '—' }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.upgrade-requests.approve', $req) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-xs">{{ __('upgrade.approve') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.upgrade-requests.reject', $req) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-error btn-xs btn-outline">{{ __('upgrade.reject') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-base-content/50 py-6">{{ __('upgrade.empty') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
</div>
@endsection
