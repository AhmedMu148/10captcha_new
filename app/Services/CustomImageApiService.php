<?php

namespace App\Services;

use App\Models\CustomImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomImageApiService
{
    protected string $apiUrl;

    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.custom_image_api.url');
        $this->apiKey = config('services.custom_image_api.key');
    }

    /**
     * Sync custom images data from API
     */
    public function syncCustomImages(): array
    {
        try {
            $response = $this->fetchCustomImagesFromApi();

            if (! $response['success']) {
                return [
                    'success' => false,
                    'message' => $response['message'],
                    'synced_count' => 0,
                ];
            }

            $syncedCount = $this->updateCustomImages($response['data']);

            return [
                'success' => true,
                'message' => "Successfully synced {$syncedCount} custom images.",
                'synced_count' => $syncedCount,
            ];
        } catch (\Exception $e) {
            Log::error('Custom Image API Sync Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to sync custom images: ' . $e->getMessage(),
                'synced_count' => 0,
            ];
        }
    }

    /**
     * Fetch custom images data from API
     */
    protected function fetchCustomImagesFromApi(): array
    {
        try {
            $response = Http::asForm()
                ->withUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)')
                ->post($this->apiUrl, [
                    'api_key' => $this->apiKey,
                    'action' => 'get_custom_images',
                ]);

            if (! $response->body()) {
                return [
                    'success' => false,
                    'message' => 'API request failed with status: ' . $response->status(),
                    'data' => null,
                ];
            }

            $data = $response->json();

            if (empty($data) || ! is_array($data)) {
                return [
                    'success' => false,
                    'message' => 'No valid data received from API',
                    'data' => null,
                ];
            }

            return [
                'success' => true,
                'message' => 'Data fetched successfully',
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch data from API: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Update custom images in database
     */
    protected function updateCustomImages(array $apiData): int
    {
        try {
            // start transaction

            // Clear existing data
            CustomImage::truncate();

            $syncedCount = 0;

            foreach ($apiData as $item) {
                CustomImage::create([
                    'id' => $item['sort'] ?? null,
                    'code' => $item['code'] ?? '',
                    'name' => $item['name'] ?? '',
                    'description' => $item['description'] ?? '',
                    'type' => $item['type'] ?? 1,
                    'status' => $item['status'] == 1 ? 'Active' : 'Inactive',
                ]);

                $syncedCount++;
            }

            return $syncedCount;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
