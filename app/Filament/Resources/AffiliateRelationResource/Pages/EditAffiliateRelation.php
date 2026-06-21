<?php

namespace App\Filament\Resources\AffiliateRelationResource\Pages;

use App\Filament\Resources\AffiliateRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliateRelation extends EditRecord
{
    protected static string $resource = AffiliateRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
