<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\ProductResource\Pages;
use App\Modules\Catalog\Models\Product;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ProductResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static string|UnitEnum|null $navigationGroup = 'Catalog & Inventory';

    protected static ?int $navigationSort = 10;

    protected static function moduleKey(): string
    {
        return 'catalog';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Product')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')->required()->maxLength(255),
                        TextInput::make('name_bn')->maxLength(255),
                        TextInput::make('generic_name')->maxLength(255),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('manufacturer_id')
                            ->relationship('manufacturer', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('schedule')
                            ->options([
                                Product::SCHEDULE_OTC => 'OTC',
                                Product::SCHEDULE_RX => 'Prescription (Rx)',
                                Product::SCHEDULE_CONTROLLED => 'Controlled',
                            ])
                            ->required(),
                        TextInput::make('sku')->maxLength(64),
                        TextInput::make('barcode')->maxLength(64),
                        TextInput::make('selling_price_minor')->numeric()->integer(),
                        TextInput::make('mrp_minor')->numeric()->integer(),
                        TextInput::make('reorder_level')->numeric()->integer(),
                        Toggle::make('requires_prescription'),
                        Toggle::make('is_active')->default(true),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('sku')->searchable(),
                TextColumn::make('barcode')->searchable(),
                TextColumn::make('schedule')->badge(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('selling_price_minor')
                    ->label('Price')
                    ->formatStateUsing(fn (int $state): string => money_format($state)),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('schedule')
                    ->options([
                        Product::SCHEDULE_OTC => 'OTC',
                        Product::SCHEDULE_RX => 'Rx',
                        Product::SCHEDULE_CONTROLLED => 'Controlled',
                    ]),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(ProductImporter::class),
                ExportAction::make()
                    ->exporter(ProductExporter::class),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
