<?php

declare(strict_types=1);

namespace App\Filament\Resources\CmsPostResource\Pages;

use App\Filament\Resources\CmsPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsPost extends CreateRecord
{
    protected static string $resource = CmsPostResource::class;
}
