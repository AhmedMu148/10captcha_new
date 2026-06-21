<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$page = \App\Models\Page::first();
$relationManager = new \App\Filament\Resources\PageResource\RelationManagers\PageSectionsRelationManager();
$relationManager->ownerRecord = $page;
$relationManager->pageClass = \App\Filament\Resources\PageResource\Pages\EditPage::class;

$section = new \App\Models\Section();
try {
    $relation = $section->pages();
    echo "pages() returned: " . get_class($relation) . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}



