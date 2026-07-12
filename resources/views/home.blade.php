@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="hero min-h-[70vh] bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="text-5xl font-bold">{{ __('home.hero_title') }}</h1>
            <p class="py-6 text-lg">{{ __('home.hero_subtitle') }}</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                {{ __('home.cta') }}
            </a>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="py-16 px-4">
    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body items-center text-center">
                <span class="text-4xl">🔒</span>
                <h2 class="card-title">{{ __('home.feature_1') }}</h2>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body items-center text-center">
                <span class="text-4xl">🔗</span>
                <h2 class="card-title">{{ __('home.feature_2') }}</h2>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body items-center text-center">
                <span class="text-4xl">🌍</span>
                <h2 class="card-title">{{ __('home.feature_3') }}</h2>
            </div>
        </div>
    </div>
</section>

@endsection