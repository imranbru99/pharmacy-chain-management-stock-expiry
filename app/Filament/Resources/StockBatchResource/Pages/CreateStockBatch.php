<?php

declare(strict_types=1);

namespace App\Filament\Resources\StockBatchResource\Pages;

use App\Filament\Resources\StockBatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockBatch extends CreateRecord
{
    protected static string $resource = StockBatchResource::class;
}
