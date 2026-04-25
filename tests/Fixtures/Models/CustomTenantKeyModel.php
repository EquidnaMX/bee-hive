<?php

namespace Equidna\BeeHive\Tests\Fixtures\Models;

use Equidna\BeeHive\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class CustomTenantKeyModel extends Model
{
    use BelongsToTenant;

    protected $table = 'custom_tenant_models';

    protected $guarded = [];

    protected string $tenantKey = 'tenant_id';

    public $timestamps = false;
}
