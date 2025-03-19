<div class="drawer absolute left-60 top-10 z-10 w-auto">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content">
        <label for="my-drawer" class="drawer-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-10 cursor-pointer">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </label>
    </div>
    <div class="drawer-side">
        <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
            <li>
                <a href="{{ route('login') }}">Login/Register</a>
            </li>
            <li><a href="{{ route('panel.home') }}">Panel</a></li>
            <li>
                <a href="{{ route('logout') }}">Logout</a>
            </li>
        </ul>
    </div>
</div>
