<?php

namespace App\Http\Controllers;

require_once 'C:\Users\Professional\PhpstormProjects\laravel\vendor\autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

class TwilioController extends Controller
{
    /**
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public function sendVerificationCode(Request $request)
    {
        $phoneNumber = $request->input('phone_number'); // Получите номер телефона от пользователя
        $verificationCode = mt_rand(1000, 9999); // Генерируйте случайный код

        // Сохраните код в кэше, связанный с номером телефона пользователя
        Cache::put('verification_code#' . $phoneNumber, $verificationCode, 600); // Например, на 10 минут

        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $sendernumber = getenv("TWILIO_PHONE_NUMBER");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create($phoneNumber, // Отправьте код на номер, введенный пользователем
                [
                    "body" => "Your verification code is: " . $verificationCode,
                    "from" => $sendernumber
                ]
            );

        return "Verification code sent.";
    }
}
