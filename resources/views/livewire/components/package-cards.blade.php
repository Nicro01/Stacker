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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    @else
                        <div class="flex w-full items-center justify-between">
                            <div class="flex w-full items-center gap-2">
                                <img src="{{ asset('images/stacks/' . $stacks[$selectedStack - 1]['image']) }}"
                                    alt="{{ $stacks[$selectedStack - 1]['name'] }}" draggable="false" class="size-6" />
                                <span class="uppercase">
                                    {{ $stacks[$selectedStack - 1]['name'] }}
                                </span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    @endif
                </summary>
                <ul class="menu dropdown-content bg-base-100 rounded-box z-10 w-full p-2 shadow-sm">
                    @foreach ($stacks as $stack)
                        <li wire:key="{{ $stack->id }}" x-on:click="$wire.set('selectedStack', {{ $stack->id }})">
                            <div class="flex cursor-pointer items-center gap-2 uppercase">
                                <img src="{{ asset('images/stacks/' . $stack->image) }}" alt="{{ $stack->name }}"
                                    draggable="false" class="size-6 rounded" />
                                <span>{{ $stack->name }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </details>



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
                        <span>Run</span>
                    @else
                        <div class="flex w-full flex-row items-center justify-center gap-2">
                            <div class="bg-base-content size-2 animate-bounce rounded-full"></div>
                            <div class="bg-base-content size-2 animate-bounce rounded-full [animation-delay:-.3s]">
                            </div>
                            <div class="bg-base-content size-2 animate-bounce rounded-full [animation-delay:-.5s]">
                            </div>
                        </div>
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
</div>
