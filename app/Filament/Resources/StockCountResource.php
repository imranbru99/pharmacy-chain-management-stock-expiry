<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\StockCountResource\Pages;
use App\Modules\StockCounts\Models\StockCount;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class StockCountResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = StockCount::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static string|UnitEnum|null $navigationGroup = 'Catalog & Inventory';

    protected static ?int $navigationSort = 45;

    protected static function moduleKey(): string
    {
        return 'stock_counts';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Stock count')->schema([
                Select::make('branch_id')->relationship('branch', 'name')->required(),
                Select::make('type')->options([
                    StockCount::TYPE_CYCLE => 'Cycle',
                    StockCount::TYPE_FULL => 'Full',
                    StockCount::TYPE_BLIND => 'Blind',
                ]),
                Select::make('status')->options([
                    StockCount::STATUS_DRAFT => 'Draft',
                    StockCount::STATUS_IN_PROGRESS => 'In progress',
                    StockCount::STATUS_SUBMITTED => 'Submitted',
                    StockCount::STATUS_APPROVED => 'Approved',
                    StockCount::STATUS_CANCELLED => 'Cancelled',
                ]),
                Textarea::make('notes')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('branch.name')->label('Branch'),
                TextColumn::make('type')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    StockCount::STATUS_DRAFT => 'Draft',
                    StockCount::STATUS_IN_PROGRESS => 'In progress',
                    StockCount::STATUS_SUBMITTED => 'Submitted',
                    StockCount::STATUS_APPROVED => 'Approved',
                    StockCount::STATUS_CANCELLED => 'Cancelled',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockCounts::route('/'),
            'create' => Pages\CreateStockCount::route('/create'),
            'edit' => Pages\EditStockCount::route('/{record}/edit'),
        ];
    }
}
