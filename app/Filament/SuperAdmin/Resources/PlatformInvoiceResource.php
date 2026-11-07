<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\PlatformInvoiceResource\Pages;
use App\Modules\Billing\Models\PlatformInvoice;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PlatformInvoiceResource extends Resource
{
    protected static ?string $model = PlatformInvoice::class;

    protected static ?string $navigationLabel = 'Platform Invoices';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Billing';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')->relationship('tenant', 'name')->required()->searchable(),
            TextInput::make('number')->required()->unique(ignoreRecord: true),
            TextInput::make('amount_minor')->numeric()->integer()->required(),
            TextInput::make('status')->required(),
            DateTimePicker::make('due_at'),
            DateTimePicker::make('paid_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable(),
                TextColumn::make('tenant.name')->searchable(),
                TextColumn::make('amount_minor')->formatStateUsing(fn (int $state): string => money_format($state)),
                TextColumn::make('status')->badge(),
                TextColumn::make('due_at')->dateTime(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformInvoices::route('/'),
            'create' => Pages\CreatePlatformInvoice::route('/create'),
            'edit' => Pages\EditPlatformInvoice::route('/{record}/edit'),
        ];
    }
}
