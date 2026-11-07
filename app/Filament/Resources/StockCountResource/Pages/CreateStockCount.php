<?php

declare(strict_types=1);

namespace App\Filament\Resources\StockCountResource\Pages;

use App\Filament\Resources\StockCountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockCount extends CreateRecord
{
    protected static string $resource = StockCountResource::class;
}
