<?php

namespace App\Filament\Resources\PageResource\RelationManagers;

use App\Filament\Resources\SectionResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PageSectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('pivot.is_visible')
                    ->label('Visible on Page')
                    ->default(true),
                Forms\Components\KeyValue::make('pivot.overrides')
                    ->label('Field Overrides')
                    ->helperText('Key-value JSON overrides that win over the reusable Section data.')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn ($record) => $record->name ?? 'Section')
            ->columns([
                Tables\Columns\TextColumn::make('pivot.order')
                    ->label('Order #')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Section Name')
                    ->description(fn ($record) => $record->key ? "Key: {$record->key}" : null)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'published',
                        'warning' => 'draft',
                    ])
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('pivot.is_visible')
                    ->label('Visible'),
            ])
            ->reorderable('order')
            ->defaultSort('page_sections.order', 'asc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Toggle::make('is_visible')->default(true),
                        Forms\Components\KeyValue::make('overrides')
                            ->label('Field Overrides')
                            ->helperText('Key-value overrides for fields in this section on this specific page')
                    ])
                    ->mutateFormDataUsing(function (array $data, RelationManager $livewire) {
                        $data['order'] = (DB::table('page_sections')
                            ->where('page_id', $livewire->getOwnerRecord()->id)
                            ->max('order') ?? 0) + 1;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('editSection')
                    ->label('Edit Section')
                    ->icon('heroicon-o-pencil')
                    ->url(fn ($record) => SectionResource::getUrl('edit', ['record' => $record->id])),
                
                Tables\Actions\EditAction::make()
                    ->label('Edit Pivot'),

                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
