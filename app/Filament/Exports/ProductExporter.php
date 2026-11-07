<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Modules\Catalog\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

/** Stub exporter for catalog CSV export (extend columns as needed). */
class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name'),
            ExportColumn::make('sku'),
            ExportColumn::make('barcode'),
            ExportColumn::make('schedule'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Product export finished.';
    }
}
