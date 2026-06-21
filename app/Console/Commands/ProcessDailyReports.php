<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReportDaily;
use App\Models\Report;
use App\Models\AffiliateRelation;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\DB;

class ProcessDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:process-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and archive daily reports and calculate affiliate commissions';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("Fetching daily reports from yesterday and earlier...");

        // Get daily reports older than today
        $reportDaily = ReportDaily::whereDate('created_at', '<', today())->get();

        if ($reportDaily->isEmpty()) {
            $this->info("No daily reports to process.");
            return Command::SUCCESS;
        }

        $merged = [];
        $ids = [];

        foreach ($reportDaily as $item) {
            $ids[] = $item->id;
            $key = $item->user_id . '_' . $item->type . '_' . $item->status;

            if (!isset($merged[$key])) {
                $merged[$key] = [
                    'user_id' => $item->user_id,
                    'type' => $item->type,
                    'status' => $item->status,
                    'count' => 0,
                    'price_5d' => 0
                ];
            }

            $merged[$key]['count'] += (int) $item->count;
            $merged[$key]['price_5d'] += (int) $item->price_5d;
        }

        $results = array_values($merged);
        $processedCount = 0;

        DB::transaction(function () use ($results, $ids, &$processedCount) {
            foreach ($results as $result) {
                // Save grouped daily data into reports table (archived reports)
                Report::create([
                    'user_id' => $result['user_id'],
                    'count' => $result['count'],
                    'type' => $result['type'],
                    'price_5d' => $result['price_5d'],
                    'status' => $result['status'],
                    'created_at' => now()->subDay(), // Dated as yesterday
                ]);

                // Check for super affiliate status
                if ($result['status'] == 3) {
                    // Update expired relations to 'Rejected'
                    AffiliateRelation::where('end_date', '<', now())->update(['status' => 'Rejected']);

                    // Find active affiliate relation for this user
                    $affRel = AffiliateRelation::where('user_id', $result['user_id'])
                        ->where('status', 'Approved')
                        ->first();

                    if ($affRel) {
                        $priceUsd = $result['price_5d'] / 100000;
                        $commAmount = $priceUsd * ($affRel->comm / 100);

                        // Save commission record
                        AffiliateCommission::create([
                            'aff_id' => $affRel->aff_id,
                            'aff_rel_id' => $affRel->id,
                            'comm_amount_5d' => (int) ($commAmount * 100000),
                            'comm_percent' => $affRel->comm,
                            'status' => 'Approved',
                        ]);
                    }
                }
                $processedCount++;
            }

            // Delete daily reports that were processed
            ReportDaily::whereIn('id', $ids)->delete();
        });

        $this->info("Successfully processed {$processedCount} grouped report records and cleared daily table.");
        return Command::SUCCESS;
    }
}
