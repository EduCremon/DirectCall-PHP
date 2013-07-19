<?php
namespace DirectCall\Module;

use DirectCall\DirectCall;
use DirectCall\Error;
use DirectCall\Exception;

/**
 * Class AbstractModule
 * @package DirectCall\Module
 * @author Renato Neto
 */
abstract class AbstractModule
{

    /**
     * @var \DirectCall\DirectCall
     */
    protected $directCall;

    public function __construct(DirectCall $directCall)
    {
        $this->setDirectCall($directCall);
    }

    /**
     * @param DirectCall $directCall
     * @return $this
     */
    public function setDirectCall(DirectCall $directCall)
    {
        $this->directCall = $directCall;
        return $this;
    }

    /**
     * @return DirectCall
     */
    public function getDirectCall()
    {
        return $this->directCall;
    }

    protected function makeRequest($path, array $body = array(), $method = 'POST', array $files = null)
    {
        $directCall = $this->getDirectCall();
        $client     = $directCall->getHttpClient();
        $method     = strtoupper($method);

        $body['access_token'] = $directCall->getAccessToken();
        $body['format']       = $directCall->getFormat();

        if (in_array($method, array('GET', 'HEAD')) && ($body && is_array($body))) {
            $path .= '?' . http_build_query($body);
        }

        $request = $client->createRequest($method, $path, null, $body);

        if ($method == 'POST') {
            $request->setHeader('Content-type', 'application/x-www-form-urlencoded');
        }

        $response = $request->send();
        $response = $response->json();

        if (!empty($response['error_description'])) {
            throw new Exception($response['error_description']);
        }

        if ($response['codigo'] == Error::OK) {
            return $response;
        }

        throw new Exception($response['msg'], $response['codigo']);
    }

}