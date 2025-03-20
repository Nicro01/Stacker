<div class="card bg-base-200 col-span-1 w-full shadow-sm" x-data="{ open: false }">
    <figure>
        <img src="{{ asset('images/stacks/' . $package->image) }}" alt="{{ $package->name }}" draggable="false" />
    </figure>
    <div class="card-body">
        <h2 class="card-title">
            {{ $package->name }}
            <div class="badge badge-secondary">NEW</div>
        </h2>
        <p>
            {{ $package->description }}
        </p>
        <div class="card-actions justify-end">
            <div class="badge badge-outline badge-secondary">{{ $package->name }}</div>
            <div class="badge badge-outline badge-secondary">Technology</div>
        </div>
    </div>

    <div x-show="open" class="card-body py-0" x-collapse>
        <form wire:submit.prevent="createProject" class="mx-auto flex w-full flex-col gap-4">


            {{-- Select Stack --}}
            @if ($stacks)
                <label class="label">
                    <span class="label-text">Stack</span>
                </label>
                <details class="dropdown border-base-300 w-full">
                    <summary class="btn bg-base-100 w-full">
                        @if (!$selectedStack)
                            <div class="flex w-full items-center justify-between">
                                <span>Select Stack</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        @else
                            <div class="flex w-full items-center justify-between">
                                <div class="flex w-full items-center gap-2">
                                    <img src="{{ asset('images/stacks/' . $stacks[$selectedStack - 1]['image']) }}"
                                        alt="{{ $stacks[$selectedStack - 1]['name'] }}" draggable="false"
                                        class="size-6" />
                                    <span class="uppercase">
                                        {{ $stacks[$selectedStack - 1]['name'] }}
                                    </span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        @endif
                    </summary>
                    <ul class="menu dropdown-content bg-base-100 rounded-field z-10 mt-2 w-full gap-2 p-2 shadow-sm">
                        @foreach ($stacks as $stack)
                            <li wire:key="{{ $stack->id }}"
                                x-on:click="$wire.set('selectedStack', {{ $stack->id }})">
                                <div class="flex cursor-pointer items-center gap-2 uppercase">
                                    <img src="{{ asset('images/stacks/' . $stack->image) }}" alt="{{ $stack->name }}"
                                        draggable="false" class="size-6" />
                                    <span>{{ $stack->name }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </details>
            @endif

            {{-- Select Env --}}

            <label class="label">
                <span class="label-text">Config</span>
            </label>
            <div class="flex items-center gap-4">
                <details class="dropdown border-base-300 h-full w-full">
                    <summary class="btn bg-base-100 w-full">
                        @if (!$selectedConfig)
                            <div class="flex w-full items-center justify-between">
                                <span>Select Config</span>
                                <x-heroicon-m-chevron-down class="size-6" />
                            </div>
                        @else
                            <div class="flex w-full items-center justify-between">
                                <div class="flex w-full items-center gap-2">
                                    <span class="uppercase">
                                        {{ $selectedConfig['name'] }}
                                    </span>
                                </div>
                                <x-heroicon-m-chevron-down class="size-6" />
                            </div>
                        @endif
                    </summary>

                    @if ($configs)
                        <ul
                            class="menu dropdown-content bg-base-100 rounded-field z-10 mt-2 w-full gap-2 p-2 shadow-sm">
                            @foreach ($configs as $config)
                                <li wire:key="{{ $config->id }}"
                                    x-on:click="$wire.set('selectedConfig', {{ $config }})">
                                    <div class="flex cursor-pointer items-center gap-2 uppercase">

                                        <span>{{ $config->name }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </details>

                <div class="btn btn-success max-h-[34px]" onclick="envModal.showModal()">
                    <x-heroicon-s-document-plus class="size-6" />
                </div>



                {{-- Env Modal --}}
                <dialog id="envModal" class="modal z-[999] w-screen">
                    <div class="modal-box">

                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                            onclick="envModal.close()">✕</button>

                        <h3 class="mb-3 text-lg font-bold">Drop your .env file here or click to select</h3>

                        <label for="dropzone-file"
                            class="flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed">
                            <div class="flex flex-col items-center justify-center pb-6 pt-5">
                                <svg class="mb-4 h-8 w-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm"><span class="font-semibold">Click to upload</span> or drag and
                                    drop</p>
                                <p class="text-xs">.env or TXT (MAX. 1MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" class="hidden" wire:model.live="envFile" />
                        </label>
                    </div>

                </dialog>

            </div>



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

                    @if (!$loading)
                        <x-heroicon-s-play class="size-6" />
                    @else
                        <span class="loading loading-dots loading-lg"></span>
                    @endif

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

    @if ($projectId)
        <livewire:components.terminal :project-id="$projectId" />
    @endif

    <script>
        Livewire.on('close-modal', () => {
            envModal.close();
        });
    </script>
</div>
