<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClubResource\Pages;
use App\Filament\Resources\ClubResource\RelationManagers;
use App\Models\Taxonomy;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClubResource extends Resource
{
    protected static ?string $model = Taxonomy::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Clubs';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Club')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Settings')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'club');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ClubSettingRelationManager::class,
            RelationManagers\BannerRelationManager::class,
            RelationManagers\ShareRelationManager::class,
            RelationManagers\EventRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'edit' => Pages\EditClub::route('/{record}/edit'),
        ];
    }

    public static function registerNavigationItems(): void
    {
        if (! static::canAccess()) {
            return;
        }

        Filament::getCurrentPanel()
            ->navigationItems(static::getNavigationItems());
    }

    public static function getNavigationItems(): array
    {
        return Taxonomy::clubs()->active()->ordered()->get()->map(function ($club) {
            return NavigationItem::make($club->title)
                ->group('Clubs')
                ->icon('heroicon-o-building-office')
                ->url(static::getUrl('edit', ['record' => $club->id]))
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName().'.edit')
                    && request()->route('record') == $club->id);
        })->toArray();
    }
}
