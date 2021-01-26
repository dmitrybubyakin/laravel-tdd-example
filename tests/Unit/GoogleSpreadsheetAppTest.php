<?php

namespace Tests\Unit;

use App\Services\GoogleSpreadsheetApp;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class GoogleSpreadsheetAppTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function an_exception_is_thrown_when_the_response_is_not_successful(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Something went wrong. Cannot append the form data.');

        /** @var Client|\Mockery\MockInterface */
        $http = Mockery::mock(Client::class);
        $http->shouldReceive('post')->andReturn(new Response(400, [], 'error'));

        $service = new GoogleSpreadsheetApp($http, 'deploymentId');
        $service->submitFormData([]);
    }
}
