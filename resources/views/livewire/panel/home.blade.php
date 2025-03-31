<div class="grid min-h-screen grid-cols-3 place-content-start items-start gap-12 py-16">

    <div class="col-span-3">
        <h2 class="aldrich text-4xl font-bold">
            Packages
        </h2>
        <p class="text-lg">Select a package to get started.</p>
    </div>

    @foreach ($packages as $package)
        @if ($package->id !== 1)
            <livewire:components.package-cards :id="$package->id" :key="$package->id" />
        @endif
    @endforeach


</div>
