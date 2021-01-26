<?php

namespace Tests;

use App\Services\GoogleSpreadsheetApp;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->mock('captcha', function (MockInterface $mock): void {
            $mock
                ->shouldReceive('verifyResponse')
                ->andReturnUsing(fn ($value) => $value === 'ok');
        });

        $this->mock(GoogleSpreadsheetApp::class, function (MockInterface $mock): void {
            $mock->shouldReceive('submitFormData');
        });
    }
}
