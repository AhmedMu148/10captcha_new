<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateWithdrawResource\Pages;
use App\Filament\Resources\AffiliateWithdrawResource\RelationManagers;
use App\Models\AffiliateWithdraw;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliateWithdrawResource extends Resource
{
    protected static ?string $model = AffiliateWithdraw::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('txn_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount_5d')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('method')
                    ->required(),
                Forms\Components\TextInput::make('payment_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('txn_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_5d')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method'),
                Tables\Columns\TextColumn::make('payment_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliateWithdraws::route('/'),
            'create' => Pages\CreateAffiliateWithdraw::route('/create'),
            'edit' => Pages\EditAffiliateWithdraw::route('/{record}/edit'),
        ];
    }
}
