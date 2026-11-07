<x-filament-panels::page>
    <form wire:submit="submit" class="space-y-4">
        {{ $this->form }}
        <x-filament::button type="submit">Ask</x-filament::button>
    </form>
    @if ($lastResponse !== '')
        <div class="mt-6 rounded-lg border p-4 whitespace-pre-wrap">{{ $lastResponse }}</div>
    @endif
</x-filament-panels::page>
