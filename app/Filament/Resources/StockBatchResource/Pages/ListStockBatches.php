<?php

declare(strict_types=1);

namespace App\Filament\Resources\StockBatchResource\Pages;

use App\Filament\Resources\StockBatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockBatches extends ListRecords
{
    protected static string $resource = StockBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
