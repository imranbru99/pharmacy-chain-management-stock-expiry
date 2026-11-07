<?php

declare(strict_types=1);

namespace App\Core\Modules;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use InvalidArgumentException;

final class ModuleRegistry
{
    private const CACHE_KEY = 'app.module_registry';

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            return $this->loadFromDisk();
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $key): array
    {
        $modules = $this->all();

        if (! isset($modules[$key])) {
            throw new InvalidArgumentException("Unknown module [{$key}].");
        }

        return $modules[$key];
    }

    /**
     * @return list<string>
     */
    public function dependencies(string $key): array
    {
        $module = $this->get($key);
        $deps = $module['dependencies'] ?? [];

        return is_array($deps) ? array_values($deps) : [];
    }

    /**
     * @return list<string>
     */
    public function dependents(string $key): array
    {
        return Collection::make($this->all())
            ->filter(fn (array $module): bool => in_array($key, $module['dependencies'] ?? [], true))
            ->keys()
            ->values()
            ->all();
    }

    public function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function loadFromDisk(): array
    {
        $modules = [];
        $basePath = app_path('Modules');

        if (! File::isDirectory($basePath)) {
            return $modules;
        }

        foreach (File::directories($basePath) as $directory) {
            $manifestPath = $directory.DIRECTORY_SEPARATOR.'module.json';

            if (! File::exists($manifestPath)) {
                continue;
            }

            $decoded = json_decode(File::get($manifestPath), true);

            if (! is_array($decoded) || ! isset($decoded['key'])) {
                continue;
            }

            $modules[$decoded['key']] = $decoded;
        }

        ksort($modules);

        return $modules;
    }
}
