@extends('layouts.base')

@section('body')
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="flex w-full gap-6">

            <div
                class="rounded-box bg-base-200 shadow-base fixed bottom-5 left-1/2 z-50 flex w-[5vw] justify-between px-4 py-4 shadow-lg max-sm:w-[90%] max-sm:-translate-x-1/2 sm:left-6 sm:top-6 sm:h-[calc(100vh-3rem)] sm:flex-col lg:w-[8vw]">
                <nav class="flex items-center space-x-2 sm:flex-col sm:space-x-0 sm:space-y-2">
                    <a draggable="false" data-tip="Packages"
                        class="btn tooltip sm:tooltip-right @if (Route::currentRouteName() == 'panel.home') bg-base-content text-base-100 @endif rounded-box inline-flex size-12 justify-center border-none"
                        href="{{ route('panel.home') }}">

                        <x-heroicon-c-cube class="h-6 min-w-8" />
                    </a>

                    <a draggable="false" data-tip="Project Configurations"
                        class="btn tooltip sm:tooltip-right @if (Route::currentRouteName() == 'panel.configs') bg-base-content text-base-100 @endif rounded-box inline-flex size-12 justify-center border-none"
                        href="{{ route('panel.configs') }}">

                        <x-heroicon-c-wrench class="h-6 min-w-8" />

                    </a>
                </nav>
                <div class="flex items-center sm:flex-col sm:space-x-0 sm:space-y-2">
                    <a draggable="false" data-tip="Your Profile"
                        class="btn @if (Route::currentRouteName() == 'profile') bg-base-content text-base-100 @endif tooltip sm:tooltip-right rounded-box inline-flex size-12 justify-center border-none"
                        href="{{ route('profile') }}">
                        <x-heroicon-s-user class="h-6 min-w-8" />

                    </a>
                    <a draggable="false" data-tip="Logout"
                        class="btn tooltip sm:tooltip-right rounded-box inline-flex size-12 justify-center border-none"
                        href="{{ route('logout') }}">

                        <x-heroicon-s-arrow-left-start-on-rectangle class="h-6 min-w-8" />

                    </a>
                </div>
            </div>
            <div class="flex-1 px-0 pe-[1.5vw] lg:ms-[14vw] 2xl:ms-[8vw]">
                @yield('content')

                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
        </div>
    </div>
@endsection
