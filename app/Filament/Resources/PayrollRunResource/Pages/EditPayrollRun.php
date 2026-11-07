<?php

declare(strict_types=1);

namespace App\Filament\Resources\PayrollRunResource\Pages;

use App\Filament\Resources\PayrollRunResource;
use Filament\Resources\Pages\EditRecord;

class EditPayrollRun extends EditRecord
{
    protected static string $resource = PayrollRunResource::class;
}
