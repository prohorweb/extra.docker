<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SeoRelationManager extends RelationManager
{
    protected static string $relationship = 'seo';

    protected static ?string $title = 'SEO';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('meta_title')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\Textarea::make('meta_description')
                    ->rows(3)
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('og_image')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/seo')
                    ->nullable(),
                Forms\Components\Select::make('schema_type')
                    ->options([
                        'Service' => 'Service',
                        'Article' => 'Article (News)',
                        'Event' => 'Event',
                        'JobPosting' => 'JobPosting',
                        'Person' => 'Person (Trainer)',
                    ])
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('meta_title')
            ->columns([
                Tables\Columns\TextColumn::make('meta_title')
                    ->label('Title'),
                Tables\Columns\TextColumn::make('schema_type')
                    ->label('Schema'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn (): bool => ! $this->getOwnerRecord()->seo()->exists()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }
}
