<div class="grid min-h-screen grid-cols-1 place-content-start items-start gap-6 py-6 sm:grid-cols-3 sm:py-16">

    <div class="col-span-1 sm:col-span-3">
        <h2 class="aldrich text-4xl font-bold">
            Packages
        </h2>
        <p class="text-lg">Select a package to get started.</p>
    </div>

    @foreach ($packages as $package)
        <livewire:components.package-card :package="$package" :wire:key="$package->id" />
    @endforeach


</div>
