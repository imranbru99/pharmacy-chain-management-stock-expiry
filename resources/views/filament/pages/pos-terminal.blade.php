<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2">
        <x-filament::section heading="Scan / Search">
            <div class="flex gap-2">
                <x-filament::input.wrapper class="flex-1">
                    <x-filament::input
                        type="text"
                        wire:model="barcode"
                        wire:keydown.enter="searchProduct"
                        placeholder="Barcode or SKU"
                    />
                </x-filament::input.wrapper>
                <x-filament::button wire:click="searchProduct" color="primary">Add</x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section heading="Cart">
            @if (count($cart) === 0)
                <p class="text-sm text-gray-500">Scan a product to begin.</p>
            @else
                <ul class="divide-y">
                    @foreach ($cart as $line)
                        <li class="flex justify-between py-2 text-sm">
                            <span>{{ $line['name'] }} × {{ $line['quantity'] }}</span>
                            <span>{{ money_format($line['line_total_minor']) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 flex items-center justify-between font-semibold">
                    <span>Total</span>
                    <span>{{ money_format($this->cartTotal) }}</span>
                </div>
                <x-filament::button class="mt-4 w-full" wire:click="completeSale" color="success">
                    Complete sale
                </x-filament::button>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
