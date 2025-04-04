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
                    <option value="" disabled>Select Config</option>
                    @foreach ($configs as $config)
                        <option value="{{ $config->id }}">{{ $config->name }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="envModal.showModal()" class="btn btn-success">
                    <x-heroicon-s-document-plus class="size-6" />
                </button>
            </div>

            <dialog id="envModal" class="modal z-[999] w-screen">
                <div class="modal-box">
                    <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        onclick="envModal.close()">✕</button>
                    <h3 class="mb-3 text-lg font-bold">Drop your .env file here or click to select</h3>
                    <input type="file" wire:model.live="envFile" class="file-input w-full" />
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
        <button class="btn w-full rounded-t-none" x-on:click="open = !open">
            <span x-text="open ? 'Hide' : 'Show'"></span>
        </button>
    </div>

    <script>
        Livewire.on('close-modal', () => envModal?.close?.());

        const allRunButtons = document.querySelectorAll('.run');

        async function requestLaravelProject(component) {
            component.set('isLoading', true);

            const info = {
                projectPath: component.get('projectPath'),
                projectName: component.get('projectName'),
                stack: component.get('selectedStack'),
                auth: component.get('authTALL'),
            };

            console.log('Requesting Laravel project with info:', info);

            allRunButtons.forEach(button => {
                button.setAttribute('disabled', true);
            });

            try {
                await axios.post('http://127.0.0.1:2025/api/create-project', JSON.stringify(info));
                console.log('Project creation request sent successfully.');

                // Wait for 2 seconds
                await new Promise(resolve => setTimeout(resolve, 2000));

                const response = await axios.get('http://127.0.0.1:2025/api/project-ids');
                const projectId = response.data.projectIDs?.[0] || null;

                component.set('projectId', projectId);
                component.dispatch('start-log-polling', {
                    projectId
                });

            } catch (error) {
                console.error('Error creating project:', error);
            }

            allRunButtons.forEach(button => {
                button.removeAttribute('disabled');
            });

            component.set('isLoading', false);
        }
    </script>
</div>
