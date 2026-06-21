<?php

namespace App\Filament\Resources\AffiliateRelationResource\Pages;

use App\Filament\Resources\AffiliateRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAffiliateRelations extends ListRecords
{
    protected static string $resource = AffiliateRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
