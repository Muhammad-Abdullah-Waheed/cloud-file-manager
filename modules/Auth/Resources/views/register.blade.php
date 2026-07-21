@extends('core::layouts.app')

@section('content')

<div class="min-h-[85vh] flex items-center justify-center bg-base-200 px-4 pt-5">
    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">

            <h2 class="card-title text-2xl font-bold mb-2">
                {{ __('auth::auth.register_title') }}
            </h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">{{ __('auth::auth.name') }}</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="{{ __('auth::auth.name_placeholder') }}"
                           class="input input-bordered w-full @error('name') input-error @enderror" />
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">{{ __('auth::auth.email') }}</span>
                    </label>
                    <input type="email"
                           name="email"
                           dir="rtl"
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
                           placeholder="{{ __('auth::auth.password_placeholder') }}"
                           class="input input-bordered w-full @error('password') input-error @enderror" />
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-control mb-5">
                    <label class="label">
                        <span class="label-text">{{ __('auth::auth.confirm_password') }}</span>
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           placeholder="{{ __('auth::auth.confirm_password_placeholder') }}"
                           class="input input-bordered w-full" />
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    {{ __('auth::auth.register_btn') }}
                </button>

                <p class="text-center mt-4 text-sm">
                    {{ __('auth::auth.already_have_account') }}
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="link link-primary">
                            {{ __('auth::auth.login_link') }}
                        </a>
                    @endif
                </p>

            </form>
        </div>
    </div>
</div>

@endsection