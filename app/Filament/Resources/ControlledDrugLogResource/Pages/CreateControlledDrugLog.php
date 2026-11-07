<?php

declare(strict_types=1);

namespace App\Filament\Resources\ControlledDrugLogResource\Pages;

use App\Filament\Resources\ControlledDrugLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateControlledDrugLog extends CreateRecord
{
    protected static string $resource = ControlledDrugLogResource::class;
}
