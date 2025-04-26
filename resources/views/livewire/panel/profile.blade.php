<div class="grid min-h-screen grid-cols-1 place-content-start items-start gap-12 sm:grid-cols-3">

    {{-- <livewire:components.theme-selection /> --}}

    <div class="rounded-box bg-base-200 shadow-base relative col-span-1 flex h-[20vh] shadow-lg sm:col-span-3">

        <figure class="rounded-box bg-base-100 group relative h-full w-full object-cover">

            @if ($background)
                <img draggable="false" src="{{ $background->temporaryUrl() }}" alt="Profile Background"
                    class="rounded-box h-full w-full object-cover" />
            @elseif($user->background)
                <img draggable="false" src="{{ $user->background }}" alt="Profile Background"
                    class="rounded-box h-full w-full object-cover" />
            @else
                <img draggable="false" src="{{ asset('images/default/user.png') }}" alt="Profile Background"
                    class="rounded-box h-full w-full object-cover" />
            @endif

            <div class="bg-base/60 rounded-box absolute inset-0 flex h-full w-full cursor-pointer items-center justify-center opacity-0 backdrop-blur-md transition-opacity duration-150 group-hover:opacity-100"
                x-bind:class="{ 'opacity-100': backgroundImage }">

                <x-heroicon-s-photo class="h-12 w-12" />


                <input wire:model.live="background" type="file" accept=".png, .jpg, .jpeg"
                    class="absolute z-10 h-full w-full cursor-pointer opacity-0" />
            </div>

        </figure>

        {{-- Integrations Buttons --}}
        <div class="absolute -bottom-5 left-[calc(3.5rem+8vw)] translate-y-full max-sm:hidden">
            @if (!$user->github_token)
                <a draggable="false" href="{{ url('/github/redirect') }}" class="btn btn-primary">
                    <img src="{{ asset('images/logos/github.png') }}" alt="Github Logo" class="size-6 invert" />
                    Connect with Github
                </a>
            @endif
        </div>



        {{-- User Profile Image --}}
        <div
            class="rounded-box shadow-base absolute bottom-0 left-5 h-[8vw] w-[8vw] translate-y-1/2 border-2 border-white bg-gray-200 shadow-lg max-sm:left-1/2 max-sm:min-h-32 max-sm:min-w-32 max-sm:-translate-x-1/2 max-sm:translate-y-1/4">

            <figure class="rounded-box group relative h-full w-full object-cover">


                @if ($profileImage)
                    <img draggable="false" src="{{ $profileImage->temporaryUrl() }}" alt="Profile Background"
                        class="rounded-box h-full w-full object-cover" />
                @elseif($user->github_avatar)
                    <img draggable="false" src="{{ $user->github_avatar }}" alt="Profile"
                        class="rounded-box h-full w-full object-cover" />
                @elseif($user->profile_image)
                    <img draggable="false" src="{{ $user->profile_image }}" alt="Profile"
                        class="rounded-box h-full w-full object-cover" />
                @else
                    <img draggable="false"
                        src="https://images.unsplash.com/photo-1610216705422-caa3fcb6d158?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                        alt="Profile" class="rounded-box h-full w-full object-cover" />
                @endif

                <div class="bg-base/60 rounded-box absolute inset-0 flex h-full w-full cursor-pointer items-center justify-center opacity-0 backdrop-blur-md transition-opacity duration-150 group-hover:opacity-100"
                    x-bind:class="{ 'opacity-100': profileImage }">

                    <x-heroicon-s-photo class="h-12 w-12" />

                    <input wire:model.live="profileImage" type="file" accept=".png, .jpg, .jpeg"
                        class="absolute z-10 h-full w-full cursor-pointer opacity-0" />

                </div>

            </figure>

        </div>
    </div>

    <div class="col-span-1 mt-[2vw] flex justify-between max-sm:w-full max-sm:flex-col sm:col-span-3 sm:ms-5">
        <div class="flex flex-col gap-2 max-sm:w-full">
            <h2 class="text-2xl font-bold">Hi, {{ $user->github_username ? $user->github_username : $user->name }}</h2>
            <p class="text-sm">Manage your profile here.</p>
        </div>

        <div class="flex flex-col items-start gap-2 max-sm:w-full sm:-mt-[3.5vw] sm:items-end">


            @if ($status)
                <div class="flex items-center gap-2">
                    <div class="inline-grid *:[grid-area:1/1]">
                        <div class="status status-success animate-ping"></div>
                        <div class="status status-success"></div>
                    </div> API is online
                </div>
            @else
                <div class="flex items-center gap-2">
                    <div class="inline-grid *:[grid-area:1/1]">
                        <div class="status status-error animate-ping"></div>
                        <div class="status status-error"></div>
                    </div> API is offline
                </div>
            @endif

            <div x-data="{ interval: null }" x-init="const status = await getApiStatus($wire);
            if (status) {
                console.log('Status: Online');
                $wire.updateStatus(true);
            } else {
                console.log('Status: Offline');
                $wire.updateStatus(false);
            }
            
            interval = setInterval(async () => {
                const status = await getApiStatus($wire);
                if (status) {
                    console.log('Status: Online');
                    $wire.updateStatus(true);
                } else {
                    console.log('Status: Offline');
                    $wire.updateStatus(false);
                }
            }, 3000);">
            </div>


            <div class="flex flex-col gap-4 max-sm:w-full sm:items-end">
                <div class="kbd kbd-md cursor-pointer" onclick="folderModal.showModal()">Root Folder :
                    {{ $user->projects_path }}</div>
                <div class="kbd kbd-md cursor-pointer" onclick="apiModal.showModal()">API Port: {{ $user->port }}
                </div>

                <div class="w-full sm:hidden">
                    @if (!$user->github_token)
                        <a draggable="false" href="{{ url('/github/redirect') }}" class="btn btn-primary w-full">
                            <img src="{{ asset('images/logos/github.png') }}" alt="Github Logo"
                                class="size-6 invert" />
                            Connect with Github
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-1 sm:col-span-3">
        <h3 class="mb-2 text-3xl font-bold">Your Projects</h3>
        <p class="text-sm">See your projects created with Stacker</p>

        <div class="my-6 grid w-full grid-cols-1 gap-6 sm:grid-cols-5">
            @for ($i = 0; $i < 6; $i++)
                <div class="skeleton h-48"></div>
            @endfor
        </div>

        {{-- <button class="btn w-full">
            Load More
        </button> --}}
    </div>



    <!-- Folder Modal -->
    <dialog id="folderModal" class="modal select-none" wire:ignore>
        <div class="modal-box">
            <h3 class="text-lg font-bold">Update projects folder</h3>

            <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-64 w-full border p-4">
                <legend class="fieldset-legend">New projects folder path</legend>
                <label class="fieldset-label">
                    <input type="text" x-on:keyup.enter="$wire.changeRootPath; folderModal.close()"
                        wire:model="rootPath" class="input input-bordered w-full" />
                </label>
            </fieldset>
            <div class="modal-action">
                <button class="btn w-[50%]" onclick="folderModal.close()">Close</button>
                <button class="btn btn-neutral w-[50%]"
                    x-on:click="$wire.changeRootPath; folderModal.close()">Save</button>
            </div>
        </div>
    </dialog>

    <!-- API Port Modal -->
    <dialog id="apiModal" class="modal select-none" wire:ignore>
        <div class="modal-box">
            <h3 class="text-lg font-bold">Update API Port</h3>

            <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-64 w-full border p-4">
                <legend class="fieldset-legend">New API Port</legend>
                <label class="fieldset-label">
                    <input type="text" wire:model="apiPort"
                        x-on:keyup.enter="$wire.changeApiPort; apiModal.close()"
                        class="input input-bordered w-full" />
                </label>
            </fieldset>
            <div class="modal-action">
                <button class="btn w-[50%]" onclick="apiModal.close()">Close</button>
                <button class="btn btn-neutral w-[50%]"
                    x-on:click="$wire.changeApiPort; apiModal.close()">Save</button>
            </div>
        </div>
    </dialog>

    <script>
        async function getApiStatus(component) {
            var port = component.get('port');

            try {
                const response = await axios.get('http://127.0.0.1:' + port + '/api/status');
                return response.data.status;
            } catch (error) {
                console.log(error);
                return false;
            }
        }
    </script>


</div>
