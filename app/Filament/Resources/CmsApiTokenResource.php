<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsApiTokenResource\Pages;
use App\Models\CmsApiToken;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CmsApiTokenResource extends Resource
{
    protected static ?string $model = CmsApiToken::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'API Tokens';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Token Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Token Name / Description')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expires At')
                            ->nullable(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),

                        Forms\Components\CheckboxList::make('abilities')
                            ->label('Abilities / Scopes')
                            ->options([
                                'cms.pages.read' => 'Read Pages',
                                'cms.pages.write' => 'Write Pages',
                                'cms.sections.read' => 'Read Sections',
                                'cms.sections.write' => 'Write Sections',
                                'cms.all' => 'Super Admin (All Abilities)',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('token_prefix')
                    ->label('Prefix')
                    ->badge()
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('abilities')
                    ->badge()
                    ->separator(','),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->placeholder('Never')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_used_at')
                    ->dateTime()
                    ->placeholder('Never')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->is_active && is_null($record->revoked_at))
                    ->action(function ($record) {
                        $record->update([
                            'is_active' => false,
                            'revoked_at' => now(),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Token Revoked')
                            ->success()
                            ->send();
                    }),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCmsApiTokens::route('/'),
            'create' => Pages\CreateCmsApiToken::route('/create'),
            'edit' => Pages\EditCmsApiToken::route('/{record}/edit'),
        ];
    }
}
