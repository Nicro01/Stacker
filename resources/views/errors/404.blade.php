@extends('layouts.base')
@section('body')
<div class="flex min-h-screen items-center justify-center">
    <div class="container mx-auto text-center rounded-box shadow-base bg-base-200 shadow-lg py-20">
        <img src="{{ asset('images/error-404.png')  }}" alt="error 404 image - page not found" class="mx-auto w-32 mb-8"> 
        <h2 class="aldrich mb-8 text-4xl font-bold">Oops... Page Not Found</h2>
        <p class="mb-12 text-xl">The page you are looking for cannot be found</p>
    </div>
</div>
@endsection