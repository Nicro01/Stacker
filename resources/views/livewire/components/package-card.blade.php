<div class="card bg-base-200 w-full shadow-sm" x-data="{ open: @entangle('isOpen'), isLoading: @entangle('isLoading') }">
    <figure class="max-h-[300px]">
        <img src="{{ asset('images/packages/' . $package->image) }}" alt="{{ $package->name }}" draggable="false" />
    </figure>

    <div class="card-body">
        <h2 class="card-title aldrich">
            {{ $package->name }}
            <div class="badge badge-secondary">NEW</div>
        </h2>
        <p>{{ $package->description }}</p>
    </div>

    <div x-show="open" x-collapse>
        <form x-on:submit.prevent="requestLaravelProject($wire)" class="card-body flex flex-col gap-4 pt-0">


            @if ($selectedStack == '3' || $selectedStack == '4')
                <div role="alert" class="alert alert-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Available only for Laravel ^12.0!</span>
                </div>
            @endif


            {{-- Select Stack --}}
            @if ($stacks)
                <label class="label"><span class="label-text">Stack</span></label>
                <select wire:model.live="selectedStack" class="select select-bordered w-full">
                    <option value="" disabled>Select Stack</option>
                    @foreach ($stacks as $stack)
                        <option value="{{ $stack->id }}">
                            <span>{{ $stack->name }}</span>
                        </option>
                    @endforeach
                </select>
            @endif

            @if (auth()->user->github_token)
                <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-64 w-full border p-4">
                    <legend class="fieldset-legend">Github Repository</legend>
                    <label class="fieldset-label">
                        <input type="checkbox" wire:model.live="createRepository" class="toggle" />
                        <span>Create Github Repository?</span>
                    </label>
                </fieldset>
            @endif


            @php
                $selected = collect($stacks)->firstWhere('id', $selectedStack);
            @endphp

            @if ($selected && !empty($selected->inputs))
                @foreach (json_decode($selected->inputs) as $key => $value)
                    @if ($key === 'auth')
                        <livewire:panel.inputs.auth :auth="$auth" />
                    @endif
                @endforeach
            @endif


            {{-- Config --}}
            <label class="label"><span class="label-text">Config</span></label>
            <div class="flex items-center gap-4">
                <select wire:model="selectedConfigId" class="select select-bordered w-full">
                    <option value="" selected>Select Config</option>
                    @foreach ($configs as $config)
                        <option value="{{ $config->id }}">{{ $config->name }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="envModal.showModal()" class="btn btn-success">
                    <x-heroicon-s-document-plus class="size-6" />
                </button>
            </div>

            {{-- Create Config Modal --}}
            <dialog id="envModal" class="modal z-[999] w-screen">
                <div class="modal-box">
                    <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        onclick="envModal.close()">✕</button>
                    <h3 class="mb-3 text-lg font-bold">Drop your .env file here or click to select</h3>
                    <input type="file" wire:model.live="envFile" wire:loading.remove
                        wire:target="createConfigFromEnv()" class="file-input w-full" />

                    <span wire:loading wire:target="createConfigFromEnv()"
                        class="loading loading-dots loading-lg"></span>
                </div>
            </dialog>

            {{-- Path --}}
            <input type="text" wire:model="projectPath" placeholder="C:/" class="input input-bordered w-full" />

            {{-- Name --}}
            <input type="text" wire:model="projectName" placeholder="Project Name"
                class="input input-bordered w-full" />


            {{-- Submit --}}
            <button type="submit" class="btn btn-success run w-full">

                <x-heroicon-s-play x-show="!isLoading" class="size-6" />

                <span x-show="isLoading" class="loading loading-dots loading-lg"></span>

            </button>
        </form>
    </div>

    <div class="card-actions mt-4">
        <button class="btn w-full rounded-b-lg rounded-t-none border-x-0 border-b-0" x-on:click="open = !open">
            <span x-text="open ? 'Hide' : 'Show'"></span>
        </button>
    </div>

    <script>
        Livewire.on('close-modal', () => envModal?.close?.());

        async function requestLaravelProject(component) {
            component.set('isLoading', true);

            var port = component.get('port');

            const info = {
                projectPath: component.get('projectPath'),
                projectName: component.get('projectName'),
                stack: component.get('selectedStack'),
                auth: component.get('auth'),
                createRepository: component.get('createRepository'),
                gitHubToken: component.get('gitHubToken'),
                gitHubUsername: component.get('gitHubUsername'),
            };

            // console.log('Requesting Laravel project with info:', info);

            const allRunButtons = document.querySelectorAll('.run');

            allRunButtons.forEach(button => button.setAttribute('disabled', true));

            try {
                await axios.post('http://127.0.0.1:' + port + '/api/create-project', info, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                // console.log('Project creation request sent successfully.');

                await new Promise(resolve => setTimeout(resolve, 2000));

            } catch (error) {
                console.error('Error creating project:', error);
            } finally {
                allRunButtons.forEach(button => button.removeAttribute('disabled'));
                component.set('isLoading', false);
            }
        }
    </script>

</div>
