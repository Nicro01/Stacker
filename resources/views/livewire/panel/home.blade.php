<div class="grid grid-cols-3 gap-6">
    <div class="card bg-base-100 col-span-1 w-full shadow-sm" x-data="{ open: false }">
        <figure>
            <img src="{{ asset('images/stacks/laravel.png') }}" alt="Shoes" draggable="false" />
        </figure>
        <div class="card-body">
            <h2 class="card-title">
                Laravel
                <div class="badge badge-secondary">NEW</div>
            </h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </p>
            <div class="card-actions justify-end">
                <div class="badge badge-outline">Laravel</div>
                <div class="badge badge-outline">Technology</div>
            </div>
        </div>

        <div x-show="open" class="card-body py-0" x-collapse>
            <form wire:submit.prevent="createProject" class="mx-auto flex w-full flex-col gap-4">
                <div class="form-control">
                    <label class="label mb-2">
                        <span class="label-text">Project Path</span>
                    </label>
                    <div class="flex">
                        <input type="text" placeholder="C:/" wire:model="projectPath"
                            class="input input-bordered w-full" />

                        {{-- <input type="file" class="file-input" webkitdirectory /> --}}
                        {{-- <button class="btn rounded-s-none" onclick="selectFolder()">Browse</button> --}}
                    </div>
                </div>
                <div class="form-control flex w-full flex-col">
                    <label class="label mb-2">
                        <span class="label-text">Project Name</span>
                    </label>
                    <input type="text" placeholder="Stacker" wire:model="projectName"
                        class="input input-bordered w-full" />
                </div>

                <div class="form-control">
                    <button type="submit" class="btn btn-success w-full">

                        <span wire:loading.remove wire:target="createProject">Run</span>

                        <div class="flex w-full flex-row items-center justify-center gap-2" wire:loading.flex
                            wire:target="createProject">
                            <div class="bg-base-content size-2 animate-bounce rounded-full"></div>
                            <div class="bg-base-content size-2 animate-bounce rounded-full [animation-delay:-.3s]">
                            </div>
                            <div class="bg-base-content size-2 animate-bounce rounded-full [animation-delay:-.5s]">
                            </div>
                        </div>

                    </button>
                </div>
            </form>
        </div>

        <div class="card-actions" :class="{ 'mt-4': open }">
            <button class="btn w-full rounded-t-none" x-on:click="open = ! open">
                <span x-show="open" x-text="`Hide`">Show</span>
                <span x-show="! open" x-text="`Show`">Hide</span>
            </button>
        </div>
    </div>

    {{-- <script>
        async function selectFolder() {
            try {
                const handle = await window.showDirectoryPicker(); // Open folder selection dialog
                const path = handle.name; // Get folder name (or handle.path for full path if available)

                document.getElementById('projectPath').value = path;
                @this.set('projectPath', path);
            } catch (error) {
                console.error("Folder selection was canceled or failed:", error);
            }
        }
    </script> --}}
</div>
