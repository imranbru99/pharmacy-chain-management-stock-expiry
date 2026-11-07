<?php

declare(strict_types=1);

namespace App\Filament\Resources\DrugLicenseResource\Pages;

use App\Filament\Resources\DrugLicenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDrugLicenses extends ListRecords
{
    protected static string $resource = DrugLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
