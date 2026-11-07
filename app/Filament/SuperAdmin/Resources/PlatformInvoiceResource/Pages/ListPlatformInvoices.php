<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\PlatformInvoiceResource\Pages;

use App\Filament\SuperAdmin\Resources\PlatformInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlatformInvoices extends ListRecords
{
    protected static string $resource = PlatformInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
