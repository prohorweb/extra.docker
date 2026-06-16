<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?string $slug = 'settings';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettingsData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identity')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site name'),
                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('uploads/settings'),
                        Forms\Components\FileUpload::make('logo_dark')
                            ->image()
                            ->disk('public')
                            ->directory('uploads/settings'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Contacts')
                    ->description('Global fallback contacts')
                    ->schema([
                        Forms\Components\TextInput::make('phone_main')
                            ->label('Main phone')
                            ->tel(),
                        Forms\Components\TextInput::make('email_main')
                            ->label('Main email')
                            ->email(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Social')
                    ->schema([
                        Forms\Components\TextInput::make('social_vk')
                            ->label('VK')
                            ->url(),
                        Forms\Components\TextInput::make('social_instagram')
                            ->label('Instagram')
                            ->url(),
                        Forms\Components\TextInput::make('social_youtube')
                            ->label('YouTube')
                            ->url(),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\TextInput::make('footer_copyright'),
                        Forms\Components\Textarea::make('footer_legal')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Analytics')
                    ->schema([
                        Forms\Components\TextInput::make('yandex_metrica_id')
                            ->label('Yandex Metrica ID'),
                        Forms\Components\TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Pages content')
                    ->schema([
                        Forms\Components\TextInput::make('about_title'),
                        Forms\Components\RichEditor::make('about_content')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('about_img')
                            ->image()
                            ->disk('public')
                            ->directory('uploads/settings'),
                        Forms\Components\TextInput::make('cards_page_title')
                            ->label('Cards page title'),
                        Forms\Components\RichEditor::make('cards_page_content')
                            ->label('Cards page content')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getSettingsData(): array
    {
        $keys = [
            'site_name',
            'logo',
            'logo_dark',
            'phone_main',
            'email_main',
            'social_vk',
            'social_instagram',
            'social_youtube',
            'footer_copyright',
            'footer_legal',
            'yandex_metrica_id',
            'google_analytics_id',
            'about_title',
            'about_content',
            'about_img',
            'cards_page_title',
            'cards_page_content',
        ];

        $data = [];

        foreach ($keys as $key) {
            $data[$key] = Setting::get($key);
        }

        return $data;
    }
}
