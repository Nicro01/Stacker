<div class="grid min-h-screen grid-cols-3 items-start gap-6 py-16">
    {{-- <div class="col-span-3">
        <h2 class="text-center text-5xl font-bold">
            Stacker Panel
        </h2>
    </div> --}}

    @foreach ($packages as $package)
        <livewire:components.package-cards :id="$package->id" :key="$package->id" />
    @endforeach



</div>
