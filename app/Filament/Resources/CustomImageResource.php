<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomImageResource\Pages;
use App\Models\CustomImage;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomImageResource extends Resource
{
    protected static ?string $model = CustomImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Page Management';

    protected static ?string $navigationLabel = 'Custom Images';

    protected static ?string $modelLabel = 'Custom Image';

    protected static ?string $pluralModelLabel = 'Custom Images';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= 50) {
                            return null;
                        }

                        return $state;
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Inactive',
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
            ])
            ->defaultSort('id', 'asc');
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
            'index' => Pages\ListCustomImages::route('/'),
            // 'create' => Pages\CreateCustomImage::route('/create'),
            // 'edit' => Pages\EditCustomImage::route('/{record}/edit'),
        ];
    }
}
