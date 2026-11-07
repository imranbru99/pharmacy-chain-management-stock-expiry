<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineOrderResource\Pages;

use App\Filament\Resources\OnlineOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOnlineOrder extends CreateRecord
{
    protected static string $resource = OnlineOrderResource::class;
}
