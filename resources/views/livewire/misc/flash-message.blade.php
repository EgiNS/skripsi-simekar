<div
    x-data="{ show: @entangle('show') }"
    x-init="if (show) { setTimeout(() => show = false, 3000); }"
    x-effect="if (show) { setTimeout(() => show = false, 3000); }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="fixed bottom-4 right-4 z-50"
>
    <div class="bg-{{ $type === 'success' ? 'green' : ($type === 'error' ? 'red' : 'yellow') }}-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center justify-between">
        <span>{{ $message }}</span>
        <button @click="show = false" class="ml-4 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>