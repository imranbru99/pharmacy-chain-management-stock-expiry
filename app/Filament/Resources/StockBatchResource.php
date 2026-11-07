<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\StockBatchResource\Pages;
use App\Modules\Stock\Models\StockBatch;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class StockBatchResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = StockBatch::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static string|UnitEnum|null $navigationGroup = 'Catalog & Inventory';

    protected static ?string $navigationLabel = 'Stock Batches';

    protected static ?int $navigationSort = 20;

    protected static function moduleKey(): string
    {
        return 'stock';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Batch')->schema([
                Select::make('branch_id')->relationship('branch', 'name')->required(),
                Select::make('product_id')->relationship('product', 'name')->required()->searchable(),
                TextInput::make('batch_number')->required(),
                DatePicker::make('manufactured_at'),
                DatePicker::make('expires_at')->required(),
                TextInput::make('quantity')->numeric()->integer()->required(),
                Select::make('status')->options([
                    StockBatch::STATUS_AVAILABLE => 'Available',
                    StockBatch::STATUS_QUARANTINE => 'Quarantine',
                    StockBatch::STATUS_EXPIRED => 'Expired',
                    StockBatch::STATUS_RECALLED => 'Recalled',
                ])->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')->sortable(),
                TextColumn::make('product.name')->searchable()->sortable(),
                TextColumn::make('batch_number')->searchable(),
                TextColumn::make('expires_at')->date()->sortable(),
                TextColumn::make('quantity')->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    StockBatch::STATUS_AVAILABLE => 'Available',
                    StockBatch::STATUS_QUARANTINE => 'Quarantine',
                    StockBatch::STATUS_EXPIRED => 'Expired',
                    StockBatch::STATUS_RECALLED => 'Recalled',
                ]),
                Filter::make('expiring_soon')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('expires_at', [now(), now()->addDays(30)]))
                    ->label('Expiring in 30 days'),
                Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now()->toDateString()))
                    ->label('Expired'),
            ])
            ->defaultSort('expires_at');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockBatches::route('/'),
            'create' => Pages\CreateStockBatch::route('/create'),
            'edit' => Pages\EditStockBatch::route('/{record}/edit'),
        ];
    }
}
