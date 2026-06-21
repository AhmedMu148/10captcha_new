<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('ocr_cap_id')
                    ->label('ocr_cap_id')
                    ->options(self::getCaptchaName())
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('img')
                    ->disk('public')
                    ->directory('plans')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('success')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('speed')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sort')
                    ->required()
                    ->default(0)
                    ->numeric(),
                Forms\Components\Toggle::make('status')
                    ->dehydrateStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                    ->afterStateHydrated(function (Forms\Components\Toggle $component, $state) {
                        $component->state($state === 'Active');
                    })
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ocr_cap_id')
                    ->label('ocr_cap_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn($state) => '$' . $state)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('img')
                    ->getStateUsing(fn($record) => (str_starts_with($record->img, 'http://') || str_starts_with($record->img, 'https://')) ? $record->img : asset('storage/' . $record->img)),
                Tables\Columns\TextColumn::make('success')
                    ->formatStateUsing(fn($state) => $state . '%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('speed')
                    ->formatStateUsing(fn($state) => $state . 's')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->getStateUsing(fn($record) => $record->status === 'Active')
                    ->boolean(),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }

    public static function getCaptchaName(): ?array
    {
        $captchaNames = [
            '1' => 'Image',
            '2' => 'Text',
            '3' => 'reCaptcha V2',
            '4' => 'reCaptcha Invisible',
            '5' => 'reCaptcha V3',
            '6' => 'reCaptcha Enterprise',
            '7' => 'FunCaptcha',
            '8' => 'hCaptcha',
        ];
        return $captchaNames;
    }
}
