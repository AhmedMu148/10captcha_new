<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteMapResource\Pages;
use App\Models\SiteMap;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\EditAction;

class SiteMapResource extends Resource
{
    protected static ?string $model = SiteMap::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'SEO';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->url()
                    ->maxLength(2048),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        0 => 'inactive',
                        1 => 'active',
                    ])
                    ->required()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('url')->label('URL')->wrap()->limit(60),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state === 1 ? 'active' : 'inactive')
                    ->colors([
                        'success' => 1,
                        'danger' => 0,
                    ])
                    ->sortable(),
                TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('id');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteMaps::route('/'),
            'create' => Pages\CreateSiteMap::route('/create'),
            'edit' => Pages\EditSiteMap::route('/{record}/edit'),
        ];
    }
}
