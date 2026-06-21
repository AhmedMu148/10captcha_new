<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteMapSeeder extends Seeder
{
    protected int $batchSize = 1000;

    public function run(): void
    {
        $this->command?->info('Importing site_map (optimized)...');

        $sqlFile = database_path('seeders/sql/site_map.sql');
        if (! file_exists($sqlFile)) {
            $this->command?->error('site_map.sql not found at: ' . $sqlFile);
            return;
        }

        $content = file_get_contents($sqlFile);

        // Find INSERT INTO `site_map` ... VALUES (...) ; blocks
        preg_match_all('/INSERT\s+INTO\s+`site_map`[\s\S]*?VALUES\s*(\(.+?\));/is', $content, $matches);

        if (empty($matches[1])) {
            $this->command?->error('No INSERT statements found in site_map.sql');
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('SET AUTOCOMMIT=0');

        // Truncate target table
        DB::table('site_map')->truncate();
        $this->command?->info('Table site_map truncated.');

        $processed = 0;
        $batchData = [];

        foreach ($matches[1] as $index => $valuesSection) {
            $rows = $this->parseValueRows($valuesSection);

            foreach ($rows as $row) {
                // row expected: id, url, status
                $id = isset($row[0]) ? (int) $this->cleanValue($row[0]) : null;
                $url = isset($row[1]) ? $this->cleanValue($row[1]) : null;
                $status = isset($row[2]) ? (int) $this->cleanValue($row[2]) : 0;

                $record = [
                    'id' => $id,
                    'url' => $url,
                    'status' => $status,
                ];

                $batchData[] = $record;

                if (count($batchData) >= $this->batchSize) {
                    $this->insertBatch($batchData, $processed);
                    $batchData = [];
                }
            }

            if (($index + 1) % 10 === 0) {
                $this->command?->info('Processed ' . ($index + 1) . '/' . count($matches[1]) . " statements...");
            }
        }

        if (! empty($batchData)) {
            $this->insertBatch($batchData, $processed);
        }

        DB::statement('COMMIT');
        DB::statement('SET AUTOCOMMIT=1');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command?->info("Import completed: $processed records inserted.");
    }

    protected function insertBatch(array &$batchData, int &$processed): void
    {
        if (empty($batchData)) {
            return;
        }

        try {
            DB::table('site_map')->insert($batchData);
            $processed += count($batchData);
            $this->command?->info("Progress: $processed records processed...");
        } catch (\Exception $e) {
            $this->command?->error('Batch insert error, falling back to single inserts: ' . $e->getMessage());
            foreach ($batchData as $data) {
                try {
                    DB::table('site_map')->insert($data);
                    $processed++;
                } catch (\Exception $e2) {
                    $this->command?->error('Skipped site_map id ' . ($data['id'] ?? 'n/a') . ': ' . $e2->getMessage());
                }
            }
        }
    }

    protected function parseValueRows(string $valuesSection): array
    {
        $rows = [];
        // match balanced parentheses rows
        preg_match_all('/\(([^)]+(?:\([^)]*\)[^)]*)*)\)/', $valuesSection, $matches);

        if (empty($matches[1])) {
            return $rows;
        }

        foreach ($matches[1] as $rowString) {
            $rows[] = $this->parseValues($rowString);
        }

        return $rows;
    }

    protected function parseValues(string $valueRow): array
    {
        // normalize escaped quotes
        $valueRow = str_replace("\\'", "''", $valueRow);
        $valueRow = str_replace('\\"', '""', $valueRow);

        return str_getcsv($valueRow, ',', "'");
    }

    protected function cleanValue(string $value): string
    {
        return trim($value, "'\" ");
    }
}
