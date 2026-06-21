<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateResource\Pages;
use App\Filament\Resources\AffiliateResource\RelationManagers;
use App\Models\Affiliate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Stack;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('user.email')
    //                 ->label('Email')
    //                 ->disabled()
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('f_name')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('l_name')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('software_name')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('software_link')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\Textarea::make('massage')
    //                 ->required()
    //                 ->columnSpanFull(),
    //             Forms\Components\TextInput::make('hash')
    //                 ->maxLength(255)
    //                 ->default(null),
    //             Forms\Components\TextInput::make('promo_link')
    //                 ->maxLength(255)
    //                 ->default(null),
    //             Forms\Components\TextInput::make('status')
    //                 ->required(),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user')
                    ->label('User')
                    ->getStateUsing(fn($record) => "#{$record->user_id} ({$record->user->email})")
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->getStateUsing(fn($record) => "{$record->f_name} {$record->l_name}")
                    ->searchable(),
                Tables\Columns\TextColumn::make('software_name')
                    ->label('Software Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('software_link')
                    ->label('Software Link')
                    ->url(fn($record) => $record->software_link)
                    ->openUrlInNewTab()
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= 50) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Awaiting' => 'Awaiting',
                        'Approve' => 'Approve',
                        'Unapprove' => 'Unapprove',
                        default => $state,
                    })
                    ->color(fn($state): string => match ($state) {
                        'Awaiting' => 'warning',
                        'Approve' => 'success',
                        'Unapprove' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Awaiting' => 'Awaiting',
                        'Approve' => 'Approve',
                        'Unapprove' => 'Unapprove',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn($record) => $record->status === 'Awaiting')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $hash = md5($record->user_id . time() . uniqid());
                        $promoLink = config('app.url') . '/?r=' . $hash;
                        $record->update([
                            'status' => 'Approve',
                            'hash' => $hash,
                            'promo_link' => $promoLink,
                        ]);
                    })
                    ->successNotificationTitle('Affiliate approved successfully.'),

                Tables\Actions\Action::make('unapprove')
                    ->label('Unapprove')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn($record) => $record->status === 'Awaiting')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'Unapprove',
                        ]);
                    })
                    ->successNotificationTitle('Affiliate unapproved successfully.'),

                Tables\Actions\ViewAction::make(),
            ])
            ->recordAction(Tables\Actions\ViewAction::class);
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
            'index' => Pages\ListAffiliates::route('/'),
            // 'create' => Pages\CreateAffiliate::route('/create'),
            // 'edit' => Pages\EditAffiliate::route('/{record}/edit'),
        ];
    }
}
