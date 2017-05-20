<?php

namespace Yapstone;

abstract class Resource implements ResourceInterface
{
    /** @var array $requiredFields */
    protected static $requiredFields = [];

    /**
     * @param null $params
     * @return YapstoneObject
     */
    protected static function makeRequest($params = null)
    {
        self::validateParams($params);
        self::checkRequiredFields($params);

        $name = self::className();

        $request = new Request();
        $response = $request->send($name, $params);

        return $response;
    }

    /**
     * @return string
     */
    private static function className()
    {
        $class = get_called_class();

        if ($postfixNamespaces = strrchr($class, '\\')) {
            $class = substr($postfixNamespaces, 1);
        }

        return $class;
    }

    /**
     * @param array $params
     * @throws Exceptions\InvalidParameters
     */
    private static function validateParams($params)
    {
        if ($params && !is_array($params)) {
            throw new Exceptions\InvalidParameters('The parameters passed should be an array');
        }
    }

    /**
     * @param array $params
     * @throws Exceptions\InvalidParameters
     */
    private static function checkRequiredFields($params)
    {
        $missing = [];

        foreach (static::$requiredFields as $requiredField) {
            if (strpos($requiredField, '|')) {
                $orFields = explode('|', $requiredField);
                $orMissing = [];
                foreach ($orFields as $orField) {
                    if (!array_key_exists($orField, $params)) {
                        $orMissing[] = $orField;
                    }
                }
                if (count($orMissing) > 1) {
                    $missing[] = $requiredField;
                }
            } else {
                if (!array_key_exists($requiredField, $params)) {
                    $missing[] = $requiredField;
                }
            }
        }

        if (!empty($missing)) {
            $fields = implode(', ', $missing);
            throw new Exceptions\InvalidParameters('Missing required fields; ' . $fields);
        }
    }
}