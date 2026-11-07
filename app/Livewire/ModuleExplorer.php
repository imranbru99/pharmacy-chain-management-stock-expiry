<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Core\Modules\ModuleRegistry;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class ModuleExplorer extends Component
{
    /** @var list<string> */
    public array $enabled = [];

    public function mount(): void
    {
        $defaults = [];

        foreach (app(ModuleRegistry::class)->all() as $key => $manifest) {
            if (($manifest['default_enabled'] ?? false) === true || ($manifest['always_on'] ?? false) === true) {
                $defaults[] = $key;
            }
        }

        $this->enabled = $defaults;
    }

    public function toggle(string $key): void
    {
        if (in_array($key, $this->enabled, true)) {
            $this->enabled = array_values(array_filter($this->enabled, fn (string $k): bool => $k !== $key));
        } else {
            $this->enabled[] = $key;
        }
    }

    /**
     * @return array<string, list<array<string, string>>>
     */
    public function getVisibleMenusProperty(): array
    {
        $menus = [];

        foreach (app(ModuleRegistry::class)->all() as $key => $manifest) {
            if (! in_array($key, $this->enabled, true)) {
                continue;
            }

            $group = $manifest['navigation']['group'] ?? 'Modules';
            $menus[$group][] = [
                'key' => $key,
                'label' => $manifest['name'] ?? $key,
                'icon' => $manifest['navigation']['icon'] ?? 'heroicon-o-squares-2x2',
            ];
        }

        ksort($menus);

        return $menus;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getModulesProperty(): array
    {
        $modules = [];

        foreach (app(ModuleRegistry::class)->all() as $key => $manifest) {
            $modules[] = [
                'key' => $key,
                'name' => $manifest['name'] ?? $key,
                'description' => $manifest['description'] ?? '',
                'group' => $manifest['navigation']['group'] ?? 'Modules',
            ];
        }

        return $modules;
    }

    public function render(): View
    {
        return view('livewire.module-explorer');
    }
}
