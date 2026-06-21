<?php

namespace App\Services;

use App\Models\PymEvent;
use App\Models\Payment;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class OcrApiService
{
    private string $apiUrl = 'https://ocr.10captcha.com/api/user-api.php';

    private string $apiKey = 'K3CSJbS7rHfYNMjenEUuxjZVcGH';

    private function doCurl(array $data): string
    {
        File::ensureDirectoryExists(storage_path('logs/ocr'));

        $data['api_key'] = $this->apiKey;

        try {
            $response = Http::asForm()
                ->withUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)')
                ->timeout(30)
                ->post($this->apiUrl, $data);

            $result = $response->body();

            // Avoid printing debug output (var_dump) in production responses
            ActionLogger::info('ocr', 'OCR API Request', [
                'url' => $this->apiUrl,
                'data' => $data,
                'response' => $result,
            ], 'ocr');

            return $result;
        } catch (\Exception $e) {
            ActionLogger::error('ocr', 'OCR API Error: ' . $e->getMessage(), [
                'url' => $this->apiUrl,
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ], 'ocr');

            return '';
        }
    }

    public function syncUserThreads(?int $uid = null): string
    {
        $data = [];
        $data['action'] = 'user_threads';
        $thread_list = Thread::orderBy('bigger_than', 'asc')->get();
        $user_threads = 0;
        
        //// if UID
        if ($uid) {
            $data['users'] = [];
            $user = User::where('id', $uid)->first();
            if (!$user) return '';
            if ($user->status == 3) return '';
            if ($user->status == 4) return '';
            if (!$user->balance_5d) return '';
            if ($user->balance_5d <= 25000) return '';
            if (!$user->api_key) return '';

            $data['delete'] = 0;

            // get user details -> threads
            foreach ($thread_list as $thread) {
                if (($user->balance_5d / 100000) > $thread->bigger_than) {
                    $user_threads = $thread->threads;
                }
            }
            if (!$user_threads) return '';

            // prepare data array => uid, threads, key
            $x = [];
            $x['uid'] = $uid;
            $x['key'] = $user->api_key;
            $x['threads'] = $user_threads;
            $x['balance_5d'] = $user->balance_5d;

            $data['users'][] = $x;
            return $this->doCurl($data);
        } else {
            $data['delete'] = 1;

            // get user_threads details
            $users = User::all();
            if (!$users) {
                return $this->doCurl($data);
            }

            $threads = $thread_list->toArray();

            $bigger_than_list = array_column($threads, 'bigger_than');
            $threads_list = array_column($threads, 'threads');

            $data_users = [];
            // prepare data array => uid, threads, key
            foreach ($users as $user) {

                if (!$user->api_key) continue;
                if ($user->status == 0) continue;
                if ($user->status == 3) continue;
                if ($user->status == 4) continue;
                if (!$user->balance_5d) continue;
                if ($user->balance_5d <= 25000) continue;

                $balance = $user->balance_5d / 100000;

                $valid_keys = array_keys(array_filter($bigger_than_list, fn($v) => $balance > $v));

                if ($valid_keys) {
                    $last_key = end($valid_keys);
                    $user_threads = $threads_list[$last_key];
                }

                // create $data array
                $x = [];
                $x['uid'] = $user->id;
                $x['api_key'] = $user->api_key;
                $x['threads'] = $user_threads;
                $x['balance_5d'] = $user->balance_5d;
                $data_users[] = $x;
            }
            $data_users_str = json_encode($data_users);

            $secureDir = app_path('secure');
            File::ensureDirectoryExists($secureDir);
            file_put_contents($secureDir . '/.threads_data', $data_users_str);

            $data['data_users'] = 'secure_file';
            $data['action'] = 'user_threads_bulk';

            return $this->doCurl($data);
        }
    }
}
