<?php

namespace App\Filament\Resources\CustomImageResource\Pages;

use App\Filament\Resources\CustomImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomImage extends EditRecord
{
    protected static string $resource = CustomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
