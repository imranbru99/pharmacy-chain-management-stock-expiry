<x-filament-panels::page>
    <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($reports as $key => $label)
            <a href="#" class="rounded-lg border px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800">
                <span class="font-medium">{{ $label }}</span>
                <span class="block text-xs text-gray-500">{{ $key }}</span>
            </a>
        @endforeach
    </div>
</x-filament-panels::page>
