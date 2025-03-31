<div x-data="{ menuOpen: false }">

    <x-heroicon-o-bars-3 class="absolute top-10 z-50 size-12 cursor-pointer max-lg:left-12 max-sm:left-6"
        x-on:click="menuOpen = !menuOpen" />

    <div x-show="menuOpen" class="fixed inset-0 z-50 h-screen w-screen cursor-pointer bg-neutral-500/50">

        <ul x-on:click.away="menuOpen = !menuOpen" x-show="menuOpen" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform -translate-x-full" x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform -translate-x-full"
            class="menu bg-base-200 text-base-content min-h-full w-80 cursor-default gap-2 p-4">

            <img draggable="false" src="{{ asset('stacker-logo.png') }}" alt="Logo" class="mx-auto w-32 py-6" />

            @auth
                <li>
                    <a draggable="false" href="{{ route('panel.home') }}" class="btn">Panel</a>
                </li>
                <li>
                    <a draggable="false" href="{{ route('logout') }}" class="btn btn-neutral">Logout</a>
                </li>
            @endauth

            @guest
                <li>
                    <a draggable="false" href="{{ route('login') }}" class="btn">Login</a>
                </li>

                <li>
                    <a draggable="false" href="{{ route('register') }}" class="btn btn-neutral">Register</a>
                </li>
            @endguest
        </ul>
    </div>

</div>
