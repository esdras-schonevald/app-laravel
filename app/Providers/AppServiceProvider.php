<?php

namespace App\Providers;

use App\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('not_weekend', function ($attribute, $value, $parameters, $validator) {
            $dayOfWeek = date('N', strtotime($value));
            return $dayOfWeek >= 1 && $dayOfWeek <= 5;
        });

        Validator::extend('not_coincide', function ($attribute, $value, $parameters, $validator) {
            [$userId, $startDate, $endDate] = $parameters;

            $coincidingActivities = Activity::where('user_id', $userId)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate]);
                })
                ->exists();

            return !$coincidingActivities;
        });
    }
}
