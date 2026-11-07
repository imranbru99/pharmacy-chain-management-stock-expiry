<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Modules\Catalog\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

/** Stub importer for catalog CSV import (extend columns as needed). */
class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')->requiredMapping(),
            ImportColumn::make('sku'),
            ImportColumn::make('barcode'),
        ];
    }

    public function resolveRecord(): ?Product
    {
        return new Product;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Product import finished.';
    }
}
