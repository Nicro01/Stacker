@section('title', 'Create a new account')

<div class="flex flex-col">
    <div>
        <a href="{{ route('home') }}">
            <span class="text-5xl font-bold">Stacker</span>
        </a>

        <h2 class="mt-6 text-3xl font-semibold leading-9">
            Create a new account
        </h2>

        <p class="max-w mt-2 text-sm leading-5">
            Or
            <a href="{{ route('login') }}" class="link font-medium transition duration-150 ease-in-out">
                sign in to your account
            </a>
        </p>
    </div>

    <div class="mt-8 w-[50%]">
        <div class="card bg-base-200 w-96 px-4 py-8 shadow-sm">
            <form wire:submit.prevent="register">
                <div>
                    <label for="name" class="block text-sm font-medium leading-5">
                        Name
                    </label>

                    <div class="mt-1">
                        <input wire:model.lazy="name" id="name" type="text" required autofocus
                            class="input w-full" />
                    </div>

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="email" class="block text-sm font-medium leading-5">
                        Email address
                    </label>

                    <div class="mt-1">
                        <input wire:model.lazy="email" id="email" type="email" required class="input w-full" />
                    </div>

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium leading-5">
                        Password
                    </label>

                    <div class="mt-1">
                        <input wire:model.lazy="password" id="password" type="password" required
                            class="input w-full" />
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="password_confirmation" class="block text-sm font-medium leading-5">
                        Confirm Password
                    </label>

                    <div class="mt-1">
                        <input wire:model.lazy="passwordConfirmation" id="password_confirmation" type="password"
                            required class="input w-full" />
                    </div>
                </div>

                <div class="mt-6">
                    <span class="block w-full">
                        <button type="submit" class="btn btn-neutral w-full">
                            Register
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
