@section('title', 'Sign in to your account')
<div class="flex flex-col">
    <div class="">
        <a href="{{ route('home') }}">
            <img draggable="false" src="{{ asset('stacker-logo.png') }}" alt="Logo" class="w-48" />
        </a>

        <h2 class="mt-6 text-3xl font-semibold leading-9">
            Sign in to your account
        </h2>
        @if (Route::has('register'))
            <p class="max-w mt-2 text-sm leading-5">
                Or
                <a href="{{ route('register') }}" class="link font-medium transition duration-150 ease-in-out">
                    create a new account
                </a>
            </p>
        @endif
    </div>

    <div class="mt-8 w-[50%]">
        <div class="card bg-base-200 w-96 px-4 py-8 shadow-sm">
            <form wire:submit.prevent="authenticate">
                <div>
                    <label for="email" class="mb-3 block text-sm font-medium leading-5">
                        Email address
                    </label>


                    <input wire:model.lazy="email" id="email" name="email" type="email" required autofocus
                        class="input w-full" />


                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="password" class="mb-3 block text-sm font-medium leading-5">
                        Password
                    </label>


                    <input wire:model.lazy="password" id="password" type="password" required class="input w-full" />


                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">

                    <button type="submit" class="btn btn-neutral w-full">
                        Sign in
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
