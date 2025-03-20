<div>
    <div class="fixed right-10 top-10 flex size-12 items-center justify-center rounded-full bg-white p-2 shadow-xl"
        x-on:click.away="themeDrop = false" x-on:click="themeDrop = !themeDrop">
        <div class="btn size-10 min-w-10 rounded-full">

        </div>
    </div>

    <div class="fixed right-10 top-28 flex flex-col gap-6" x-show="themeDrop" x-collapse>

        <div x-show="theme != 'lofi'" class="flex size-12 items-center justify-center rounded-full bg-white p-2 shadow"
            x-on:click="theme = 'lofi'">
            <input type="radio" name="theme-buttons"
                class="btn theme-controller join-item size-10 min-w-10 rounded-full bg-white" value="lofi" />
        </div>

        <div x-show="theme != 'dark'" class="flex size-12 items-center justify-center rounded-full bg-white p-2 shadow"
            x-on:click="theme = 'dark'">
            <input type="radio" name="theme-buttons"
                class="btn theme-controller join-item size-10 min-w-10 rounded-full bg-[#000]" value="dark" />
        </div>

        <div x-show="theme != 'retro'" class="flex size-12 items-center justify-center rounded-full bg-white p-2 shadow"
            x-on:click="theme = 'retro'">
            <input type="radio" name="theme-buttons"
                class="btn theme-controller join-item size-10 min-w-10 rounded-full bg-[#ECE3CA]" value="retro" />
        </div>

    </div>
</div>
