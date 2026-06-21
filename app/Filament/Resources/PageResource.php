<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Pages';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            )
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaDash()
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        if (in_array(strtolower($value), Page::RESERVED_SLUGS)) {
                                            $fail('The slug is reserved and cannot be used.');
                                        }
                                    };
                                }
                            ]),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Select::make('visibility')
                            ->options([
                                'public' => 'Public',
                                'private' => 'Private',
                            ])
                            ->default('public')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('SEO Metadata')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('seo_keywords')
                            ->label('SEO Keywords')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'published',
                        'warning' => 'draft',
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('visibility')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->visibility === 'public')
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->sortable(),

                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->colors([
                        'gray' => 'admin',
                        'info' => 'api',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
                Tables\Filters\SelectFilter::make('visibility')
                    ->options([
                        'public' => 'Public',
                        'private' => 'Private',
                    ]),
            ])
            ->actions([
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
            RelationManagers\PageSectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPage::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
