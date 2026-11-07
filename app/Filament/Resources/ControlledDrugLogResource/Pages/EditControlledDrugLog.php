<?php

declare(strict_types=1);

namespace App\Filament\Resources\ControlledDrugLogResource\Pages;

use App\Filament\Resources\ControlledDrugLogResource;
use Filament\Resources\Pages\EditRecord;

class EditControlledDrugLog extends EditRecord
{
    protected static string $resource = ControlledDrugLogResource::class;
}
