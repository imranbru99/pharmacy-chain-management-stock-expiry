<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineOrderResource\Pages;

use App\Filament\Resources\OnlineOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOnlineOrders extends ListRecords
{
    protected static string $resource = OnlineOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
