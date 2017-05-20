<?php

namespace Yapstone\Exceptions;

abstract class BaseException extends \Exception
{
    const ERROR_TITLE = '[Yapstone] ';

    public function __construct($message = 'An unexpected error has occurred')
    {
        parent::__construct(self::ERROR_TITLE . $message);
    }
}
