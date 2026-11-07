<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Modules\Customers\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Sales';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('name_bn')->label('Name (Bangla)')->maxLength(255),
            TextInput::make('phone')->tel()->maxLength(50),
            TextInput::make('email')->email()->maxLength(255),
            DatePicker::make('date_of_birth'),
            Select::make('gender')->options([
                'male' => 'Male',
                'female' => 'Female',
                'other' => 'Other',
            ]),
            Textarea::make('address')->columnSpanFull(),
            TextInput::make('loyalty_points')->numeric()->default(0),
            TextInput::make('loyalty_tier')->default('standard'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('name_bn')->label('বাংলা'),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('loyalty_points')->sortable(),
                TextColumn::make('loyalty_tier')->badge(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
