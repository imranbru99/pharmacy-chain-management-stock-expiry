<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\GoodsReceiptResource\Pages;
use App\Modules\Purchasing\Models\GoodsReceipt;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class GoodsReceiptResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = GoodsReceipt::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static string|UnitEnum|null $navigationGroup = 'Purchasing';

    protected static ?int $navigationSort = 73;

    protected static ?string $navigationLabel = 'Goods receipts';

    protected static function moduleKey(): string
    {
        return 'purchasing';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('GRN')->schema([
                TextInput::make('grn_number')->disabled()->dehydrated(false),
                Select::make('purchase_order_id')->relationship('purchaseOrder', 'po_number')->required(),
                Select::make('branch_id')->relationship('branch', 'name')->required(),
                DateTimePicker::make('received_at'),
                Textarea::make('notes')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grn_number')->searchable()->sortable(),
                TextColumn::make('purchaseOrder.po_number')->label('PO'),
                TextColumn::make('branch.name')->label('Branch'),
                TextColumn::make('received_at')->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoodsReceipts::route('/'),
            'create' => Pages\CreateGoodsReceipt::route('/create'),
            'edit' => Pages\EditGoodsReceipt::route('/{record}/edit'),
        ];
    }
}
