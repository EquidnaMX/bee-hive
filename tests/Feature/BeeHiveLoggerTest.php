<?php

namespace Equidna\BeeHive\Tests\Feature;

use Equidna\BeeHive\Support\BeeHiveLogger;
use Equidna\BeeHive\Tests\TestCase;
use Illuminate\Support\Facades\Log;

class BeeHiveLoggerTest extends TestCase
{
    protected function tearDown(): void
    {
        BeeHiveLogger::setRandomFloatResolver(null);

        parent::tearDown();
    }

    public function testDoesNotLogWhenPackageLoggingIsDisabled(): void
    {
        config(['bee-hive.logging.enabled' => false]);
        $logSpy = Log::spy();

        BeeHiveLogger::log('Tenant warning', ['tenant_key' => 'id_tenant']);

        $logSpy->shouldNotHaveReceived('log');
    }

    public function testUsesConfiguredLoggingLevel(): void
    {
        config(['bee-hive.logging.enabled' => true]);
        config(['bee-hive.logging.level' => 'notice']);
        config(['bee-hive.logging.sample_rate' => 1.0]);
        $logSpy = Log::spy();

        BeeHiveLogger::log('Tenant warning', ['tenant_key' => 'id_tenant']);

        $logSpy->shouldHaveReceived('log')
            /** @phpstan-ignore-next-line */
            ->once()
            ->with('notice', 'Tenant warning', ['tenant_key' => 'id_tenant']);
    }

    public function testFallsBackToWarningForInvalidLevel(): void
    {
        config(['bee-hive.logging.enabled' => true]);
        config(['bee-hive.logging.level' => 'not-a-level']);
        config(['bee-hive.logging.sample_rate' => 1.0]);
        $logSpy = Log::spy();

        BeeHiveLogger::log('Tenant warning', ['tenant_key' => 'id_tenant']);

        $logSpy->shouldHaveReceived('log')
            /** @phpstan-ignore-next-line */
            ->once()
            ->with('warning', 'Tenant warning', ['tenant_key' => 'id_tenant']);
    }

    public function testAppendsEventCodeToContext(): void
    {
        config(['bee-hive.logging.enabled' => true]);
        config(['bee-hive.logging.level' => 'warning']);
        config(['bee-hive.logging.sample_rate' => 1.0]);
        $logSpy = Log::spy();

        BeeHiveLogger::log('Tenant warning', ['tenant_key' => 'id_tenant'], 'BEEHIVE_TEST_EVENT');

        $logSpy->shouldHaveReceived('log')
            /** @phpstan-ignore-next-line */
            ->once()
            ->with('warning', 'Tenant warning', [
                'tenant_key' => 'id_tenant',
                'event_code' => 'BEEHIVE_TEST_EVENT',
            ]);
    }

    public function testSkipsLoggingWhenSampleRateIsZero(): void
    {
        config(['bee-hive.logging.enabled' => true]);
        config(['bee-hive.logging.level' => 'warning']);
        config(['bee-hive.logging.sample_rate' => 0.0]);
        $logSpy = Log::spy();

        BeeHiveLogger::log('Tenant warning', ['tenant_key' => 'id_tenant']);

        $logSpy->shouldNotHaveReceived('log');
    }

    public function testLogsWhenFractionalSampleRateAcceptsDraw(): void
    {
        config(['bee-hive.logging.enabled' => true]);
        config(['bee-hive.logging.level' => 'warning']);
        config(['bee-hive.logging.sample_rate' => 0.25]);
        BeeHiveLogger::setRandomFloatResolver(static fn(): float => 0.20);
        $logSpy = Log::spy();

        BeeHiveLogger::log('Tenant warning', ['tenant_key' => 'id_tenant']);

        $logSpy->shouldHaveReceived('log')
            /** @phpstan-ignore-next-line */
            ->once()
            ->with('warning', 'Tenant warning', ['tenant_key' => 'id_tenant']);
    }

    public function testSkipsWhenFractionalSampleRateRejectsDraw(): void
    {
        config(['bee-hive.logging.enabled' => true]);
        config(['bee-hive.logging.level' => 'warning']);
        config(['bee-hive.logging.sample_rate' => 0.25]);
        BeeHiveLogger::setRandomFloatResolver(static fn(): float => 0.30);
        $logSpy = Log::spy();

        BeeHiveLogger::log('Tenant warning', ['tenant_key' => 'id_tenant']);

        $logSpy->shouldNotHaveReceived('log');
    }
}
