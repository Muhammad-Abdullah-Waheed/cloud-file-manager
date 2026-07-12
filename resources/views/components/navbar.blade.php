<div class="navbar bg-base-200 shadow-md px-6">

    {{-- Logo --}}
    <div class="flex-1">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
            <span>{{ __('nav.brand') }}</span>
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

        @guest
            <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">
                {{ __('nav.login') }}
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                {{ __('nav.register') }}
            </a>
        @endguest

        @auth
            <div class="flex items-center gap-2">
                <div class="badge badge-ghost badge-lg font-medium">
                    {{ auth()->user()->name }}
                </div>
                @if(Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-error btn-sm btn-outline">
                            {{ __('nav.logout') }}
                        </button>
                    </form>
                @endif
            </div>
        @endauth

    </div>
</div>