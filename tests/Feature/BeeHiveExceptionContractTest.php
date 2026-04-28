<?php

namespace Equidna\BeeHive\Tests\Feature;

use Equidna\BeeHive\Exceptions\BeeHiveException;
use Equidna\BeeHive\Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BeeHiveExceptionContractTest extends TestCase
{
    public function testRendersEnterpriseContractByDefault(): void
    {
        config(['bee-hive.errors.contract' => 'enterprise']);
        config(['bee-hive.errors.code' => 'tenant_not_resolved']);
        config(['bee-hive.errors.status' => 422]);

        $request = Request::create('/api/orders', 'GET', server: ['HTTP_ACCEPT' => 'application/json']);
        $response = (new BeeHiveException())->render($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(422, $response->getStatusCode());
        $payload = $response->getData(true);

        $this->assertSame('tenant_not_resolved', $payload['error']['code']);
        $this->assertSame(422, $payload['error']['status']);
    }

    public function testRendersPlainTextResponseWhenRequestIsNotJson(): void
    {
        config(['bee-hive.errors.status' => 422]);

        $request = Request::create('/web/orders', 'GET');
        $response = (new BeeHiveException())->render($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame('text/plain; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('BeeHive tenant was not resolved.', (string) $response->getContent());
    }

    public function testUsesConfiguredHttpStatusCode(): void
    {
        config(['bee-hive.errors.status' => 403]);

        $request = Request::create('/api/orders', 'GET', server: ['HTTP_ACCEPT' => 'application/json']);
        $response = (new BeeHiveException())->render($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(403, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertSame(403, $payload['status']);
    }

    public function testFallsBackToDefaultStatusWhenConfiguredStatusIsInvalid(): void
    {
        config(['bee-hive.errors.status' => 200]);

        $request = Request::create('/api/orders', 'GET', server: ['HTTP_ACCEPT' => 'application/json']);
        $response = (new BeeHiveException())->render($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(422, $response->getStatusCode());
    }
}
