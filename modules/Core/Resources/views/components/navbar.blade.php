<div class="navbar bg-base-200 shadow-md px-6">

    {{-- Logo --}}
    <div class="flex-1">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
            <span>{{ __('core::nav.brand') }}</span>
        </a>
    </div>

    {{-- Right side --}}
    <div class="flex-none flex items-center gap-2">

        {{-- Language toggle --}}
        <form method="POST" action="{{ route('language.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
            @csrf
            <button class="btn btn-outline btn-sm">
                {{ app()->getLocale() === 'ar' ? 'English' : 'عربي' }}
            </button>
        </form>

        @auth
            @php($currentUser = auth()->user())

            {{-- Admin / Manager panel button --}}
            @if($currentUser->hasPermission('view-all-files'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                    {{ $currentUser->hasPermission('manage-users') ? __('core::nav.admin_panel') : __('core::nav.manager_panel') }}
                </a>
            @endif

            {{-- Upgrade button (normal users only) --}}
            @if($currentUser->isNormal())
                <button class="btn btn-outline btn-sm" onclick="upgrade_modal.showModal()">
                    {{ __('core::nav.upgrade') }}
                </button>
                <dialog id="upgrade_modal" class="modal">
                    <div class="modal-box">
                        <h3 class="text-lg font-bold">{{ __('core::nav.upgrade_title') }}</h3>
                        <p class="py-2 text-sm text-base-content/70">{{ __('core::nav.upgrade_desc') }}</p>
                        <form method="POST" action="{{ route('upgrade.request') }}">
                            @csrf
                            <textarea name="reason" rows="3" class="textarea textarea-bordered w-full"
                                      placeholder="{{ __('core::nav.upgrade_reason_placeholder') }}"></textarea>
                            <div class="modal-action">
                                <button type="button" class="btn btn-ghost" onclick="upgrade_modal.close()">{{ __('core::file.cancel') }}</button>
                                <button type="submit" class="btn btn-outline">{{ __('core::nav.upgrade_submit') }}</button>
                            </div>
                        </form>
                    </div>
                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                </dialog>
            @endif
        @endauth

        @guest
            <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">
                {{ __('core::nav.login') }}
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                {{ __('core::nav.register') }}
            </a>
        @endguest


        @auth
            {{-- Notification bell --}}
            <div class="dropdown dropdown-end">
                <button tabindex="0" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                                    6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388
                                    6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3
                                    0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge badge-xs badge-primary indicator-item">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </div>
                </button>
                <div tabindex="0"
                    class="dropdown-content bg-base-100 rounded-box shadow-lg z-50 w-80 p-2 border border-base-200 mt-2">
                    <div class="flex justify-between items-center px-2 py-1 mb-1">
                        <span class="font-semibold text-sm">{{ __('core::notifications.title') }}</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf @method('PATCH')
                                <button class="text-xs link link-primary">
                                    {{ __('core::notifications.mark_all_read') }}
                                </button>
                            </form>
                        @endif
                    </div>
                    @forelse(auth()->user()->notifications->take(5) as $notification)
                        <div class="flex items-start gap-2 px-2 py-2 rounded-lg
                                    {{ $notification->read_at ? 'opacity-60' : 'bg-base-200' }}">
                            <p class="text-xs flex-1">{{ $notification->data['message'] }}</p>
                            @if(!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs link link-primary whitespace-nowrap">
                                        {{ __('core::notifications.mark_read') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-center text-base-content/50 py-4">
                            {{ __('core::notifications.empty') }}
                        </p>
                    @endforelse
                    <a href="{{ route('notifications.index') }}"
                    class="block text-center text-xs link link-primary mt-2 py-1">
                        {{ __('core::notifications.view_all') }}
                    </a>
                </div>
            </div>
        @endauth

        @auth
            <div class="flex items-center gap-2">
                <div class="badge badge-ghost badge-lg font-medium">
                    {{ auth()->user()->name }}
                </div>
                @if(Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-error btn-sm btn-outline">
                            {{ __('core::nav.logout') }}
                        </button>
                    </form>
                @endif
            </div>
        @endauth

    </div>
</div>