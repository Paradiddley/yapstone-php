<?php

namespace Yapstone\Endpoints;

use Yapstone\Resource;
use Yapstone\YapstoneObject;

class CreditCardPayment extends Resource
{
    /** @var array $requiredFields */
    protected static $requiredFields = [
        'number',
        'expiration',
        'cardholder',
        'type',
        'street',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'email',
        'amount|amounts',
        'id',
        'personFirstname',
        'personLastname'
    ];

    /**
     * @param null $params
     * @return YapstoneObject
     */
    public static function submit($params = null)
    {
        return self::makeRequest($params);
    }
}
