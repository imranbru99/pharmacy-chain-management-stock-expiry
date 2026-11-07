<?php

declare(strict_types=1);

namespace App\Filament\Resources\ColdChainLogResource\Pages;

use App\Filament\Resources\ColdChainLogResource;
use Filament\Resources\Pages\EditRecord;

class EditColdChainLog extends EditRecord
{
    protected static string $resource = ColdChainLogResource::class;
}
