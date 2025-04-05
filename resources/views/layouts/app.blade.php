@extends('layouts.base')

@section('body')
    @yield('content')

    @php
        $cookiesAccepted = isset($_COOKIE['cookiesAccepted']) && $_COOKIE['cookiesAccepted'] === 'true';
    @endphp

    @if (!$cookiesAccepted)
        <div x-data="{ open: {{ $cookiesAccepted ? 'false' : 'true' }} }" x-init="() => setTimeout(() => open = true, 500)"
            class="container fixed bottom-10 left-1/2 z-[60] mx-auto flex -translate-x-1/2 items-center justify-center max-sm:max-w-[80%]">

            <div x-show="open" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter="transition duration-200 transform ease"
                x-transition:leave="transition duration-200 transform ease" x-transition:leave-end="opacity-0 scale-90"
                class="bg-base-100 rounded-box flex w-full items-center justify-between gap-8 p-4 shadow-lg max-sm:flex-col">
                <div>
                    <p class="text-sm">
                        This site uses cookies to improve your experience. By continuing to browse the site, you agree to
                        our use of
                        cookies. For more information, please read our <a href="/privacy-policy" class="link">Privacy
                            Policy</a>.
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <button x-on:click="open = false" class="hover:link text-red-500">Refuse</button>
                    <button x-on:click="allowCookies()" class="btn btn-neutral">Accept All</button>
                </div>
            </div>

        </div>
    @endif

    <script>
        function allowCookies() {
            document.cookie = "cookiesAccepted=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
            location.reload();
        }
    </script>
@endsection
