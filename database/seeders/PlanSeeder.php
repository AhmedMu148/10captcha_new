<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['id' => 1, 'name' => 'reCAPTCHA v2',         'ocr_cap_id' => 3, 'price' => '0.2',        'img' => 'https://10captcha.com/assets/img/re.png',              'success' => 96,  'speed' => 40, 'sort' => 1, 'status' => 1],
            ['id' => 2, 'name' => 'reCAPTCHA v3',         'ocr_cap_id' => 5, 'price' => '0.25',       'img' => 'https://10captcha.com/assets/img/re.png',              'success' => 100, 'speed' => 15, 'sort' => 2, 'status' => 1],
            ['id' => 3, 'name' => 'reCAPTCHA Enterprise', 'ocr_cap_id' => 6, 'price' => '0.4',        'img' => 'https://10captcha.com/assets/img/re.png',              'success' => 90,  'speed' => 15, 'sort' => 3, 'status' => 1],
            ['id' => 4, 'name' => 'hCAPTCHA',             'ocr_cap_id' => 8, 'price' => '0.4',        'img' => 'https://capmonster.cloud/img/landing/hcaptcha.svg',    'success' => 86,  'speed' => 24, 'sort' => 5, 'status' => 0],
            ['id' => 5, 'name' => 'Image Captcha',        'ocr_cap_id' => 1, 'price' => '0.1',        'img' => 'https://10captcha.com/assets/img/text.svg',            'success' => 99,  'speed' => 1,  'sort' => 6, 'status' => 1],
            ['id' => 6, 'name' => 'FunCAPTCHA',           'ocr_cap_id' => 7, 'price' => 'Contact Us', 'img' => 'https://10captcha.com/assets/img/fun.svg',             'success' => 0,   'speed' => 0,  'sort' => 7, 'status' => 1],
            ['id' => 7, 'name' => 'GeeTest',              'ocr_cap_id' => 0, 'price' => 'Coming Soon','img' => 'https://10captcha.com/assets/img/geetest.svg',         'success' => 0,   'speed' => 0,  'sort' => 8, 'status' => 1],
            ['id' => 8, 'name' => 'reCAPTCHA Invisible',  'ocr_cap_id' => 4, 'price' => '0.2',        'img' => 'https://10captcha.com/assets/img/re.png',              'success' => 95,  'speed' => 30, 'sort' => 4, 'status' => 1],
        ];

        DB::table('plans')->upsert(
            $plans,
            ['id'],
            ['name', 'ocr_cap_id', 'price', 'img', 'success', 'speed', 'sort', 'status']
        );
    }
}
