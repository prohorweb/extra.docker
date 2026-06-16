<?php

namespace App\Filament\Resources\ClubResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EventRelationManager extends RelationManager
{
    protected static string $relationship = 'eventPosts';

    protected static ?string $title = 'Events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Textarea::make('intro')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('img')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/events'),
                Forms\Components\RichEditor::make('content')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Paid event'),
                Forms\Components\Toggle::make('is_open')
                    ->label('Registration open')
                    ->default(true),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->dehydrateStateUsing(fn (bool $state): int => $state ? 10 : 0)
                    ->formatStateUsing(fn (?int $state): bool => (int) $state === 10),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_open')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => (int) $record->status === 10),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['type'] = 'event';
                        $data['status'] = $data['status'] ?? 10;

                        return $data;
                    }),
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

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }
}
