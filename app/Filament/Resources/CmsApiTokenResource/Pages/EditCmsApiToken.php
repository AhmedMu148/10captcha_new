<?php

namespace App\Filament\Resources\CmsApiTokenResource\Pages;

use App\Filament\Resources\CmsApiTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCmsApiToken extends EditRecord
{
    protected static string $resource = CmsApiTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
