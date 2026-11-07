<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Modules\Sales\Models\Sale;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string|UnitEnum|null $navigationGroup = 'Sales';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('invoice_number')->disabled(),
            Select::make('status')->options([
                'draft' => 'Draft',
                'held' => 'Held',
                'completed' => 'Completed',
                'returned' => 'Returned',
                'cancelled' => 'Cancelled',
            ]),
            TextInput::make('total_minor')->numeric()->label('Total (minor)'),
            TextInput::make('paid_minor')->numeric()->label('Paid (minor)'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('invoice_number')->searchable()->sortable(),
            TextColumn::make('status')->badge(),
            TextColumn::make('total_minor')->label('Total')->formatStateUsing(fn (int $state): string => money_format($state)),
            TextColumn::make('completed_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'view' => Pages\ViewSale::route('/{record}'),
        ];
    }
}
