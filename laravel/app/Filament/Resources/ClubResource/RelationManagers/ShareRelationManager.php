<?php

namespace App\Filament\Resources\ClubResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ShareRelationManager extends RelationManager
{
    protected static string $relationship = 'sharePosts';

    protected static ?string $title = 'Promotions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('intro')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('img')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/share'),
                Forms\Components\TextInput::make('price')
                    ->numeric(),
                Forms\Components\TextInput::make('slug')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->dehydrateStateUsing(fn (bool $state): int => $state ? 10 : 0)
                    ->formatStateUsing(fn (?int $state): bool => (int) $state === 10),
                Forms\Components\Toggle::make('is_banner')
                    ->label('Show as banner')
                    ->live(),
                Forms\Components\TextInput::make('banner_position')
                    ->numeric()
                    ->visible(fn (Get $get): bool => (bool) $get('is_banner')),
                Forms\Components\FileUpload::make('banner_img_mobile')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/banners')
                    ->visible(fn (Get $get): bool => (bool) $get('is_banner')),
                Forms\Components\TextInput::make('banner_video')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => (bool) $get('is_banner')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('position')
            ->reorderable('position')
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('RUB', divideBy: 1),
                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => (int) $record->status === 10),
                Tables\Columns\IconColumn::make('is_banner')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['type'] = 'share';
                        $data['is_banner'] = $data['is_banner'] ?? false;
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
