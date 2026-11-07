<div class="grid gap-8 lg:grid-cols-2">
    <div class="card-glass">
        <h2 class="font-display text-2xl font-bold">Enabled modules</h2>
        <p class="mt-2 text-sm text-emerald-950/70">Click to simulate which back-office menus appear for your tenant.</p>
        <ul class="mt-6 max-h-[28rem] space-y-2 overflow-y-auto">
            @foreach ($this->modules as $module)
                <li>
                    <button type="button" wire:click="toggle('{{ $module['key'] }}')"
                        class="flex w-full items-center justify-between rounded-xl border px-4 py-3 text-left text-sm transition {{ in_array($module['key'], $enabled, true) ? 'border-[#0B6E4F] bg-mint-wash' : 'border-emerald-900/10 bg-white' }}">
                        <span>
                            <span class="font-semibold">{{ $module['name'] }}</span>
                            <span class="block text-xs text-emerald-900/60">{{ $module['group'] }}</span>
                        </span>
                        <span class="text-xs font-semibold text-[#0B6E4F]">{{ in_array($module['key'], $enabled, true) ? 'On' : 'Off' }}</span>
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="card-glass">
        <h2 class="font-display text-2xl font-bold">Resulting navigation</h2>
        <div class="mt-6 space-y-6">
            @forelse ($this->visibleMenus as $group => $items)
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-[#0B6E4F]">{{ $group }}</p>
                    <ul class="mt-2 space-y-1 text-sm">
                        @foreach ($items as $item)
                            <li class="rounded-lg bg-mint-wash px-3 py-2">{{ $item['label'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="text-sm text-emerald-950/70">Enable at least one module to preview menus.</p>
            @endforelse
        </div>
    </div>
</div>
