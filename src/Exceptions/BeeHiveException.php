<?php

namespace Equidna\BeeHive\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use RuntimeException;

class BeeHiveException extends RuntimeException
{
    private const DEFAULT_STATUS_CODE = 422;
    private const DEFAULT_ERROR_CODE = 'tenant_not_resolved';

    public function __construct(string $message = 'BeeHive tenant was not resolved.')
    {
        parent::__construct($message, self::DEFAULT_STATUS_CODE);
    }

    public function render(Request $request): Response|JsonResponse
    {
        $statusCode = $this->statusCode();

        if ($request->expectsJson()) {
            return new JsonResponse($this->buildJsonPayload($statusCode), $statusCode);
        }

        $body = $this->getMessage();

        return new Response(
            $body,
            $statusCode,
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }

    /** @return array<string, mixed> */
    private function buildJsonPayload(int $statusCode): array
    {
        return [
            'type'   => 'urn:beehive:error:' . self::DEFAULT_ERROR_CODE,
            'title'  => 'BeeHive Exception',
            'status' => $statusCode,
            'detail' => $this->getMessage(),
            'code'   => self::DEFAULT_ERROR_CODE,
        ];
    }

    private function statusCode(): int
    {
        $status = (int) Config::get('bee-hive.errors.status', self::DEFAULT_STATUS_CODE);

        return $status >= 400 && $status <= 599 ? $status : self::DEFAULT_STATUS_CODE;
    }
}
