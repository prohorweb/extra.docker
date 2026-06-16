<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return static::getTypeLabel(static::getCurrentType());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'news' => 'News',
                        'service' => 'Service',
                        'share' => 'Promotion',
                        'trainer' => 'Trainer',
                        'event' => 'Event',
                        'job' => 'Job',
                        'card' => 'Card',
                    ])
                    ->required()
                    ->live()
                    ->disabled(fn (?Model $record): bool => $record !== null),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(
                        table: Post::class,
                        column: 'slug',
                        ignoreRecord: true,
                        modifyRuleUsing: function (Unique $rule, Get $get, ?Model $record): Unique {
                            return $rule->where('type', $record?->type ?? $get('type'));
                        },
                    )
                    ->nullable(),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->dehydrateStateUsing(fn (bool $state): int => $state ? 10 : 0)
                    ->formatStateUsing(fn (?int $state): bool => (int) $state === 10),
                Forms\Components\TextInput::make('position')
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('intro')
                    ->rows(3)
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['news', 'share', 'event'], true))
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('date')
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['news', 'event'], true)),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Paid event')
                    ->visible(fn (Get $get): bool => $get('type') === 'event'),
                Forms\Components\Toggle::make('is_open')
                    ->label('Registration open')
                    ->default(true)
                    ->visible(fn (Get $get): bool => $get('type') === 'event'),
                Forms\Components\TextInput::make('subtitle')
                    ->label('Position / Job Title')
                    ->maxLength(255)
                    ->visible(fn (Get $get): bool => $get('type') === 'trainer'),
                Forms\Components\Select::make('terms')
                    ->label('Specializations')
                    ->multiple()
                    ->relationship(
                        'terms',
                        'title',
                        fn (Builder $query) => $query->where('type', 'specialization')->where('status', 10),
                    )
                    ->visible(fn (Get $get): bool => $get('type') === 'trainer'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['share', 'card'], true)),
                Forms\Components\ColorPicker::make('color')
                    ->visible(fn (Get $get): bool => $get('type') === 'card'),
                Forms\Components\TextInput::make('button_code')
                    ->label('Button embed code')
                    ->visible(fn (Get $get): bool => $get('type') === 'card'),
                Forms\Components\FileUpload::make('img')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/posts')
                    ->nullable(),
                Forms\Components\Section::make('Slider / Banner')
                    ->schema([
                        Forms\Components\Toggle::make('is_banner'),
                        Forms\Components\TextInput::make('banner_position')
                            ->numeric()
                            ->nullable(),
                        Forms\Components\TextInput::make('banner_video')
                            ->label('Video URL')
                            ->maxLength(255)
                            ->nullable(),
                        Forms\Components\FileUpload::make('banner_img_mobile')
                            ->image()
                            ->disk('public')
                            ->directory('uploads/banners')
                            ->nullable(),
                    ])
                    ->visible(fn (Get $get): bool => $get('type') === 'share')
                    ->collapsed(),
                Forms\Components\RichEditor::make('content')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $type = static::getCurrentType();

        $columns = [
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->formatStateUsing(fn (int $state): string => $state === 10 ? 'Active' : 'Draft')
                ->color(fn (int $state): string => $state === 10 ? 'success' : 'danger'),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ];

        if (in_array($type, ['service', 'trainer', 'card', 'job'], true)) {
            array_splice($columns, 1, 0, [
                Tables\Columns\TextColumn::make('position')
                    ->sortable(),
            ]);
        }

        if (in_array($type, ['news', 'event'], true)) {
            array_splice($columns, 1, 0, [
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
            ]);
        }

        if (in_array($type, ['share', 'card'], true)) {
            array_splice($columns, 1, 0, [
                Tables\Columns\TextColumn::make('price')
                    ->sortable(),
            ]);
        }

        if ($type === 'share') {
            array_splice($columns, 2, 0, [
                Tables\Columns\IconColumn::make('is_banner')
                    ->boolean(),
            ]);
        }

        if ($type === 'event') {
            array_splice($columns, 2, 0, [
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),
            ]);
        }

        return $table
            ->columns($columns)
            ->defaultSort('position')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->where('type', '!=', 'setting');

        if (request()->routeIs(static::getRouteBaseName().'.index')) {
            $type = static::getCurrentType();
            $query->where('type', $type);

            if ($type === 'share') {
                $query->where('is_banner', false);
            }
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SeoRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
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
        $types = [
            'service' => ['label' => 'Services', 'icon' => 'heroicon-o-wrench-screwdriver'],
            'news' => ['label' => 'News', 'icon' => 'heroicon-o-newspaper'],
            'share' => ['label' => 'Promotions', 'icon' => 'heroicon-o-gift'],
            'trainer' => ['label' => 'Trainers', 'icon' => 'heroicon-o-user'],
            'event' => ['label' => 'Events', 'icon' => 'heroicon-o-calendar-days'],
            'job' => ['label' => 'Jobs', 'icon' => 'heroicon-o-briefcase'],
            'card' => ['label' => 'Cards', 'icon' => 'heroicon-o-credit-card'],
        ];

        return collect($types)->map(function (array $config, string $type) {
            return NavigationItem::make($config['label'])
                ->group('Content')
                ->icon($config['icon'])
                ->url(static::getUrl('index').'?type='.$type)
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteBaseName().'.*')
                    && static::getCurrentType() === $type);
        })->values()->all();
    }

    public static function getCurrentType(): string
    {
        $type = request('type', 'news');

        return in_array($type, ['news', 'service', 'share', 'trainer', 'event', 'job', 'card'], true)
            ? $type
            : 'news';
    }

    public static function getTypeLabel(string $type): string
    {
        return match ($type) {
            'service' => 'Services',
            'news' => 'News',
            'share' => 'Promotions',
            'trainer' => 'Trainers',
            'event' => 'Events',
            'job' => 'Jobs',
            'card' => 'Cards',
            default => 'Posts',
        };
    }
}
