<?php

namespace Equidna\BeeHive\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BeeHiveLogger
{
    /** @var (callable(): float)|null */
    private static $randomFloatResolver = null;

    /** @param array<string, mixed> $context */
    public static function log(string $message, array $context = [], string|null $eventCode = null): void
    {
        if (!(bool) Config::get('bee-hive.logging.enabled', true)) {
            return;
        }

        if (!self::shouldSample()) {
            return;
        }

        $level = (string) Config::get('bee-hive.logging.level', 'warning');
        $allowedLevels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

        if (!in_array($level, $allowedLevels, true)) {
            $level = 'warning';
        }

        if ($eventCode !== null) {
            $context['event_code'] = $eventCode;
        }

        // Emitted logs still respect the global channel/handler level configuration.
        Log::log($level, $message, $context);
    }

    private static function shouldSample(): bool
    {
        $sampleRate = (float) Config::get('bee-hive.logging.sample_rate', 1.0);

        if ($sampleRate <= 0.0) {
            return false;
        }

        if ($sampleRate >= 1.0) {
            return true;
        }

        $draw = self::$randomFloatResolver !== null
            ? (float) call_user_func(self::$randomFloatResolver)
            : mt_rand() / mt_getrandmax();

        return $draw <= $sampleRate;
    }

    /** @param (callable(): float)|null $resolver */
    public static function setRandomFloatResolver(callable|null $resolver): void
    {
        self::$randomFloatResolver = $resolver;
    }
}
