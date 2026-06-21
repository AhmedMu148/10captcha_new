<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateCommissionResource\Pages;
use App\Filament\Resources\AffiliateCommissionResource\RelationManagers;
use App\Models\AffiliateCommission;
use App\Models\AffiliateBalance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliateCommissionResource extends Resource
{
    protected static ?string $model = AffiliateCommission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('aff_id')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('aff_rel_id')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('comm_amount_5d')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('comm_percent')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('status')
    //                 ->required(),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('affiliate.user.email')
                    ->label('Super Aff.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('affiliateRelation.user.email')
                    ->label('Payer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comm_amount_5d')
                    ->label('Commission')
                    ->money('USD', divideBy: 100000)
                    ->sortable(),
                Tables\Columns\TextColumn::make('comm_percent')
                    ->label('Percent')
                    ->formatStateUsing(fn($state) => $state . '%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Awaiting' => 'Awaiting',
                        'Approved', 'Approve' => 'Approved',
                        'Rejected', 'Unapprove' => 'Rejected',
                        default => $state,
                    })
                    ->color(fn($state): string => match ($state) {
                        'Awaiting' => 'warning',
                        'Approved', 'Approve' => 'success',
                        'Rejected', 'Unapprove' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Awaiting' => 'Awaiting',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
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
                        $record->update([
                            'status' => 'Approve',
                        ]);

                        $affiliate = $record->affiliate;
                        if ($affiliate) {
                            $userId = $affiliate->user_id;

                            $balance = AffiliateBalance::where('user_id', $userId)->first();

                            if (! $balance) {
                                $balance = new AffiliateBalance();
                                $balance->aff_id = $affiliate->id;
                                $balance->user_id = $userId;
                                $balance->balance_5d = 0;
                            }

                            // Increment the balance
                            $balance->balance_5d += $record->comm_amount_5d;
                            $balance->save();
                        }
                    })
                    ->successNotificationTitle('Commission approved successfully.'),

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
                    ->successNotificationTitle('Commission unapproved successfully.'),
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
            'index' => Pages\ListAffiliateCommissions::route('/'),
            // 'create' => Pages\CreateAffiliateCommission::route('/create'),
            // 'edit' => Pages\EditAffiliateCommission::route('/{record}/edit'),
        ];
    }
}
