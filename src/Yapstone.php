<?php

namespace Yapstone;

class Yapstone
{
    /** @var string $accountCode */
    public static $accountCode = null;

    /** @var string $apiUrl */
    public static $apiUrl = null;

    /** @var Logger $logger */
    public static $logger = null;

    /** @var string $username */
    public static $username = null;

    /** @var string $password */
    public static $password = null;

    /**
     * @param array $credentials
     */
    public static function setCredentials($credentials)
    {
        self::$username    = $credentials['username'];
        self::$password    = $credentials['password'];
        self::$apiUrl      = $credentials['api_url'];
        self::$accountCode = $credentials['acc_code'];
    }

    /**
     * @return string
     */
    public static function getAccountCode()
    {
        return self::$accountCode;
    }

    /**
     * @param string $accountCode
     */
    public static function setAccountCode($accountCode)
    {
        self::$accountCode = $accountCode;
    }

    /**
     * @return string
     */
    public static function getApiUrl()
    {
        return self::$apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public static function setApiUrl($apiUrl)
    {
        self::$apiUrl = $apiUrl;
    }

    /**
     * @return Logger
     */
    public static function getLogger()
    {
        return self::$logger;
    }

    /**
     * @param Logger $logger
     */
    public static function setLogger(Logger $logger)
    {
        self::$logger = $logger;
    }

    /**
     * @return string
     */
    public static function getUsername()
    {
        return self::$username;
    }

    /**
     * @param string $username
     */
    public static function setUsername($username)
    {
        self::$username = $username;
    }

    /**
     * @return string
     */
    public static function getPassword()
    {
        return self::$password;
    }

    /**
     * @param string $password
     */
    public static function setPassword($password)
    {
        self::$password = $password;
    }
}
