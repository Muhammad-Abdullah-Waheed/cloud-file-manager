@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">{{ __('notifications.title') }}</h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-outline">
                    {{ __('notifications.mark_all_read') }}
                </button>
            </form>
        @endif
    </div>

    @forelse($notifications as $notification)
        <div class="card bg-base-100 shadow-sm mb-3 border
                    {{ $notification->read_at ? 'border-base-200 opacity-70' : 'border-primary' }}">
            <div class="card-body py-3 px-4 flex-row items-center justify-between gap-4">
                <p class="text-sm flex-1">{{ $notification->data['message'] }}</p>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs text-base-content/50">
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs btn-outline">
                                {{ __('notifications.mark_read') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-base-content/40 py-16">
            <p class="text-lg">{{ __('notifications.empty') }}</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>

</div>

@endsection