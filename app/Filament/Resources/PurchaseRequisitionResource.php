<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\PurchaseRequisitionResource\Pages;
use App\Modules\Purchasing\Models\PurchaseRequisition;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PurchaseRequisitionResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = PurchaseRequisition::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|UnitEnum|null $navigationGroup = 'Purchasing';

    protected static ?int $navigationSort = 71;

    protected static ?string $navigationLabel = 'Requisitions';

    protected static function moduleKey(): string
    {
        return 'purchasing';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Requisition')->schema([
                Select::make('branch_id')->relationship('branch', 'name')->required(),
                Select::make('status')->options([
                    PurchaseRequisition::STATUS_DRAFT => 'Draft',
                    PurchaseRequisition::STATUS_SUBMITTED => 'Submitted',
                    PurchaseRequisition::STATUS_APPROVED => 'Approved',
                    PurchaseRequisition::STATUS_REJECTED => 'Rejected',
                    PurchaseRequisition::STATUS_CONVERTED => 'Converted',
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
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    PurchaseRequisition::STATUS_DRAFT => 'Draft',
                    PurchaseRequisition::STATUS_SUBMITTED => 'Submitted',
                    PurchaseRequisition::STATUS_APPROVED => 'Approved',
                    PurchaseRequisition::STATUS_REJECTED => 'Rejected',
                    PurchaseRequisition::STATUS_CONVERTED => 'Converted',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseRequisitions::route('/'),
            'create' => Pages\CreatePurchaseRequisition::route('/create'),
            'edit' => Pages\EditPurchaseRequisition::route('/{record}/edit'),
        ];
    }
}
