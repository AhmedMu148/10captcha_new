<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages;
use App\Models\Section;
use App\Support\SectionCodeEditorTools;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Reusable Sections';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->options(Section::TYPES)
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('published')
                            ->required(),

                        Forms\Components\TextInput::make('key')
                            ->label('Optional Handle / Key')
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->maxLength(255),
                    ])->columns(2),

                // Hero section fields
                Forms\Components\Section::make('Hero Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_HERO)
                    ->schema([
                        Forms\Components\TextInput::make('data.eyebrow')->label('Eyebrow'),
                        Forms\Components\TextInput::make('data.heading')->label('Heading')->required(),
                        Forms\Components\TextInput::make('data.subheading')->label('Subheading'),
                        Forms\Components\TextInput::make('data.button_label')->label('Button Label'),
                        Forms\Components\TextInput::make('data.button_url')->label('Button URL'),
                    ])->columns(2),

                // CTA section fields
                Forms\Components\Section::make('CTA Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_CTA)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading')->required(),
                        Forms\Components\Textarea::make('data.body')->label('Body'),
                        Forms\Components\TextInput::make('data.button_label')->label('Button Label'),
                        Forms\Components\TextInput::make('data.button_url')->label('Button URL'),
                    ])->columns(2),

                // Rich Text section fields
                Forms\Components\Section::make('Rich Text Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_RICH_TEXT)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\MarkdownEditor::make('data.body')->label('Markdown Body')->required(),
                    ]),

                // Stats section fields
                Forms\Components\Section::make('Stats Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_STATS)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\Repeater::make('data.items')
                            ->label('Stat Items')
                            ->schema([
                                Forms\Components\TextInput::make('value')->required()->label('Value (e.g. 99%, 10s)'),
                                Forms\Components\TextInput::make('label')->required()->label('Label (e.g. Success rate, Speed)'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                // Features section fields
                Forms\Components\Section::make('Features Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_FEATURES)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\TextInput::make('data.description')->label('Description'),
                        Forms\Components\Repeater::make('data.items')
                            ->label('Feature Cards')
                            ->schema([
                                Forms\Components\TextInput::make('title')->required()->label('Feature Title'),
                                Forms\Components\TextInput::make('icon')->label('Line Awesome Icon Class (e.g. las la-clock)'),
                                Forms\Components\Textarea::make('description')->required()->label('Description'),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                // Testimonial section fields
                Forms\Components\Section::make('Testimonial Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_TESTIMONIAL)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\TextInput::make('data.subheading')->label('Subheading'),
                        Forms\Components\Repeater::make('data.items')
                            ->label('Testimonials')
                            ->schema([
                                Forms\Components\Textarea::make('quote')->required()->label('Quote'),
                                Forms\Components\TextInput::make('author')->required()->label('Author Name'),
                                Forms\Components\TextInput::make('role')->label('Role/Title'),
                                Forms\Components\TextInput::make('company')->label('Company'),
                                Forms\Components\Select::make('rating')
                                    ->options([5 => '5 Stars', 4 => '4 Stars', 3 => '3 Stars', 2 => '2 Stars', 1 => '1 Star'])
                                    ->default(5)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                // Team section fields
                Forms\Components\Section::make('Team Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_TEAM)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\Repeater::make('data.items')
                            ->label('Team Members')
                            ->schema([
                                Forms\Components\TextInput::make('name')->required()->label('Name'),
                                Forms\Components\TextInput::make('role')->required()->label('Role'),
                                Forms\Components\TextInput::make('photo_url')->label('Photo URL'),
                                Forms\Components\Textarea::make('bio')->label('Bio'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                // FAQ section fields
                Forms\Components\Section::make('FAQ Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_FAQ)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\TextInput::make('data.description')->label('Description'),
                        Forms\Components\Repeater::make('data.items')
                            ->label('FAQ Items')
                            ->schema([
                                Forms\Components\TextInput::make('question')->required()->label('Question'),
                                Forms\Components\Textarea::make('answer')->required()->label('Answer'),
                            ])
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                // Gallery section fields
                Forms\Components\Section::make('Gallery Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_GALLERY)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\Repeater::make('data.items')
                            ->label('Gallery Items')
                            ->schema([
                                Forms\Components\TextInput::make('image_url')->required()->label('Image URL'),
                                Forms\Components\TextInput::make('caption')->label('Caption'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                // Pricing section fields
                Forms\Components\Section::make('Pricing Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_PRICING)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\Repeater::make('data.items')
                            ->label('Pricing Plans')
                            ->schema([
                                Forms\Components\TextInput::make('name')->required()->label('Plan Name'),
                                Forms\Components\TextInput::make('price')->required()->label('Price (e.g. $9, $19)'),
                                Forms\Components\TextInput::make('period')->label('Billing Period (e.g. month, year)'),
                                Forms\Components\TagsInput::make('features')->label('Plan Features'),
                                Forms\Components\TextInput::make('button_label')->label('Button Label'),
                                Forms\Components\TextInput::make('button_url')->label('Button URL'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                // Two Column section fields
                Forms\Components\Section::make('Two Column Settings')
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_TWO_COLUMN)
                    ->schema([
                        Forms\Components\TextInput::make('data.heading')->label('Heading'),
                        Forms\Components\MarkdownEditor::make('data.left')->label('Left Column (Markdown/HTML)')->required(),
                        Forms\Components\MarkdownEditor::make('data.right')->label('Right Column (Markdown/HTML)')->required(),
                    ])->columns(2),

                // Custom Section code editor + reference tooling row
                Forms\Components\Group::make()
                    ->visible(fn (Forms\Get $get) => $get('type') === Section::TYPE_CUSTOM)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\Section::make('Custom Code Editor')
                                        ->schema([
                                            Forms\Components\Textarea::make('html_content')
                                                ->label('HTML / Blade Code')
                                                ->helperText('You can use Blade tags, standard CSS/JS, and [[cms.*]] shortcodes')
                                                ->required()
                                                ->rows(15)
                                                ->extraAttributes(['style' => 'font-family: monospace;']),

                                            Forms\Components\TextInput::make('wrapper_class')
                                                ->label('Outer Shell Wrapper CSS Class(es)'),

                                            Forms\Components\TextInput::make('anchor_id')
                                                ->label('Anchor Section ID (id="...")')
                                                ->alphaDash(),
                                        ]),
                                ])->columnSpan(2),

                                Forms\Components\Group::make([
                                    Forms\Components\Section::make('Editor Helper Tools')
                                        ->schema([
                                            Forms\Components\Placeholder::make('reference_guidelines')
                                                ->label('Design Reference Items')
                                                ->content(collect(SectionCodeEditorTools::designReferenceItems())->map(fn ($g) => "• {$g}")->implode("\n")),

                                            Forms\Components\Placeholder::make('theme_classes')
                                                ->label('Host Approved Classes')
                                                ->content(collect(SectionCodeEditorTools::classTokenOptions())->map(fn ($v, $k) => "{$k} ({$v})")->implode("\n")),

                                            Forms\Components\Placeholder::make('theme_tokens')
                                                ->label('Design Tokens')
                                                ->content(collect(SectionCodeEditorTools::designTokenOptions())->map(fn ($v, $k) => "{$k} ({$v})")->implode("\n")),

                                            Forms\Components\Placeholder::make('scaffold_recipes')
                                                ->label('Scaffold Recipes (Copy-paste)')
                                                ->content(collect(SectionCodeEditorTools::recipes())->map(fn ($v, $k) => "**{$v['name']}**:\n```html\n{$v['html']}\n```")->implode("\n\n")),
                                        ]),
                                ])->columnSpan(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Section::TYPES[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->placeholder('None'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'published',
                        'warning' => 'draft',
                    ])
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
                Tables\Filters\SelectFilter::make('type')
                    ->options(Section::TYPES),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
