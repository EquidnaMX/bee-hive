<?php

namespace Equidna\BeeHive\Tests\Feature;

use Equidna\BeeHive\Tests\Fixtures\Models\CustomTenantKeyModel;
use Equidna\BeeHive\Exceptions\BeeHiveException;
use Equidna\BeeHive\Tests\Fixtures\Models\TenantAwareModel;
use Equidna\BeeHive\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TenantIsolationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('tenant_models');
        Schema::dropIfExists('custom_tenant_models');

        Schema::create('tenant_models', function ($table): void {
            $table->id();
            $table->unsignedBigInteger('id_tenant')->nullable();
            $table->string('name');
        });

        Schema::create('custom_tenant_models', function ($table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('name');
        });
    }

    public function testItFiltersResultsByResolvedTenant(): void
    {
        Config::set('bee-hive.static_tenant_id', 10);

        TenantAwareModel::query()->withoutGlobalScopes()->insert([
            ['name' => 'tenant-10', 'id_tenant' => 10],
            ['name' => 'tenant-11', 'id_tenant' => 11],
        ]);

        $rows = TenantAwareModel::query()->pluck('name')->all();

        $this->assertSame(['tenant-10'], $rows);
    }

    public function testItThrowsWhenTenantIsMissing(): void
    {
        Config::set('bee-hive.static_tenant_id', null);
        $logSpy = Log::spy();
        $exception = null;

        try {
            TenantAwareModel::query()->get();
            $this->fail('Expected BeeHiveException to be thrown.');
        } catch (BeeHiveException $caught) {
            $exception = $caught;
        }

        $this->assertInstanceOf(BeeHiveException::class, $exception);

        $logSpy->shouldHaveReceived('log')
            /** @phpstan-ignore-next-line */
            ->once()
            ->withArgs(function (string $level, string $message, array $context): bool {
                return $level === 'warning'
                    && $message === 'BeeHive tenant was not resolved for a tenant-scoped query.'
                    && ($context['event_code'] ?? null) === 'BEEHIVE_TENANT_UNRESOLVED_QUERY';
            });
    }

    public function testItEnforcesContextTenantOnCreate(): void
    {
        Config::set('bee-hive.static_tenant_id', 10);
        $logSpy = Log::spy();

        $model = TenantAwareModel::query()->create([
            'name' => 'spoof-attempt',
            'id_tenant' => 99,
        ]);

        $this->assertSame('10', (string) $model->getAttribute('id_tenant'));

        $logSpy->shouldHaveReceived('log')
            /** @phpstan-ignore-next-line */
            ->once()
            ->withArgs(function (string $level, string $message, array $context): bool {
                return $level === 'warning'
                    && $message === 'BeeHive detected and neutralized a tenant spoofing attempt.'
                    && ($context['event_code'] ?? null) === 'BEEHIVE_TENANT_SPOOF_ATTEMPT';
            });
    }

    public function testItHonorsCustomTenantKeyModel(): void
    {
        Config::set('bee-hive.static_tenant_id', 77);

        CustomTenantKeyModel::query()->withoutGlobalScopes()->insert([
            ['name' => 'tenant-77', 'tenant_id' => 77],
            ['name' => 'tenant-78', 'tenant_id' => 78],
        ]);

        $rows = CustomTenantKeyModel::query()->pluck('name')->all();

        $this->assertSame(['tenant-77'], $rows);

        $created = CustomTenantKeyModel::query()->create([
            'name' => 'custom-key-create',
            'tenant_id' => 999,
        ]);

        $this->assertSame('77', (string) $created->getAttribute('tenant_id'));
    }
}
