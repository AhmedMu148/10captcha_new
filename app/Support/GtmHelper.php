<?php

namespace App\Support;
use App\Models\PymEvent;

class GtmHelper
{
    public static function getLocation(): array
    {
        $cip = null;
        $country = null;
        $countryName = null;
        $city = null;
        if (request()->server('HTTP_CLIENT_IP')) {
            $cip = request()->server('HTTP_CLIENT_IP');
        } elseif (request()->server('HTTP_X_FORWARDED_FOR')) {
            $cip = request()->server('HTTP_X_FORWARDED_FOR');
        } elseif (request()->server('HTTP_CF_CONNECTING_IP')) {
            $cip = request()->server('HTTP_CF_CONNECTING_IP');
        } else {
            $cip = request()->ip();
        }

        if ($cip) {
            $url = 'http://ip-api.com/json/'.urlencode($cip);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            if (! curl_errno($ch)) {
                $data = json_decode($response, true);
                if ($data && isset($data['status']) && $data['status'] === 'success') {
                    $country = $data['countryCode'] ?? null;
                    $countryName = $data['country'] ?? null;
                    $city = $data['city'] ?? null;
                }
            }
            curl_close($ch);
        }

        return [
            'ip' => $cip,
            'country_code' => $country,
            'country_name' => $countryName,
            'city' => $city,
        ];
    }

    public static function gtmData(string $event, array $data = [])
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        $email = hash('sha256', strtolower(trim($user->email)));
        if ($event === 'register') {
            $location = self::getLocation();

            return [
                'event' => 'user_registration',
                'userId' => $user->id,
                'em' => $email,
                'event_id' => uniqid('reg_'),
                'country' => $location['country_name'],
                'city' => $location['city'],
            ];
        }
        if ($event === 'login') {
            $location = self::getLocation();

            return [
                'event' => 'user_login',
                'userId' => $user->id,
                'em' => $email,
                'event_id' => uniqid('login_'),
                'country' => $location['country_name'],
                'city' => $location['city'],
            ];
        }
        if ($event === 'add_to_cart') {
            $location = self::getLocation();
            $value = isset($data['value']) && is_numeric($data['value']) ? (float) $data['value'] : 0;

            return [
                'event' => 'add_to_cart',
                'userId' => $user->id,
                'em' => $email,
                'event_id' => uniqid('act_'),
                'loginStatus' => 'logged_in',
                'value' => $value,
                'currency' => 'USD',
                'items' => [[
                    'item_id' => 'SKU_001',
                    'item_name' => '10Captcha',
                    'quantity' => 1,
                ]],
                'country' => $location['country_name'],
                'city' => $location['city'],
            ];
        }
        if ($event === 'checkout') {
            $location = self::getLocation();
            $value = isset($data['value']) && is_numeric($data['value']) ? (float) $data['value'] : 0;

            return [
                'event' => 'begin_checkout',
                'event_id' => uniqid('ic_'),
                'userId' => $user->id,
                'em' => $email,
                'loginStatus' => 'logged_in',
                'value' => $value,
                'paymentmethod' => $data['method'] ?? null,
                'currency' => 'USD',
                'items' => [[
                    'item_id' => 'SKU_001',
                    'item_name' => '10Captcha',
                    'price' => $value,
                    'quantity' => 1,
                ]],
                'country' => $location['country_name'],
                'city' => $location['city'],
            ];
        }

        // Purchase events: fetch pending pym_events rows, push each to dataLayer and mark as sent
        if ($event === 'purchase') {
            // Expect $data to contain uid if called externally, else use current authenticated user
            $uid = $data['uid'] ?? ($user->id ?? null);
            if (! $uid) {
                return false;
            }

            $payment_events = PymEvent::where('uid', $uid)->where('gtm_event', 0)->get();
            if ($payment_events->isEmpty()) {
                return false;
            }

            $location_info = self::getLocation();

            foreach ($payment_events as $payment_event) {
                $x = [];
                $x['userId'] = $uid;
                $x['em'] = $email;
                $x['event_id'] = uniqid('pur_');
                $x['loginStatus'] = 'logged_in';
                $x['event'] = 'purchase';
                $x['method'] = $payment_event->method ?? null;
                $x['transaction_id'] = $payment_event->payment_id ?? null;
                $x['value'] = (float) ($payment_event->value ?? 0);
                $x['tax'] = 0;
                $x['shipping'] = 0;
                $x['currency'] = 'USD';

                $item = [];
                $item['item_id'] = 'SKU_001';
                $item['item_name'] = '10Captcha';
                $item['price'] = $x['value'];
                $item['quantity'] = 1;

                $x['items'] = [$item];
                $x['country'] = $location_info['country_name'];
                $x['city'] = $location_info['city'];

                // Push to dataLayer for GTM
                self::renderScript($x);

                // Mark event as sent
                PymEvent::where('id', $payment_event->id)->update(['gtm_event' => 1]);            }

            return true;
        }

        return false;
    }

    public static function renderScript(array $data)
    {
        $data = json_encode($data, JSON_PRETTY_PRINT);
        $GTM_JS_Code = "
			<script>
			window.dataLayer = window.dataLayer || [];
			dataLayer.push($data);
			</script>
		";
        echo $GTM_JS_Code;
    }
}
