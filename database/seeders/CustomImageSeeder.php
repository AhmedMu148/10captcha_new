<?php

namespace Database\Seeders;

use App\Models\CustomImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Starting custom images data transfer...\n";

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Clear existing data
            CustomImage::truncate();
            echo "Custom images table truncated.\n";

            $this->parseAndInsertCustomImages();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            echo "Transfer completed successfully.\n";

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            echo 'Error: '.$e->getMessage()."\n";
            throw $e;
        }
    }

    protected function parseAndInsertCustomImages(): void
    {
        $sqlFile = database_path('seeders/sql/custom_images.sql');

        if (! file_exists($sqlFile)) {
            throw new \Exception('File custom_images.sql not found');
        }

        $content = file_get_contents($sqlFile);

        preg_match('/INSERT INTO `custom_images`.*?VALUES\s*(.+?);/s', $content, $matches);

        if (empty($matches[1])) {
            throw new \Exception('No data found in file');
        }

        $processedCount = 0;
        $skippedCount = 0;

        $rows = $this->parseValueRows($matches[1]);

        foreach ($rows as $row) {
            if (count($row) >= 6) {
                $customImageData = [
                    'id' => (int) $row[0],
                    'code' => $this->cleanValue($row[1]),
                    'name' => $this->cleanValue($row[2]),
                    'description' => $this->cleanValue($row[3]),
                    'type' => (int) $row[4], // Keep original numeric type
                    'status' => $this->mapStatus((int) $row[5]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    CustomImage::create($customImageData);
                    $processedCount++;
                } catch (\Exception $e) {
                    echo "Error inserting custom image with id {$row[0]}: ".$e->getMessage()."\n";
                    $skippedCount++;
                }
            }
        }

        echo "$processedCount custom images saved.\n";
        if ($skippedCount > 0) {
            echo "$skippedCount records skipped due to errors.\n";
        }
    }

    protected function parseValueRows(string $valuesSection): array
    {
        $rows = [];
        $current = '';
        $inQuotes = false;
        $quoteChar = null;
        $parenthesesLevel = 0;

        for ($i = 0; $i < strlen($valuesSection); $i++) {
            $char = $valuesSection[$i];

            if (! $inQuotes) {
                if ($char === '(' && $parenthesesLevel === 0) {
                    $current = '';
                    $parenthesesLevel = 1;

                    continue;
                } elseif ($char === ')' && $parenthesesLevel === 1) {
                    $rows[] = $this->parseValues($current);
                    $current = '';
                    $parenthesesLevel = 0;

                    continue;
                }

                if ($char === '"' || $char === "'") {
                    $inQuotes = true;
                    $quoteChar = $char;
                }
            } else {
                if ($char === $quoteChar && ($i === 0 || $valuesSection[$i - 1] !== '\\')) {
                    $inQuotes = false;
                    $quoteChar = null;
                }
            }

            if ($parenthesesLevel === 1) {
                $current .= $char;
            }
        }

        return $rows;
    }

    protected function parseValues(string $valueRow): array
    {
        $values = [];
        $current = '';
        $inQuotes = false;
        $quoteChar = null;

        for ($i = 0; $i < strlen($valueRow); $i++) {
            $char = $valueRow[$i];

            if (! $inQuotes && ($char === '"' || $char === "'")) {
                $inQuotes = true;
                $quoteChar = $char;

                continue;
            }

            if ($inQuotes && $char === $quoteChar) {
                if ($i > 0 && $valueRow[$i - 1] === '\\') {
                    $current .= $char;

                    continue;
                }
                $inQuotes = false;
                $quoteChar = null;

                continue;
            }

            if (! $inQuotes && $char === ',') {
                $values[] = trim($current);
                $current = '';

                continue;
            }

            $current .= $char;
        }

        if ($current !== '') {
            $values[] = trim($current);
        }

        return $values;
    }

    protected function cleanValue(string $value): string
    {
        $value = trim($value, "'\"");

        return $value;
    }

    protected function mapStatus(int $oldStatus): string
    {
        return $oldStatus == 1 ? 'Active' : 'Inactive';
    }
}
