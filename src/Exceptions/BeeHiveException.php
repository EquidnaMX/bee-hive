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

    private const ASCII_BEE = "  \\\   /\n   )_(\n  (o o)\n /  V  \\\n/(  _  )\\\n  ^^ ^^";

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

        if ($this->shouldIncludeDecorativePayload()) {
            $body = self::ASCII_BEE . "\n\n" . $body;
        }

        return new Response(
            $body,
            $statusCode,
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }

    /** @return array<string, mixed> */
    private function buildJsonPayload(int $statusCode): array
    {
        $errorCode = (string) Config::get('bee-hive.errors.code', self::DEFAULT_ERROR_CODE);
        $contract  = (string) Config::get('bee-hive.errors.contract', 'enterprise');

        $payload = match ($contract) {
            'flat' => [
                'message' => $this->getMessage(),
                'error'   => $errorCode,
            ],
            'problem_details' => [
                'type'   => 'urn:beehive:error:' . $errorCode,
                'title'  => 'BeeHive Exception',
                'status' => $statusCode,
                'detail' => $this->getMessage(),
                'code'   => $errorCode,
            ],
            default => [
                'error' => [
                    'code'    => $errorCode,
                    'message' => $this->getMessage(),
                    'status'  => $statusCode,
                ],
            ],
        };

        if ($this->shouldIncludeDecorativePayload()) {
            $payload['bee'] = self::ASCII_BEE;
        }

        return $payload;
    }

    private function shouldIncludeDecorativePayload(): bool
    {
        return (bool) Config::get('bee-hive.errors.include_decorative_payload', false);
    }

    private function statusCode(): int
    {
        $status = (int) Config::get('bee-hive.errors.status', self::DEFAULT_STATUS_CODE);

        return $status >= 400 && $status <= 599 ? $status : self::DEFAULT_STATUS_CODE;
    }
}
