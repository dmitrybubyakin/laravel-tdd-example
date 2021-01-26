<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleSpreadsheetApp
{
    private Client $http;
    private string $deploymentId;

    public function __construct(Client $http, string $deploymentId)
    {
        $this->http = $http;
        $this->deploymentId = $deploymentId;
    }

    public function submitFormData(array $formData): void
    {
        $response = $this->http->post("https://script.google.com/macros/s/{$this->deploymentId}/exec", [
            'json' => $formData,
        ]);
    }
}
