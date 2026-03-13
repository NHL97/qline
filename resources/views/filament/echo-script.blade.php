@vite(['resources/js/app.js'])
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const interval = setInterval(function () {
            if (window.Echo) {
                clearInterval(interval);

                @auth
                @if(auth()->user()?->business)
                window.Echo.channel('queue.{{ auth()->user()->business->slug }}')
                    .listen('.queue.updated', (data) => {
                        Livewire.dispatch('queue-updated');
                    });
                @endif
                @endauth
            }
        }, 100);
    });
</script>