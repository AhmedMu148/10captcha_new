<?php

namespace App\Filament\Resources\CustomImageResource\Pages;

use App\Filament\Resources\CustomImageResource;
use App\Services\CustomImageApiService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListCustomImages extends ListRecords
{
    protected static string $resource = CustomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync')
                ->label('Sync from API')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Sync Custom Images')
                ->modalDescription('This will fetch the latest custom images from the API and update the database. Are you sure you want to continue?')
                ->action(function () {
                    $service = app(CustomImageApiService::class);
                    $result = $service->syncCustomImages();
                    if ($result['success']) {
                        Notification::make()
                            ->title('Sync Successful')
                            ->body($result['message'])
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Sync Failed')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
