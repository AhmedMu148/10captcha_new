<?php

namespace App\Filament\Resources\CmsApiTokenResource\Pages;

use App\Filament\Resources\CmsApiTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCmsApiTokens extends ListRecords
{
    protected static string $resource = CmsApiTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
