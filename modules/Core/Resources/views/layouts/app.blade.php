<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-base-100">

    @include('core::components.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="footer footer-center p-4 bg-base-300 text-base-content">
        <p>{{ config('app.name') }} © {{ date('Y') }}</p>
    </footer>

    @if(session('quota'))
        @php($quota = session('quota'))
        <dialog id="quota_modal" class="modal" open>
            <div class="modal-box">
                <h3 class="text-lg font-bold text-error flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ __('core::file.quota_title') }}
                </h3>

                @if(($quota['tier'] ?? 'normal') === 'premium')
                    <p class="py-3 text-sm">{{ __('core::file.quota_premium_body') }}</p>
                    <a href="mailto:{{ config('storage.support_email') }}" class="link link-primary text-sm">
                        {{ config('storage.support_email') }}
                    </a>
                @else
                    <p class="py-3 text-sm">{{ __('core::file.quota_normal_body', ['days' => config('storage.trash_retention_days')]) }}</p>
                    <ul class="list-disc list-inside text-sm text-base-content/70 space-y-1">
                        <li>{{ __('core::file.quota_tip_delete') }}</li>
                        <li>{{ __('core::file.quota_tip_wait', ['days' => config('storage.trash_retention_days')]) }}</li>
                        <li>{{ __('core::file.quota_tip_upgrade') }}</li>
                    </ul>
                @endif

                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">{{ __('core::file.quota_ok') }}</button>
                    </form>
                </div>
            </div>
        </dialog>
    @endif

</body>
</html>
