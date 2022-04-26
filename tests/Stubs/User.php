<?php

namespace Deadan\QuickBooks\Stubs;

use Deadan\QuickBooks\HasQuickBooksToken;

/**
 * Class User
 *
 * Stub for a Laravel User model
 *
 * @package Deadan\QuickBooks\Stubs
 */
class User
{
    use HasQuickBooksToken;

    public function hasOne($relationship)
    {
        return $relationship;
    }
}
