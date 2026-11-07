<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductRecallResource\Pages;

use App\Filament\Resources\ProductRecallResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductRecall extends CreateRecord
{
    protected static string $resource = ProductRecallResource::class;
}
