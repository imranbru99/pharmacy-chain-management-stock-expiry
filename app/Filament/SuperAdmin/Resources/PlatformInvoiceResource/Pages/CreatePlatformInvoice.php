<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\PlatformInvoiceResource\Pages;

use App\Filament\SuperAdmin\Resources\PlatformInvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlatformInvoice extends CreateRecord
{
    protected static string $resource = PlatformInvoiceResource::class;
}
