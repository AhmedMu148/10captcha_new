<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OcrApiService;

class SyncUserThreads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:sync-threads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user threads with OCR API';

    /**
     * Execute the console command.
     */
    public function handle(OcrApiService $ocrApiService): int
    {
        $this->info("Syncing threads for all users...");
        
        try {
            $response = $ocrApiService->syncUserThreads();
            $this->info("Sync completed. Response: " . $response);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
