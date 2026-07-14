@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">{{ __('share.shared_with_me') }}</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4"><span>{{ session('success') }}</span></div>
    @endif

    @if($sharedWithMe->isEmpty())
        <div class="flex flex-col items-center justify-center h-64 text-base-content/40">
            <p class="text-lg">{{ __('share.no_shares') }}</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-base-200">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>{{ __('file.name') }}</th>
                        <th>{{ __('share.shared_by') }}</th>
                        <th>{{ __('share.permission') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sharedWithMe as $share)
                        <tr>
                            <td>{{ $share->shared_id }} ({{ $share->shared_type }})</td>
                            <td>{{ $share->sender->name }}</td>
                            <td>
                                <span class="badge {{ $share->permission === 'write' ? 'badge-primary' : 'badge-ghost' }}">
                                    {{ __('share.' . $share->permission) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

@endsection