<div x-data="{ menuOpen: false }">

    <x-heroicon-o-bars-3 class="absolute top-10 z-50 size-12 cursor-pointer" x-on:click="menuOpen = !menuOpen" />

    <div x-show="menuOpen" class="fixed inset-0 z-50 h-screen w-screen cursor-pointer bg-neutral-500/50">

        <ul x-on:click.away="menuOpen = !menuOpen" x-show="menuOpen" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform -translate-x-full" x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform -translate-x-full"
            class="menu bg-base-200 text-base-content min-h-full w-80 cursor-default p-4">

            @auth

                <li><a href="{{ route('panel.home') }}">Panel</a></li>
                <li>
                    <a href="{{ route('logout') }}">Logout</a>
                </li>
            @endauth

            @guest
                <li>
                    <a href="{{ route('login') }}">Login/Register</a>
                </li>
            @endguest
        </ul>
    </div>

</div>
