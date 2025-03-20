@extends('layouts.base')

@section('body')
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="flex w-full gap-6">

            <div
                class="rounded-box bg-base-200 shadow-base fixed left-6 top-6 flex h-[calc(100vh-3rem)] w-[5vw] flex-col justify-between px-4 py-4 shadow-lg">
                <nav class="flex flex-col items-center space-x-0 space-y-2">
                    <a draggable="false"
                        class="btn @if (Route::currentRouteName() == 'panel.home') bg-base-content text-base-100
                    @else
                        bg-base-100 text-base-content @endif rounded-box inline-flex size-12 justify-center border-none"
                        href="{{ route('panel.home') }}">

                        <x-heroicon-s-cube-transparent class="h-6 min-w-8" />
                    </a>


                </nav>
                <div class="flex flex-col items-center space-x-0 space-y-2">
                    <a draggable="false" class="btn rounded-box inline-flex size-12 justify-center border-none"
                        href="#">

                        <x-heroicon-c-wrench class="h-6 min-w-8" />

                    </a>
                    <a draggable="false" class="btn rounded-box inline-flex size-12 justify-center border-none"
                        href="#">

                        <x-heroicon-s-arrow-left-start-on-rectangle class="h-6 min-w-8" />

                    </a>
                </div>
            </div>
            <div class="ms-[8vw] flex-1 px-0">
                @yield('content')

                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
        </div>
    </div>
@endsection
