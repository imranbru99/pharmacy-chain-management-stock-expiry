<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\CmsPostResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CmsPostResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = App\Modules\Cms\Models\CmsPost::class;

    protected static ?string $navigationLabel = 'CMS Posts';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'CMS';

    protected static function moduleKey(): string
    {
        return 'cms';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('id')->disabled()->visibleOn('edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCmsPosts::route('/'),
            'create' => Pages\CreateCmsPost::route('/create'),
            'edit' => Pages\EditCmsPost::route('/{record}/edit'),
        ];
    }
}
