<?php

namespace App\Filament\Resources\ClubResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BannerRelationManager extends RelationManager
{
    protected static string $relationship = 'bannerPosts';

    protected static ?string $title = 'Banners / Slider';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtitle')
                    ->maxLength(255),
                Forms\Components\Textarea::make('intro')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->label('CTA link')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('img')
                    ->label('Desktop image')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/banners'),
                Forms\Components\FileUpload::make('banner_img_mobile')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/banners'),
                Forms\Components\TextInput::make('banner_video')
                    ->label('Video URL')
                    ->hint('YouTube or mp4 URL')
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->dehydrateStateUsing(fn (bool $state): int => $state ? 10 : 0)
                    ->formatStateUsing(fn (?int $state): bool => (int) $state === 10),
                Forms\Components\TextInput::make('banner_position')
                    ->label('Slider position')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('banner_position')
            ->reorderable('banner_position')
            ->columns([
                Tables\Columns\TextColumn::make('banner_position')
                    ->label('Position')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Image'),
                Tables\Columns\IconColumn::make('banner_video')
                    ->label('Video')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => filled($record->banner_video)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['type'] = 'share';
                        $data['is_banner'] = true;
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
