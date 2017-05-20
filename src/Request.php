<?php

namespace Yapstone;

class Request
{
    /** @var null|string $url */
    private $url = null;

    /** @var null|string $username */
    private $username = null;

    /** @var null|string $password */
    private $password = null;

    /** @var null|string $account */
    private $account = null;

    /**
     * Request constructor
     */
    public function __construct()
    {
        $this->establish();
    }

    /**
     * @param string $resourceName
     * @param array $resourceParams
     * @throws Exceptions\FailedRequest
     * @return YapstoneObject
     */
    public function send($resourceName, $resourceParams)
    {
        $data = $this->includeYapstoneUserCredentials($resourceParams);
        $xml = $this->resourceToXmlString($resourceName, $data);

        list($resBody, $resCode) = self::post($this->url, $xml);

        if ($resCode < 200 || $resCode >= 300) {
            throw new Exceptions\FailedRequest('Request failed with status code ' . $resCode);
        }

        $object = new YapstoneObject($resBody, $resCode);

        self::checkResponseForError($object);

        return $object;
    }

    /**
     * @param string $url
     * @param string $xml
     * @return mixed
     */
    private static function post($url, $xml)
    {
        $headers = [
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"run\""
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        if ($body === false) {
            $errCode = curl_errno($ch);
            $errMsg = curl_error($ch);
            curl_close($ch);
            self::curlError($errCode, $errMsg);
        }

        curl_close($ch);

        return [$body, $code];
    }

    /**
     * @param integer $code
     * @param string $msg
     * @throws Exceptions\FailedRequest
     */
    private static function curlError($code, $msg)
    {
        throw new Exceptions\FailedRequest("cURL error $code: $msg");
    }

    /**
     * @param string $rootName
     * @param array $params
     * @return string
     */
    private function resourceToXmlString($rootName, $params)
    {
        $xmlParser = new Utility\ArrayToXML();
        $xml = $xmlParser->toXml($rootName, $params);
        $str = $xml->saveXML();

        self::validateXmlString($str);

        return $str;
    }

    /**
     * @param string $xml
     * @throws Exceptions\MalformedData
     */
    private static function validateXmlString($xml)
    {
        libxml_use_internal_errors(true);
        $sxe = simplexml_load_string($xml);
        if (!$sxe) {
            throw new Exceptions\MalformedData('Malformed XML string');
        }
    }

    /**
     * @param YapstoneObject $obj
     * @throws Exceptions\FailedRequest
     */
    private static function checkResponseForError(YapstoneObject $obj)
    {
        if ($obj->getName() === 'Error') {
            $error = $obj->getContent();
            throw new Exceptions\FailedRequest("({$error['code']}) {$error['description']}");
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function includeYapstoneUserCredentials($data)
    {
        return array_merge([
            'username'     => $this->username,
            'password'     => $this->password,
            'propertyCode' => $this->account
        ], $data);
    }

    /**
     * @throws Exceptions\MissingRequiredCredentials
     */
    private function establish()
    {
        $this->url      = Yapstone::getApiUrl();
        $this->username = Yapstone::getUsername();
        $this->password = Yapstone::getPassword();
        $this->account  = Yapstone::getAccountCode();

        if (!$this->url || !$this->username || !$this->password || !$this->account) {
            throw new Exceptions\MissingRequiredCredentials('Credentials missing');
        }
    }
}
