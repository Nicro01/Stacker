<div class="@if ($projectId) max-h-[30vh] @else h-0 @endif w-full select-text overflow-auto">

    <div class="mockup-code w-full">
        @foreach ($logs as $log)
            <pre data-prefix="|">{{ $log }}</pre>
        @endforeach
    </div>

    @if ($projectId)
        <div x-data="{ interval: null }" x-init="interval = setInterval(() => pollLogs($wire), 1000);
        
        Livewire.on('stop-log-polling', () => {
            clearInterval(interval);
        });">
        </div>
    @endif

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('start-log-polling', (event) => {
                const projectId = event.detail.projectId;
                pollLogs(projectId);
            });
        });

        async function pollLogs(component) {
            const projectId = component.get('projectId');
            if (!projectId) return;

            try {
                const response = await axios.get(`http://127.0.0.1:2025/api/logs?id=${projectId}`);

                if (response.status === 200) {
                    const newLogs = response.data.logs || [];

                    component.set('logs', newLogs);

                    if (newLogs.includes("complete")) {
                        component.dispatch('stop-log-polling');
                    }
                }
            } catch (error) {
                console.error('Error polling logs:', error);
            }
        }
    </script>
</div>
