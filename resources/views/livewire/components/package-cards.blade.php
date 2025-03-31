<div class="col-span-3 grid w-full grid-cols-1 place-content-start items-start gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($packages as $package)
        <livewire:components.package-card :package="$package" :wire:key="$package->id" />
    @endforeach
</div>
