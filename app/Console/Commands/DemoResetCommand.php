<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\Modules\ModuleRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

final class DemoResetCommand extends Command
{
    protected $signature = 'demo:reset {--force : Force reset in production}';

    protected $description = 'Reset database and re-seed demo data';

    public function handle(): int
    {
        if (! $this->option('force') && $this->laravel->environment('production')) {
            $this->error('Refusing to reset production without --force');

            return self::FAILURE;
        }

        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true], $this->output);
        app(ModuleRegistry::class)->flushCache();

        $this->info('Demo environment reset complete.');

        return self::SUCCESS;
    }
}
