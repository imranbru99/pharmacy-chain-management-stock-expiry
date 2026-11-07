<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\OnlineOrderResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class OnlineOrderResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = App\Modules\OnlineOrders\Models\OnlineOrder::class;

    protected static ?string $navigationLabel = 'Online Orders';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Online & Delivery';

    protected static function moduleKey(): string
    {
        return 'online_orders';
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
            'index' => Pages\ListOnlineOrders::route('/'),
            'create' => Pages\CreateOnlineOrder::route('/create'),
            'edit' => Pages\EditOnlineOrder::route('/{record}/edit'),
        ];
    }
}
