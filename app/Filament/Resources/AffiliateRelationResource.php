<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateRelationResource\Pages;
use App\Filament\Resources\AffiliateRelationResource\RelationManagers;
use App\Models\AffiliateRelation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliateRelationResource extends Resource
{
    protected static ?string $model = AffiliateRelation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('aff_id')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('user_id')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('comm')
    //                 ->required()
    //                 ->numeric()
    //                 ->default(0),
    //             Forms\Components\TextInput::make('status')
    //                 ->required(),
    //             Forms\Components\DatePicker::make('end_date'),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Player')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('affiliate.user.email')
                    ->label('Super Affiliate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comm')
                    ->label('% commission')
                    ->formatStateUsing(fn($state) => $state . '%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    })
                    ->color(fn($state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliateRelations::route('/'),
            // 'create' => Pages\CreateAffiliateRelation::route('/create'),
            // 'edit' => Pages\EditAffiliateRelation::route('/{record}/edit'),
        ];
    }
}
