<?php

declare(strict_types=1);

namespace App\Filament\Resources\StockBatchResource\Pages;

use App\Filament\Resources\StockBatchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockBatch extends EditRecord
{
    protected static string $resource = StockBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
