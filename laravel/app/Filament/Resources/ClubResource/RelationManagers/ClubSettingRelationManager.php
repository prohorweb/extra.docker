<?php

namespace App\Filament\Resources\ClubResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ClubSettingRelationManager extends RelationManager
{
    protected static string $relationship = 'settingPosts';

    protected static ?string $title = 'Contacts & Hours';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tel')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('coordinates')
                    ->hint('lat, lng — e.g. 59.8499, 30.2953')
                    ->maxLength(255),
                Forms\Components\TextInput::make('working_hours')
                    ->label('Weekdays hours')
                    ->maxLength(255),
                Forms\Components\TextInput::make('working_hours_weekend')
                    ->label('Weekend hours')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('img')
                    ->label('Club photo')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/clubs'),
                Forms\Components\RichEditor::make('content')
                    ->label('About club text')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('tel'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('address'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn (): bool => $this->getOwnerRecord()->settingPosts()->count() === 0)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['type'] = 'setting';
                        $data['title'] = $this->getOwnerRecord()->title;
                        $data['status'] = 10;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }
}
