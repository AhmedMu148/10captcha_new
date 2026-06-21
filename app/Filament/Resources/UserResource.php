<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User')
                    ->schema([
                        TextInput::make('name')
                            ->nullable()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => $state ?: null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context) => $context === 'create')
                            ->minLength(8),
                    ])
                    ->columns(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        1, '1', 'A', 'Active' => 'Active',
                        2, '2', 'V', 'Verified' => 'Verified',
                        3, '3', 'S', 'Suspended' => 'Suspended',
                        4, '4', 'F', 'Frozen' => 'Frozen',
                        default => 'Unknown',
                    })
                    ->color(fn($state): string => match ($state) {
                        1, '1', 'A', 'Active' => 'info',
                        2, '2', 'V', 'Verified' => 'success',
                        3, '3', 'S', 'Suspended' => 'danger',
                        4, '4', 'F', 'Frozen' => 'danger',
                        default => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('bonus')
                    ->label('T.Bonus')
                    ->formatStateUsing(fn($state) => '$' . number_format($state / 100000, 2))
                    ->sortable(),
                TextColumn::make('balance_5d')
                    ->label('Balance')
                    ->formatStateUsing(fn($state) => '$' . number_format($state / 100000, 2))
                    ->sortable(),

                TextColumn::make('created_at')->dateTime()->label('Registered')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email verified')
                    ->placeholder('All users')
                    ->trueLabel('Verified')->falseLabel('Unverified')
                    ->queries(
                        true: fn(Builder $q) => $q->whereNotNull('email_verified_at'),
                        false: fn(Builder $q) => $q->whereNull('email_verified_at'),
                        blank: fn(Builder $q) => $q
                    ),
                Tables\Filters\Filter::make('created_between')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $q, array $data) {
                        return $q
                            ->when($data['from'] ?? null, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
