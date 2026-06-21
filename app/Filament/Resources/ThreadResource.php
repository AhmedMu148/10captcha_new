<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThreadResource\Pages;
use App\Filament\Resources\ThreadResource\RelationManagers;
use App\Models\Thread;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThreadResource extends Resource
{
    protected static ?string $model = Thread::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bigger_than')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('threads')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('status')
                    ->dehydrateStateUsing(fn($state) => $state ? 'active' : 'inactive')
                    ->afterStateHydrated(function (Forms\Components\Toggle $component, $state) {
                        $component->state($state === 'active');
                    })
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bigger_than')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('threads')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListThreads::route('/'),
            'create' => Pages\CreateThread::route('/create'),
            'edit' => Pages\EditThread::route('/{record}/edit'),
        ];
    }
}
