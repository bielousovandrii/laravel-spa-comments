<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mews\Captcha\Facades\Captcha;

class CaptchaController extends Controller
{
    public function getCaptcha()
    {
//        return response()->json(['captcha_src' => Captcha::src()]);
        return response()->json(['captcha_src'=> captcha_img()]);
    }

    public function verifyCaptcha(Request $request)
    {
        $rules = ['captcha' => 'required|captcha_api:'. request('key') . ',math'];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid captcha',
            ]);

        } else {
            return response()->json(['message' => 'CAPTCHA is valid']);
        }
    }
}
