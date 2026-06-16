<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaxonomyResource\Pages;
use App\Models\Taxonomy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class TaxonomyResource extends Resource
{
    protected static ?string $model = Taxonomy::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Categories & Tags';

    protected static ?string $modelLabel = 'taxonomy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'category' => 'Category (services, news)',
                        'specialization' => 'Specialization (trainers)',
                        'department' => 'Department (jobs)',
                        'tag' => 'Tag',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(
                        table: Taxonomy::class,
                        column: 'slug',
                        ignoreRecord: true,
                        modifyRuleUsing: function (Unique $rule, Get $get, $record): Unique {
                            return $rule->where('type', $record?->type ?? $get('type'));
                        },
                    )
                    ->nullable(),
                Forms\Components\TextInput::make('position')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->dehydrateStateUsing(fn (bool $state): int => $state ? 10 : 0)
                    ->formatStateUsing(fn (?int $state): bool => (int) $state === 10),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent category')
                    ->relationship(
                        'parent',
                        'title',
                        fn (Builder $query) => $query->where('type', 'category'),
                    )
                    ->nullable()
                    ->visible(fn (Get $get): bool => $get('type') === 'category'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => $state === 10 ? 'Active' : 'Hidden')
                    ->color(fn (int $state): string => $state === 10 ? 'success' : 'danger'),
            ])
            ->defaultSort('position')
            ->filters([])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', '!=', 'club');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaxonomies::route('/'),
            'create' => Pages\CreateTaxonomy::route('/create'),
            'edit' => Pages\EditTaxonomy::route('/{record}/edit'),
        ];
    }
}
