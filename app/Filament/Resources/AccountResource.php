<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\ChecksModuleAccess;
use App\Filament\Resources\AccountResource\Pages;
use App\Modules\Finance\Models\ChartOfAccount;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AccountResource extends Resource
{
    use ChecksModuleAccess;

    protected static ?string $model = App\Modules\Finance\Models\ChartOfAccount::class;

    protected static ?string $navigationLabel = 'Accounts';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static function moduleKey(): string
    {
        return 'finance';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->required()->maxLength(32),
            TextInput::make('name')->required()->maxLength(255),
            Select::make('type')->options([
                ChartOfAccount::TYPE_ASSET => 'Asset',
                ChartOfAccount::TYPE_LIABILITY => 'Liability',
                ChartOfAccount::TYPE_EQUITY => 'Equity',
                ChartOfAccount::TYPE_INCOME => 'Income',
                ChartOfAccount::TYPE_EXPENSE => 'Expense',
            ])->required(),
            Select::make('parent_id')->relationship('parent', 'name')->searchable()->preload(),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->badge(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('code');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
