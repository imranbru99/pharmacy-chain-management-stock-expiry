<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\StockTransferResource\Pages;
use App\Modules\Transfers\Models\StockTransfer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class StockTransferResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = StockTransfer::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string|UnitEnum|null $navigationGroup = 'Catalog & Inventory';

    protected static ?int $navigationSort = 40;

    protected static function moduleKey(): string
    {
        return 'transfers';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Transfer')->schema([
                Select::make('from_branch_id')->relationship('fromBranch', 'name')->required(),
                Select::make('to_branch_id')->relationship('toBranch', 'name')->required(),
                Select::make('status')->options([
                    StockTransfer::STATUS_REQUESTED => 'Requested',
                    StockTransfer::STATUS_APPROVED => 'Approved',
                    StockTransfer::STATUS_DISPATCHED => 'Dispatched',
                    StockTransfer::STATUS_IN_TRANSIT => 'In transit',
                    StockTransfer::STATUS_RECEIVED => 'Received',
                    StockTransfer::STATUS_CANCELLED => 'Cancelled',
                ]),
                Textarea::make('notes')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fromBranch.name')->label('From'),
                TextColumn::make('toBranch.name')->label('To'),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    StockTransfer::STATUS_REQUESTED => 'Requested',
                    StockTransfer::STATUS_APPROVED => 'Approved',
                    StockTransfer::STATUS_DISPATCHED => 'Dispatched',
                    StockTransfer::STATUS_RECEIVED => 'Received',
                    StockTransfer::STATUS_CANCELLED => 'Cancelled',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockTransfers::route('/'),
            'create' => Pages\CreateStockTransfer::route('/create'),
            'edit' => Pages\EditStockTransfer::route('/{record}/edit'),
        ];
    }
}
