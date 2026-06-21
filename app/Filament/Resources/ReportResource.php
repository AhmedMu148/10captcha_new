<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_5d')
                    ->formatStateUsing(fn($state) => '$' . $state / 100000)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): string => match ((int) $state) {
                        1 => 'warning',
                        2 => 'primary',
                        3 => 'success',
                        4 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state): string => match ((int) $state) {
                        1 => 'Pending',
                        2 => 'Processing',
                        3 => 'Completed',
                        4 => 'Error',
                        default => 'Unknown',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListReports::route('/'),
            // 'create' => Pages\CreateReport::route('/create'),
            // 'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
