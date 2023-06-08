<?php

namespace julio101290\Auth\Authorization;

use julio101290\Auth\Models\PermissionModel as BaseModel;

/**
 * @deprecated 1.2.0 Use Myth\Auth\Models\PermissionModel instead
 */
class PermissionModel extends BaseModel
{
    protected $returnType = 'array';
}
