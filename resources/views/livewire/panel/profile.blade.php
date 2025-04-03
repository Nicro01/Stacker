<div class="grid min-h-screen grid-cols-3 place-content-start items-start gap-12">

    <livewire:components.theme-selection />

    <div class="rounded-box bg-base-200 shadow-base relative col-span-3 flex h-[20vh] shadow-lg">

        <figure class="rounded-box group relative h-full w-full object-cover" x-data="{ backgroundImage: false }"
            x-on:click="backgroundImage = !backgroundImage" x-on:click.away="backgroundImage = false">

            <img draggable="false"
                src="https://images.unsplash.com/photo-1741866987680-5e3d7f052b87?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Profile Background" class="rounded-box h-full w-full object-cover" />


            <div class="bg-base/60 rounded-box absolute inset-0 flex h-full w-full cursor-pointer items-center justify-center opacity-0 backdrop-blur-md transition-opacity duration-150 group-hover:opacity-100"
                x-bind:class="{ 'opacity-100': backgroundImage }">

                <x-heroicon-s-photo class="h-12 w-12" />

            </div>

            <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 translate-y-full" x-show="backgroundImage"
                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="bg-base-200 rounded-box relative z-10 drop-shadow-lg">

                    <x-ri-triangle-fill
                        class="text-base-200 absolute left-1/2 top-0 z-0 size-6 -translate-x-1/2 -translate-y-[88%]" />

                    <div class="flex flex-col">
                        <button class="btn btn-ghost w-full rounded-t-lg border-none px-4">
                            See Image
                        </button>

                        <button class="btn btn-ghost w-full rounded-b-lg border-none px-4">
                            Change Image
                        </button>
                    </div>

                </div>
            </div>

        </figure>



        <div class="rounded-box shadow-base absolute bottom-0 left-5 h-[8vw] w-[8vw] translate-y-1/2 border-2 border-white bg-gray-200 shadow-lg"
            x-data="{ profileImage: false }" x-on:click="profileImage = !profileImage" x-on:click.away="profileImage = false">

            <figure class="rounded-box group relative h-full w-full object-cover">

                <img draggable="false"
                    src="https://images.unsplash.com/photo-1610216705422-caa3fcb6d158?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                    alt="Profile" class="rounded-box h-full w-full object-cover" />

                <div class="bg-base/60 rounded-box absolute inset-0 flex h-full w-full cursor-pointer items-center justify-center opacity-0 backdrop-blur-md transition-opacity duration-150 group-hover:opacity-100"
                    x-bind:class="{ 'opacity-100': profileImage }">

                    <x-heroicon-s-photo class="h-12 w-12" />

                </div>

                <div class="absolute -right-10 top-1/2 -translate-y-[10%] translate-x-full" x-show="profileImage"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="bg-base-200 rounded-box relative z-10 w-full min-w-40 drop-shadow-lg">

                        <x-ri-triangle-fill
                            class="text-base-200 absolute left-2 top-1/2 z-0 size-6 -translate-x-full -translate-y-1/2 rotate-[30deg]" />

                        <div class="flex w-full flex-col">
                            <button class="btn btn-ghost w-full rounded-t-lg border-none px-4">
                                See Image
                            </button>

                            <button class="btn btn-ghost w-full rounded-b-lg border-none px-4">
                                Change Image
                            </button>
                        </div>

                    </div>
                </div>

            </figure>



        </div>
    </div>

    <div class="col-span-3 ms-5 mt-[2vw] flex justify-between">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold">Hi, {{ $user->name }}</h2>
            <p class="text-sm">Manage your profile here.</p>
        </div>

        <div class="-mt-[3.5vw] flex flex-col items-end gap-2">


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

            <div x-data="{ interval: null }" x-init="interval = setInterval(async () => {
                const status = await getApiStatus();
                if (status) {
                    console.log('Status: Online');
                    $wire.updateStatus(true);
                } else {
                    console.log('Status: Offline');
                    $wire.updateStatus(false);
                }
            }, 3000);">
            </div>


            <div class="flex flex-col items-end gap-4">
                <div class="kbd kbd-md cursor-pointer" onclick="folderModal.showModal()">Root Folder :
                    {{ $user->projects_path }}</div>
                <div class="kbd kbd-md cursor-pointer" onclick="apiModal.showModal()">API Port: {{ $user->port }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-3">
        <h3 class="mb-2 text-3xl font-bold">Your Projects</h3>
        <p class="text-sm">See your projects created with Stacker</p>

        <div class="my-6 grid w-full grid-cols-5 gap-6">
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
                    <input type="text" wire:model="apiPort" x-on:keyup.enter="$wire.changeApiPort; apiModal.close()"
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
        async function getApiStatus() {
            try {
                const response = await axios.get('http://127.0.0.1:2025/api/status');
                return response.data.status;
            } catch (error) {
                console.log(error);
                return false;
            }
        }
    </script>


</div>
