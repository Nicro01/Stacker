<div class="grid min-h-screen grid-cols-3 place-content-start items-start gap-12">

    <div class="rounded-box bg-base-200 shadow-base relative col-span-3 flex h-[20vh] shadow-lg">

        <figure class="rounded-box h-full w-full object-cover">
            <img draggable="false"
                src="https://images.unsplash.com/photo-1741866987680-5e3d7f052b87?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Profile Background" class="rounded-box h-full w-full object-cover" />
        </figure>

        <div
            class="rounded-box shadow-base absolute bottom-0 left-5 h-[8vw] w-[8vw] translate-y-1/2 border-2 border-white bg-gray-200 shadow-lg">

            <figure class="rounded-box h-full w-full object-cover">
                <img draggable="false"
                    src="https://images.unsplash.com/photo-1610216705422-caa3fcb6d158?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                    alt="Profile" class="rounded-box h-full w-full object-cover" />
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

            <div x-data="{ interval: null }" x-init="interval = setInterval(() => {
                @this.getApiStatus()
            }, 3000);">
            </div>

            <div class="flex flex-col items-end gap-4">
                <div class="kbd kbd-md">Root Folder : {{ $user->projects_path }}</div>
                <div class="kbd kbd-md">Port: {{ $user->port }}</div>
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

        <button class="btn w-full">
            Load More
        </button>
    </div>

</div>
