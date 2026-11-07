<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineOrderResource\Pages;

use App\Filament\Resources\OnlineOrderResource;
use Filament\Resources\Pages\EditRecord;

class EditOnlineOrder extends EditRecord
{
    protected static string $resource = OnlineOrderResource::class;
}
