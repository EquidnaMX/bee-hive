<?php

namespace Equidna\BeeHive\Tests\Fixtures\Models;

use Equidna\BeeHive\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TenantAwareModel extends Model
{
    use BelongsToTenant;

    protected $table = 'tenant_models';

    protected $guarded = [];

    public $timestamps = false;
}
