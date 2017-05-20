<?php

namespace Yapstone\Utility;

/**
 * A nested array to XML parser.
 *
 * Class ArrayToXML
 * @package Yapstone\Utility
 */
class ArrayToXML
{
    const KEY_ATTRIBUTE = '@attributes';
    const KEY_VALUE = '@value';

    private $xml;
    private $encoding = null;

    /**
     * ArrayToXML constructor
     *
     * @param string $version
     * @param string $encoding
     * @param bool $formatOut
     */
    public function __construct($version = '1.0', $encoding = 'UTF-8', $formatOut = false)
    {
        $this->xml = new \DOMDocument($version, $encoding);
        $this->xml->formatOutput = $formatOut;
        $this->encoding = $encoding;
    }

    /**
     * Initiate translation.
     *
     * @param string $name
     * @param array $array
     * @return \DOMDocument
     */
    public function toXml($name, $array = [])
    {
        $this->xml->appendChild($this->translate($name, $array));
        return $this->xml;
    }

    /**
     * Assigns elements to the DOMDocument object from a
     * nested array using recursion.
     *
     * @param string $name name of the current node.
     * @param array|string $array Accepts multidimensional arrays
     * with attributes and corresponding values.
     * @throws \Exception
     * @return \DOMElement
     */
    public function translate($name, $array = [])
    {
        $xml = $this->xml;
        $node = $xml->createElement($name);

        if (is_array($array)) {
            if (isset($array[self::KEY_ATTRIBUTE])) {
                foreach ($array[self::KEY_ATTRIBUTE] as $attrKey => $attrVal) {
                    self::isKeyValid($attrKey);
                    $node->setAttribute($attrKey, self::strVal($attrVal));
                }
                unset($array[self::KEY_ATTRIBUTE]);
            }
            if (isset($array[self::KEY_VALUE])) {
                $node->appendChild($xml->createTextNode(self::strVal($array[self::KEY_VALUE])));
                unset($array[self::KEY_VALUE]);
                return $node;
            }
        }

        if (is_array($array)) {
            foreach ($array as $key => $val) {
                self::isKeyValid($key);
                if (is_array($val) && is_numeric(key($val))) {
                    foreach ($val as $subKey => $subVal) {
                        $node->appendChild($this->translate($key, self::strVal($subVal)));
                    }
                } else {
                    $node->appendChild($this->translate($key, self::strVal($val)));
                }
                unset($array[$key]);
            }
        }

        if (!is_array($array)) {
            $node->appendChild($xml->createTextNode(self::strVal($array)));
        }

        return $node;
    }

    /**
     * Convert value to a string representation.
     *
     * @param string|bool $val
     * @return string
     */
    private static function strVal($val)
    {
        if (is_bool($val)) {
            return ($val) ? 'true' : 'false';
        } else {
            return $val;
        }
    }

    /**
     * Validate the tag/attribute name meets the required
     * constructs. https://www.w3.org/TR/xml/#sec-common-syn
     *
     * @param string $key
     * @throws \Exception
     * @return bool
     */
    private static function isKeyValid($key)
    {
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';
        preg_match($pattern, $key, $matches);
        if (empty($matches)) {
            throw new \Exception("[ArrayToXML] $key is not a valid tag/attribute name");
        } else {
            return true;
        }
    }
}
