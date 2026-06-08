# Admin Panel — FilamentPHP v3

---

## Installation

```bash
composer require filament/filament:"^3.3"
php artisan filament:install --panels
php artisan make:filament-panel admin
```

---

## Panel Configuration

**File**: `app/Providers/Filament/AdminPanelProvider.php`

```php
<?php

namespace App\\Providers\\Filament;

use Filament\\Panel;
use Filament\\PanelProvider;
use Filament\\Support\\Colors\\Color;
use Illuminate\\Cookie\\Middleware\\EncryptCookies;
use Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse;
use Illuminate\\Session\\Middleware\\StartSession;
use Illuminate\\View\\Middleware\\ShareErrorsFromSession;
use Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken;
use Illuminate\\Routing\\Middleware\\SubstituteBindings;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Extra Fitness')
            ->brandLogo(asset('img/logo-admin.svg'))
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => Color::hex('#c8102e'),
            ])
            ->font('Oswald')
            ->darkMode(true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->widgets([
                \\App\\Filament\\Widgets\\StatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
            ])
            ->authMiddleware([
                'auth',
            ]);
    }
}
```

---

## Resources (Priority Order)

| Resource | Model | Navigation Group | Features |
|----------|-------|------------------|----------|
| `CallbackResource` | `Callback` | Заявки | View, Export CSV, Status toggle |
| `ContactResource` | `Contact` | Заявки | View, Reply via Mail, Export |
| `ServiceResource` | `Service` | Контент | Media Library, Rich Editor, Categories |
| `ShareResource` | `Share` | Контент | Media Library, Date Range Filter |
| `NewsResource` | `News` | Контент | Categories, Scheduling, SEO |
| `TrainerResource` | `Trainer` | Команда | Media Library, Specializations |
| `EventResource` | `Event` | Контент | Media Library, Date Filter |
| `ProgramResource` | `Program` | Контент | Media Library, Trainer Relation |
| `JobResource` | `Job` | Команда | Application Management |
| `ArticleResource` | `Article` | Контент | Categories, SEO |
| `ClubResource` | `Club` | Настройки | Media Library, Contacts |
| `SettingResource` | `Setting` | Настройки | Key-Value Editor, JSON |
| `UserResource` | `User` | Система | Roles, Permissions |

---

## Example Resource: CallbackResource

**File**: `app/Filament/Resources/CallbackResource.php`

```php
<?php

namespace App\\Filament\\Resources;

use App\\Filament\\Resources\\CallbackResource\\Pages;
use App\\Models\\Callback;
use Filament\\Forms;
use Filament\\Resources\\Resource;
use Filament\\Tables;
use Filament\\Tables\\Table;

class CallbackResource extends Resource
{
    protected static ?string $model = Callback::class;
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Заявки';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\\Form $form): Forms\\Form
    {
        return $form->schema([
            Forms\\Components\\Section::make('Информация о клиенте')
                ->schema([
                    Forms\\Components\\TextInput::make('name')
                        ->label('Имя')
                        ->disabled(),
                    Forms\\Components\\TextInput::make('phone')
                        ->label('Телефон')
                        ->tel()
                        ->disabled(),
                    Forms\\Components\\Select::make('club_id')
                        ->label('Клуб')
                        ->relationship('club', 'title')
                        ->disabled(),
                ])->columns(2),

            Forms\\Components\\Section::make('Статус')
                ->schema([
                    Forms\\Components\\Toggle::make('processed')
                        ->label('Обработано')
                        ->inline(false),
                    Forms\\Components\\DateTimePicker::make('processed_at')
                        ->label('Дата обработки')
                        ->disabled(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\\Columns\\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable()
                    ->copyable(),
                Tables\\Columns\\TextColumn::make('club.title')
                    ->label('Клуб')
                    ->badge()
                    ->color('primary'),
                Tables\\Columns\\IconColumn::make('processed')
                    ->label('Статус')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\\Filters\\SelectFilter::make('club_id')
                    ->label('Клуб')
                    ->relationship('club', 'title'),
                Tables\\Filters\\Filter::make('processed')
                    ->label('Обработано')
                    ->query(fn ($query) => $query->whereNotNull('processed_at')),
                Tables\\Filters\\Filter::make('created_at')
                    ->form([
                        Forms\\Components\\DatePicker::make('created_from'),
                        Forms\\Components\\DatePicker::make('created_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\\Actions\\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkAction::make('markProcessed')
                    ->label('Отметить как обработанные')
                    ->icon('heroicon-o-check')
                    ->action(fn (Collection $records) => $records->each->update(['processed' => true, 'processed_at' => now()])),
                Tables\\Actions\\ExportBulkAction::make()
                    ->label('Экспорт CSV'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\\ListCallbacks::route('/'),
            'view' => Pages\\ViewCallback::route('/{record}'),
        ];
    }
}
```

---

## Media Library Integration

**In Models** (e.g., `Service.php`):

```php
use Spatie\\MediaLibrary\\HasMedia;
use Spatie\\MediaLibrary\\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
```

**In Filament Resource**:

```php
use Filament\\Forms\\Components\\FileUpload;

Forms\\Components\\FileUpload::make('images')
    ->label('Изображения')
    ->image()
    ->imageEditor()
    ->disk('public')
    ->directory('services')
    ->visibility('public')
    ->maxSize(5120)
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->imageEditorAspectRatios([
        '16:9',
        '4:3',
        '1:1',
    ]);
```

---

## Widgets

**File**: `app/Filament/Widgets/StatsOverview.php`

```php
<?php

namespace App\\Filament\\Widgets;

use App\\Models\\Callback;
use App\\Models\\Contact;
use App\\Models\\Service;
use App\\Models\\Trainer;
use Filament\\Widgets\\StatsOverviewWidget\\Stat;
use Filament\\Widgets\\StatsOverviewWidget;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Заявки на звонок', Callback::count())
                ->description('Всего за все время')
                ->descriptionIcon('heroicon-o-phone')
                ->color('primary'),
            Stat::make('Контактные формы', Contact::count())
                ->description('Новые за неделю: ' . Contact::where('created_at', '>=', now()->subWeek())->count())
                ->descriptionIcon('heroicon-o-envelope')
                ->color('success'),
            Stat::make('Услуги', Service::active()->count())
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),
            Stat::make('Тренеры', Trainer::active()->count())
                ->descriptionIcon('heroicon-o-users')
                ->color('danger'),
        ];
    }
}
```

---

## Permissions & Roles

```php
// config/permission.php
return [
    'roles' => ['admin', 'editor', 'viewer'],
    'permissions' => [
        'view_callbacks',
        'manage_callbacks',
        'view_contacts',
        'manage_contacts',
        'view_content',
        'manage_content',
        'view_users',
        'manage_users',
    ],
];
```

**In AdminPanelProvider**:

```php
->plugins([
    \\BezhanSalleh\\FilamentShield\\FilamentShieldPlugin::make()
        ->gridColumns([
            'default' => 1,
            'sm' => 2,
            'lg' => 3,
        ])
        ->sectionColumnSpan(1)
        ->checkboxListColumns([
            'default' => 1,
            'sm' => 2,
            'lg' => 3,
        ])
])
```

---

## Navigation Groups Order

```php
protected static ?int $navigationSort = 1; // Заявки
protected static ?int $navigationSort = 10; // Контент
protected static ?int $navigationSort = 20; // Команда
protected static ?int $navigationSort = 30; // Настройки
protected static ?int $navigationSort = 40; // Система
```

---

## Navigation

- [← Roadmap](./roadmap.md)
- [Foundation →](./foundation.md)
- [Deployment →](./deployment.md)
- [Patterns →](./patterns.md)