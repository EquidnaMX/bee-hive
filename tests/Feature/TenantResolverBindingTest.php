<?php

namespace Equidna\BeeHive\Tests\Feature;

use Equidna\BeeHive\Contracts\TenantResolverInterface;
use Equidna\BeeHive\Tenancy\TenantContext;
use Equidna\BeeHive\Tenancy\Resolvers\StaticTenantResolver;
use Equidna\BeeHive\Tests\TestCase;
use InvalidArgumentException;

class TenantResolverBindingTest extends TestCase
{
    public function testItThrowsWhenResolverDoesNotImplementContract(): void
    {
        config(['bee-hive.resolver' => \stdClass::class]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Configured BeeHive resolver must implement TenantResolverInterface.');

        app()->make(TenantResolverInterface::class);
    }

    public function testScopedTenantContextRefreshesBetweenScopes(): void
    {
        config(['bee-hive.static_tenant_id' => 10]);

        $firstContext = app(TenantContext::class);
        $this->assertSame('10', $firstContext->get());

        config(['bee-hive.static_tenant_id' => 11]);
        app()->forgetScopedInstances();

        $secondContext = app(TenantContext::class);
        $this->assertSame('11', $secondContext->get());
    }

    public function testStaticResolverReturnsStringTenantContract(): void
    {
        config(['bee-hive.static_tenant_id' => 55]);

        $resolver = app(StaticTenantResolver::class);

        $this->assertSame('55', $resolver->resolveTenantId());
    }

    public function testTenantContextStoresTenantAsStringContract(): void
    {
        config(['bee-hive.static_tenant_id' => 42]);

        $context = app(TenantContext::class);

        $this->assertSame('42', $context->get());
    }
}
