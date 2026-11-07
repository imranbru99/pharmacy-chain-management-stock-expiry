<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PrescriptionResource\Pages;
use App\Modules\Prescriptions\Models\Prescription;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PrescriptionResource extends Resource
{
    protected static ?string $model = Prescription::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Clinical';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('customer_id')
                ->relationship('customer', 'name')
                ->searchable()
                ->required(),
            Select::make('branch_id')
                ->relationship('branch', 'name')
                ->required(),
            TextInput::make('doctor_name')->required(),
            Select::make('status')->options([
                'pending' => 'Pending',
                'verified' => 'Verified',
                'partial' => 'Partial',
                'filled' => 'Filled',
                'cancelled' => 'Cancelled',
            ])->required(),
            DateTimePicker::make('prescribed_at'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('customer.name')->searchable(),
            TextColumn::make('doctor_name')->searchable(),
            TextColumn::make('status')->badge(),
            TextColumn::make('prescribed_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrescriptions::route('/'),
            'create' => Pages\CreatePrescription::route('/create'),
            'edit' => Pages\EditPrescription::route('/{record}/edit'),
        ];
    }
}
