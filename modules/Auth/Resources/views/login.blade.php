@extends('core::layouts.app')

@section('content')

<div class="min-h-[85vh] flex items-center justify-center bg-base-200 px-4">
    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">

            <h2 class="card-title text-2xl font-bold mb-2">
                {{ __('auth::auth.login_title') }}
            </h2>

            @if(session('status'))
                <div class="alert alert-success mb-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">{{ __('auth::auth.email') }}</span>
                    </label>
                    <input type="email"
                           name="email"
                           dir="ltr"
                           value="{{ old('email') }}"
                           placeholder="{{ __('auth::auth.email_placeholder') }}"
                           class="input input-bordered w-full @error('email') input-error @enderror" />
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">{{ __('auth::auth.password') }}</span>
                    </label>
                    <input type="password"
                           name="password"
                           dir="ltr"
                           placeholder="{{ __('auth::auth.password_placeholder') }}"
                           class="input input-bordered w-full @error('password') input-error @enderror" />
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="form-control mb-5">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="remember" class="checkbox checkbox-primary" />
                        <span class="label-text">{{ __('auth::auth.remember_me') }}</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    {{ __('auth::auth.login_btn') }}
                </button>

                <p class="text-center mt-4 text-sm">
                    {{ __('auth::auth.no_account') }}
                    <a href="{{ route('register') }}" class="link link-primary">
                        {{ __('auth::auth.register_link') }}
                    </a>
                </p>

            </form>
        </div>
    </div>
</div>

@endsection