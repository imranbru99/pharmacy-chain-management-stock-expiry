<?php

declare(strict_types=1);

namespace App\Filament\Resources\ColdChainLogResource\Pages;

use App\Filament\Resources\ColdChainLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateColdChainLog extends CreateRecord
{
    protected static string $resource = ColdChainLogResource::class;
}
