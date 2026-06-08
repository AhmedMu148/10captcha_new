<?php

namespace App\Http\Controllers;

use App\Models\CustomImage;
use App\Models\CustomImagesTest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomImageController extends Controller
{
    /**
     * Show index of all custom images
     */
    public function index()
    {
        return view('custom-images.index');
    }

    /**
     * Show custom images test form
     */
    public function testForm()
    {
        return view('custom-images.test');
    }

    /**
     * Store test and process images
     */
    public function storeTest(Request $request)
    {
        // Custom validation: either image or base64 is required
        $validated = $request->validate([
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'base64' => 'nullable|string',
            'result' => 'nullable|string|max:512',
        ]);

        if (! $request->hasFile('image') && ! $request->filled('base64')) {
            return back()->withErrors(['image' => 'Please provide either an image or base64 string'])->withInput();
        }

        $uid = Auth::id();

        // Check if result is empty
        if (empty($request->result)) {
            return back()->with('error', 'The Known Correct Result is required!')->withInput();
        }

        // Check if user has an active plan
        $user = User::where('id', $uid)->first();
        if (! $user['balance_5d'] || $user['balance_5d'] < 25000) {
            return back()->with('error', 'You should have balance!')->withInput();
        }

        // Process image or base64
        $base64 = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageData = file_get_contents($image->getRealPath());
            $base64 = base64_encode($imageData);
        } elseif ($request->filled('base64')) {
            $base64 = $request->base64;
            if (strpos($base64, 'data:image') !== false) {
                $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
            }
        }

        if (! $base64) {
            return back()->with('error', 'Please provide either an image or base64 string')->withInput();
        }

        $result = $request->result;

        // Delete old records (older than 2 days)
        $twoDaysAgo = Carbon::now()->subDays(2);
        CustomImagesTest::where('created_at', '<', $twoDaysAgo)->delete();

        // Check if CustomImage table has any records
        $totalCustomImages = CustomImage::count();
        if ($totalCustomImages === 0) {
            return back()->with('error', __('Please try again later'))->withInput();
        }

        // Get all active custom images
        $customImages = CustomImage::where('status', 'Active')->get();

        if ($customImages->isEmpty()) {
            return back()->with('error', 'No active custom images found')->withInput();
        }

        // Generate hash
        $hash = md5(uniqid(rand(), true));

        // Create records for each custom image
        foreach ($customImages as $custom) {
            CustomImagesTest::create([
                'uid' => $uid,
                'base64' => $base64,
                'module' => $custom->code,
                'result' => $result,
                'hash' => $hash,
                'loop' => 0,
                'status' => 'Active',
            ]);
        }

        return redirect()->route('custom-image.results', ['hash' => $hash]);
    }

    /**
     * Show results page
     */
    public function showResults($hash)
    {
        $uid = Auth::id();
        $records = CustomImagesTest::where('hash', $hash)
            ->where('uid', $uid)
            ->orderBy('id')
            ->get();

        if ($records->isEmpty()) {
            return redirect()->route('custom-image.test')->with('error', 'No records found');
        }

        $base64String = $records->first()->base64;
        if (strpos($base64String, 'data:image') === false) {
            $base64String = 'data:image/jpeg;base64,'.$base64String;
        }
        $hasProcessing = $records->where('status', 'Active')->where('loop', '<', 3)->isNotEmpty();

        return view('custom-images.results', [
            'records' => $records,
            'hash' => $hash,
            'base64String' => $base64String,
            'hasProcessing' => $hasProcessing,
        ]);
    }

    /**
     * Send records to API for processing (one at a time for live updates)
     */
    public function sendRecords(Request $request)
    {
        $uid = Auth::id();

        // Get ONE active record that hasn't reached max attempts (3)
        $record = CustomImagesTest::where('uid', $uid)
            ->where('status', 'Active')
            ->where('loop', '<', 3)
            ->orderBy('id')
            ->first();

        if (! $record) {
            return response()->json([
                'processed' => 0,
                'remaining' => 0,
                'status' => 'completed',
            ]);
        }

        // Get user key
        $user = Auth::user();
        $key = $user->key ?? null;

        if (! $key) {
            return response()->json([
                'error' => 'no api key',
                'processed' => 0,
                'remaining' => 0,
                'status' => 'error',
            ], 400);
        }

        $url = 'http://ocr.captchaai.com/solve.php';

        // Prepare API request
        $data = [
            'key' => $key,
            'method' => 'base64',
            'body' => $record->base64,
            'module' => $record->module,
        ];

        $success = false;

        try {
            // Send to API using curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Always increment loop counter
            $updateData = ['loop' => $record->loop + 1];

            if ($curlError) {
                Log::error('CURL Error in sendRecords for module '.$record->module.': '.$curlError);
            } elseif (! empty($response)) {
                // Try JSON response first
                $jsonResponse = json_decode($response, true);

                if (is_array($jsonResponse) && isset($jsonResponse['status']) && $jsonResponse['status'] === 'ok') {
                    if (isset($jsonResponse['data'])) {
                        $updateData['result_ocr'] = trim($jsonResponse['data']);
                        $updateData['status'] = 'Inactive';
                        $success = true;
                    }
                } elseif (strpos($response, 'OK|') !== false) {
                    // Text response format
                    $resultOcr = str_replace('OK|', '', $response);
                    $updateData['result_ocr'] = trim($resultOcr);
                    $updateData['status'] = 'Inactive';
                    $success = true;
                }
            }

            // If reached max attempts without result, mark as inactive
            if ($updateData['loop'] >= 3 && ! isset($updateData['result_ocr'])) {
                $updateData['status'] = 'Inactive';
                $updateData['result_ocr'] = '-';
            }

            $record->update($updateData);

        } catch (\Exception $e) {
            Log::error('Exception in sendRecords for module '.$record->module.': '.$e->getMessage());
            $record->update(['loop' => $record->loop + 1]);
        }

        // Check if there are still active records
        $remainingRecords = CustomImagesTest::where('uid', $uid)
            ->where('status', 'Active')
            ->where('loop', '<', 3)
            ->count();

        return response()->json([
            'processed' => 1,
            'remaining' => $remainingRecords,
            'status' => $remainingRecords > 0 ? 'processing' : 'completed',
            'module' => $record->module,
            'success' => $success,
        ]);
    }

    /**
     * Test OCR API response
     */
    public function testOcr(Request $request)
    {
        // Get user key
        $user = Auth::user();
        $key = $user->key ?? null;

        if (! $key) {
            return response()->json(['error' => 'No API key found'], 400);
        }

        // Use a simple test image (base64 encoded small image)
        $testBase64 = 'data:image/png;base64,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'; // 1x1 pixel transparent PNG

        // Prepare API request
        $url = 'http://ocr.captchaai.com/solve.php';
        $data = [
            'key' => $key,
            'method' => 'base64',
            'body' => $testBase64,
            'module' => 'common-1', // Use a common module for testing
        ];

        try {
            // Send to API using curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('CURL Error in testOcr: '.$curlError);

                return response()->json([
                    'success' => false,
                    'error' => 'CURL Error: '.$curlError,
                    'http_code' => $httpCode,
                ], 500);
            }

            return response()->json([
                'success' => true,
                'response' => $response,
                'http_code' => $httpCode,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in testOcr: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Exception: '.$e->getMessage(),
            ], 500);
        }
    }
}
