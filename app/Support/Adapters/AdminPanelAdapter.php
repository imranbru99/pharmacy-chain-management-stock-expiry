<?php

declare(strict_types=1);

namespace App\Support\Adapters;

use Illuminate\Support\HtmlString;
use ImranDevBD\LaravelAdminPanel\Facades\AdminPanel;
use ImranDevBD\LaravelAdminPanel\Panel\Panel;

final class AdminPanelAdapter
{
    public function getPanel(?string $id = null): Panel
    {
        return AdminPanel::getPanel($id);
    }

    public function registerPanel(Panel $panel): string
    {
        return AdminPanel::registerPanel($panel);
    }

    public function renderHook(string $name, array $scopes = []): HtmlString
    {
        return AdminPanel::renderHook($name, $scopes);
    }
}
