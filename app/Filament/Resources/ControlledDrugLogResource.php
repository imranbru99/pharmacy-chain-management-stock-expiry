<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\ControlledDrugLogResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ControlledDrugLogResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = App\Modules\Compliance\Models\ControlledDrugLog::class;

    protected static ?string $navigationLabel = 'Controlled Drug Logs';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Compliance';

    protected static function moduleKey(): string
    {
        return 'compliance';
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
            'index' => Pages\ListControlledDrugLogs::route('/'),
            'create' => Pages\CreateControlledDrugLog::route('/create'),
            'edit' => Pages\EditControlledDrugLog::route('/{record}/edit'),
        ];
    }
}
