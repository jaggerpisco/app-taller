<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Calculadora de ductos --}}
        <div class="flex-1">
            <livewire:duct-calculator />
        </div>
    </div>
</x-layouts::app>
