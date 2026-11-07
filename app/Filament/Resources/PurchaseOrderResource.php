<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Modules\Purchasing\Models\PurchaseOrder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PurchaseOrderResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = PurchaseOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string|UnitEnum|null $navigationGroup = 'Purchasing';

    protected static ?int $navigationSort = 72;

    protected static function moduleKey(): string
    {
        return 'purchasing';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Purchase order')->schema([
                TextInput::make('po_number')->disabled()->dehydrated(false),
                Select::make('supplier_id')->relationship('supplier', 'name')->required(),
                Select::make('branch_id')->relationship('branch', 'name')->required(),
                Select::make('status')->options([
                    PurchaseOrder::STATUS_DRAFT => 'Draft',
                    PurchaseOrder::STATUS_SENT => 'Sent',
                    PurchaseOrder::STATUS_PARTIAL => 'Partial',
                    PurchaseOrder::STATUS_RECEIVED => 'Received',
                    PurchaseOrder::STATUS_CANCELLED => 'Cancelled',
                ]),
                DateTimePicker::make('ordered_at'),
                DateTimePicker::make('expected_at'),
                TextInput::make('total_minor')->numeric()->prefix('Minor units'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('po_number')->searchable()->sortable(),
                TextColumn::make('supplier.name')->label('Supplier'),
                TextColumn::make('branch.name')->label('Branch'),
                TextColumn::make('status')->badge(),
                TextColumn::make('total_minor')->label('Total'),
                TextColumn::make('ordered_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    PurchaseOrder::STATUS_DRAFT => 'Draft',
                    PurchaseOrder::STATUS_SENT => 'Sent',
                    PurchaseOrder::STATUS_PARTIAL => 'Partial',
                    PurchaseOrder::STATUS_RECEIVED => 'Received',
                    PurchaseOrder::STATUS_CANCELLED => 'Cancelled',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
