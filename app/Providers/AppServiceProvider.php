<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginViewResponse;
use App\Http\Responses\CustomLoginViewResponse;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginViewResponse::class, CustomLoginViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            // Add your phone validation logic here

            // You can use regular expressions to validate the phone number.
            // For example, let's assume a valid phone number consists of 10 digits:
            $isValid = preg_match('/^\d{11}$/', $value);

            // Return true if the phone is valid, or false if it's not.
            return $isValid;
        });
    }
}
