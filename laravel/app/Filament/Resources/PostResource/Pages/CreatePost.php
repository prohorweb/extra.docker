<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'type' => PostResource::getCurrentType(),
            'status' => 10,
            'position' => 0,
            'is_open' => true,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return PostResource::getUrl('index').'?type='.$this->record->type;
    }
}
