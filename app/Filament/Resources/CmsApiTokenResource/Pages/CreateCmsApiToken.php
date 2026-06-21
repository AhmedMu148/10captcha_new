<?php

namespace App\Filament\Resources\CmsApiTokenResource\Pages;

use App\Filament\Resources\CmsApiTokenResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateCmsApiToken extends CreateRecord
{
    protected static string $resource = CmsApiTokenResource::class;

    protected ?string $rawToken = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate raw token: prefix + 48 characters
        $raw = 'cms_live_' . Str::random(48);
        $this->rawToken = $raw;

        $data['token_hash'] = hash('sha256', $raw);
        $data['token_prefix'] = substr($raw, 0, 12);
        $data['created_by_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        if ($this->rawToken) {
            Notification::make()
                ->title('API Token Minted')
                ->body("Copy this token now. It will NEVER be shown again:\n\n**{$this->rawToken}**")
                ->success()
                ->persistent()
                ->send();
        }
    }
}
