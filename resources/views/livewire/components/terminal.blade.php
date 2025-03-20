<div
    class="@if ($projectId) max-h-[30vh] @else h-0 @endif fixed bottom-0 left-0 w-screen overflow-y-auto">
    <div class="mockup-code w-full rounded-none">
        <pre data-prefix="$" class="text-success">
            <code>Stacker Console Log</code>
        </pre>
        @foreach ($logs as $log)
            <pre data-prefix="|">{{ $log }}</pre>
        @endforeach
    </div>

    @if ($projectId)
        <div x-data="{ interval: null }" x-init="interval = setInterval(() => {
            @this.pollLogs('{{ $projectId }}')
        }, 1000);
        
        Livewire.on('stop-log-polling', () => {
            clearInterval(interval); // Stop polling
        });">
        </div>
    @endif
</div>
