@extends('layouts.base')

@section('body')
    <div data-theme="" class="container relative mx-auto min-h-screen bg-center max-sm:px-6">
        <div class="border-primary/20 flex min-h-screen flex-col justify-center sm:items-start sm:border-s-2 sm:ps-16">
            @yield('content')

            @isset($slot)
                {{ $slot }}
            @endisset
        </div>
    </div>
@endsection
