<?php

declare(strict_types=1);

namespace App\Filament\Resources\DrugLicenseResource\Pages;

use App\Filament\Resources\DrugLicenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDrugLicense extends CreateRecord
{
    protected static string $resource = DrugLicenseResource::class;
}
