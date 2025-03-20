<div class="@if ($projectId) max-h-[30vh] @else h-0 @endif w-full select-text overflow-auto">
    <div class="mockup-code w-full">

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
