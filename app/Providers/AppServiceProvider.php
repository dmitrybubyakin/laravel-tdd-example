<?php

namespace App\Providers;

use App\Services\GoogleSpreadsheetApp;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GoogleSpreadsheetApp::class, function () {
            $http = new Client([]);
            $deploymentId = config('services.google_spreadsheet_app.deployment_id');

            return new GoogleSpreadsheetApp($http, $deploymentId);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
