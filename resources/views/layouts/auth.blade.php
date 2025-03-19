@extends('layouts.base')

@section('body')
    <div data-theme="" class="container relative mx-auto min-h-screen bg-center">
        <div class="border-primary/20 min-h-screen flex-col border-s-2 ps-16 sm:flex sm:items-start sm:justify-center">
            @yield('content')

            @isset($slot)
                {{ $slot }}
            @endisset
        </div>
    </div>
@endsection
