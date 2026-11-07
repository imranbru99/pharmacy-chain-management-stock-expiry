<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\SubscriptionResource\Pages;

use App\Filament\SuperAdmin\Resources\SubscriptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
