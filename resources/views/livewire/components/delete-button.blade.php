<div x-data="{ deleteModal: false }">
    <x-heroicon-s-trash class="w-6 min-w-6 cursor-pointer text-red-400" x-on:click="deleteModal = true" />

    <div x-show="deleteModal" x-on:keydown.escape.window="deleteModal = false"
        class="fixed left-0 top-0 z-50 flex h-screen w-screen items-center justify-center bg-gray-500/50">
        <div class="card h-auto min-w-[50%] rounded-xl bg-white shadow-sm">
            <div x-on:click.away="deleteModal = false" class="card-body relative bg-white">

                <x-heroicon-o-x-mark class="absolute right-5 top-5 size-8 cursor-pointer stroke-2"
                    x-on:click="deleteModal = false" />

                <div class="col-span-full flex h-[20vh] items-center justify-center">
                    <span class="text-2xl font-bold text-gray-700">
                        Are you sure you want to delete this record?
                    </span>
                </div>

                <div class="card-actions col-span-4">
                    <button
                        x-on:click="$wire.delete({{ $id }}, '{{ $model }}', '{{ $redirect }}');"
                        class="btn btn-neutral w-full">Save</button>

                    <button x-on:click="deleteModal = false" class="btn w-full">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
