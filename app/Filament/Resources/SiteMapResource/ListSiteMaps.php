<?php

namespace App\Filament\Resources\SiteMapResource\Pages;

use App\Filament\Resources\SiteMapResource;
use App\Models\SiteMap;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSiteMaps extends ListRecords
{
    protected static string $resource = SiteMapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate')
                ->label('Generate')
                ->color('warning')
                ->action('generate'),
            Actions\CreateAction::make(),
        ];
    }

    public function generate(): void
    {
        $urls = SiteMap::where('status', 1)->pluck('url')->all();

        $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        foreach ($urls as $url) {
            $xml .= "

<url>
<loc>{$url}</loc>
<changefreq>weekly</changefreq>
</url>
";
        }

        $xml .= '
</urlset>

';

        // write to public folder
        file_put_contents(public_path('sitemap-main.xml'), $xml);

        Notification::make()
            ->title('Site Map Generated!')
            ->success()
            ->send();
    }
}
