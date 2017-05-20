<?php

namespace Tests\Utility;

use Yapstone\Resource;

class ResourceTester extends Resource
{
    protected static $requiredFields = [];

    public static function submit($params = null)
    {
        return self::makeRequest($params);
    }
}
