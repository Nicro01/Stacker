<div class="grid min-h-screen grid-cols-3 place-content-start items-start gap-12 py-16">
    <div class="col-span-3">
        <h2 class="aldrich text-4xl font-bold">Project Configurations</h2>
        <p class="text-lg">Manage your project configurations here.</p>
    </div>

    <div class="col-span-3 flex flex-col gap-4">
        <label class="input rounded-field w-full">
            <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                    stroke="currentColor">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </g>
            </svg>
            <input type="search" class="grow focus:ring-0" placeholder="Search" />
            <kbd class="kbd kbd-sm">Enter</kbd>
        </label>

        <div class="rounded-box border-base-content/5 bg-base-100 overflow-x-auto border">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($configs) == 0)
                        <tr>
                            <td colspan="3" class="text-center">
                                <span class="text-lg">No configurations files found.</span>
                            </td>
                        </tr>
                    @else
                        @foreach ($configs as $config)
                            <tr>
                                <th>{{ $config->id }}</th>
                                <td>{{ $config->name }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <button onclick="" class="btn btn-sm btn-error">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
