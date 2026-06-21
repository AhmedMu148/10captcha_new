<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\ReportDaily;
use App\Models\SyncLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActionLogger;

class OcrDataController extends Controller
{
    /**
     * Store daily user log data from the external OCR system.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeData(Request $request)
    {
        $apiKey = $request->input('api_key');
        $action = $request->input('action');
        $configuredApiKey = (string) config('services.ocr.api_key', 'DjysVsFravWcCQT6JqhBWKhcgRx');

        // Verify API key and parameters
        if (!$action || !$apiKey || $apiKey !== $configuredApiKey) {
            return response('Error: insufficient parameters!', 400);
        }

        if ($action !== 'add') {
            return response('Error: action not supported', 400);
        }

        $dataUids = $request->input('data_uids');
        if (is_string($dataUids)) {
            $dataUidsArr = json_decode($dataUids, true);
        } else {
            $dataUidsArr = $dataUids;
        }

        if (!is_array($dataUidsArr) || empty($dataUidsArr)) {
            return response('Error: invalid or empty data_uids parameter!', 400);
        }

        // Fetch plans to map ocr_cap_id to price
        $plans = Plan::all();
        $plansName = $plans->pluck('price', 'ocr_cap_id')->toArray();
        $priceDefault = (float) config('services.ocr.default_price', 0.2);

        $res = false;

        try {
            DB::transaction(function () use ($dataUidsArr, $plansName, $priceDefault, &$res) {
                foreach ($dataUidsArr as $data) {
                    if (!isset($data['uid']) || !isset($data['cap_type']) || !isset($data['count'])) {
                        continue;
                    }

                    $uid = $data['uid'];
                    $capType = $data['cap_type'];
                    $count = (int) $data['count'];
                    $status = (int) ($data['status'] ?? 1);

                    // Determine the price per CAPTCHA
                    $rate = isset($plansName[$capType]) && is_numeric($plansName[$capType])
                        ? (float) $plansName[$capType]
                        : $priceDefault;

                    $pricePerOne = $rate / 1000;
                    $price = $pricePerOne * $count;
                    $price5d = (int) round($price * 100000);

                    // Save daily report record
                    $report = ReportDaily::create([
                        'user_id'  => $uid,
                        'count'    => $count,
                        'type'     => $capType,
                        'price_5d' => $price5d,
                        'status'   => $status,
                    ]);

                    if ($report) {
                        $res = true;
                    }

                    // Deduct user balance if status is 3
                    if ($status === 3) {
                        $user = User::find($uid);
                        if ($user) {
                            $user->balance_5d = (int) max(0, $user->balance_5d - $price5d);
                            $user->save();
                        }
                    }

                    // Write sync log tracking entry
                    SyncLog::create([
                        'uid'        => $uid,
                        'status'     => 1,
                        'created_at' => now(),
                    ]);
                }
            });
        } catch (\Throwable $e) {
            // Log the error
            if (class_exists(ActionLogger::class)) {
                ActionLogger::error('ocr', 'Error saving data_uids: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ], 'ocr');
            } else {
                \Illuminate\Support\Facades\Log::error('OCR data_uids sync error: ' . $e->getMessage());
            }

            return response('Error: transaction failed', 500);
        }

        if (!$res) {
            return response('');
        }

        return response('ok');
    }

    /**
     * Retrieve thread data for users (secured API).
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function secureData(Request $request)
    {
        $apiKey = $request->input('api_key');
        $action = $request->input('action');
        $configuredSecureApiKey = (string) config('services.ocr.secure_api_key', 'ZtfhyaR8H8RbGXp2V6UgR6CftFg');

        if (!$action || !$apiKey || $apiKey !== $configuredSecureApiKey) {
            return response('Error: insufficient parameters!!', 400);
        }

        if ($action !== 'data_users') {
            return response('Error: action not supported', 400);
        }

        $filePath = app_path('secure/.threads_data');
        if (!file_exists($filePath)) {
            // Check fallback for typo thrads_data
            $filePath = app_path('secure/.thrads_data');
        }

        if (!file_exists($filePath)) {
            return response('', 200);
        }

        $data = file_get_contents($filePath);
        return response($data, 200)->header('Content-Type', 'application/json');
    }
}

