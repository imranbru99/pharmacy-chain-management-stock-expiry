<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PosShiftResource\Pages;
use App\Modules\Pos\Models\PosShift;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PosShiftResource extends Resource
{
    protected static ?string $model = PosShift::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'POS';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')->relationship('branch', 'name')->required(),
            Select::make('user_id')->relationship('user', 'name')->required(),
            DateTimePicker::make('opened_at')->required(),
            DateTimePicker::make('closed_at'),
            TextInput::make('opening_cash_minor')->numeric()->default(0),
            TextInput::make('status')->default('open'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('branch.name'),
            TextColumn::make('user.name'),
            TextColumn::make('status')->badge(),
            TextColumn::make('opened_at')->dateTime(),
            TextColumn::make('closed_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosShifts::route('/'),
            'create' => Pages\CreatePosShift::route('/create'),
            'edit' => Pages\EditPosShift::route('/{record}/edit'),
        ];
    }
}
