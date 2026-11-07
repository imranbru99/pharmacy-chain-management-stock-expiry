<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\ProductRecallResource\Pages;
use App\Modules\Expiry\Models\ProductRecall;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ProductRecallResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = ProductRecall::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|UnitEnum|null $navigationGroup = 'Catalog & Inventory';

    protected static ?string $navigationLabel = 'Recalls';

    protected static ?int $navigationSort = 35;

    protected static function moduleKey(): string
    {
        return 'expiry';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Recall')->schema([
                Select::make('product_id')->relationship('product', 'name')->required()->searchable(),
                TextInput::make('batch_number')->helperText('Leave empty to recall all batches of this product'),
                Textarea::make('reason')->required()->columnSpanFull(),
                Select::make('status')->options([
                    ProductRecall::STATUS_ACTIVE => 'Active',
                    ProductRecall::STATUS_CLOSED => 'Closed',
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->searchable(),
                TextColumn::make('batch_number')->placeholder('All batches'),
                TextColumn::make('status')->badge(),
                TextColumn::make('started_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    ProductRecall::STATUS_ACTIVE => 'Active',
                    ProductRecall::STATUS_CLOSED => 'Closed',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductRecalls::route('/'),
            'create' => Pages\CreateProductRecall::route('/create'),
            'edit' => Pages\EditProductRecall::route('/{record}/edit'),
        ];
    }
}
