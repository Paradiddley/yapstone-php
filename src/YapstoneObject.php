<?php

namespace Yapstone;

class YapstoneObject
{
    /** @var string $name */
    private $name;

    /** @var array $content */
    private $content;

    /** @var integer $code */
    private $code;

    /**
     * YapstoneObject constructor
     * @param string $data
     * @param integer $code
     */
    public function __construct($data, $code)
    {
        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);

        $this->name = $xml->getName();
        $this->content = json_decode(json_encode($xml), true);
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [$this->name => $this->content];
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
