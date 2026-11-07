<?php

declare(strict_types=1);

namespace App\Filament\Resources\DrugLicenseResource\Pages;

use App\Filament\Resources\DrugLicenseResource;
use Filament\Resources\Pages\EditRecord;

class EditDrugLicense extends EditRecord
{
    protected static string $resource = DrugLicenseResource::class;
}
