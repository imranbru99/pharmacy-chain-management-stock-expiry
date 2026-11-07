<x-filament-panels::page>
    <form wire:submit.prevent="loadSuggestions">
        {{ $this->form }}
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg border">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">Product ID</th>
                    <th class="px-4 py-2 text-left font-medium">Suggested qty</th>
                    <th class="px-4 py-2 text-left font-medium">Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suggestions as $row)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $row['product_id'] }}</td>
                        <td class="px-4 py-2">{{ $row['suggested_qty'] }}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $row['reason'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">No reorder suggestions for this branch.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
