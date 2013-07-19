<?php
namespace DirectCall;

use Guzzle\Http\Client;
use DirectCall\Exception;

/**
 * Class DirectCall
 * @package DirectCall
 * @author Renato Neto
 */
class DirectCall
{

    protected $clienteId;
    protected $clientSecret;
    protected $accessToken;

    protected $apiUrl = 'https://api.directcallsoft.com';
    protected $format = 'json';

    /**
     * @var Client $httpClient
     */
    protected $httpClient;

    public function __construct($clientId, $clientSecret, $accessToken = false)
    {
        $this->setClienteId($clientId)
            ->setClientSecret($clientSecret);

        if ($accessToken) {
            $this->setAccessToken($accessToken);
        }

        $this->setHttpClient(new Client($this->getApiUrl()));
    }

    /**
     * @param $apiUrl
     * @return $this
     * @throws Exception
     */
    public function setApiUrl($apiUrl)
    {
        if (!filter_var($apiUrl, FILTER_VALIDATE_URL)) {
            throw new Exception('URL inválida');
        }

        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param $accessToken
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param $clientSecret
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param $clienteId
     * @return $this
     */
    public function setClienteId($clienteId)
    {
        $this->clienteId = $clienteId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * @param Client $httpClient
     * @return $this
     */
    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function authenticate()
    {
        try {

            $request = $this->httpClient->post('/request_token', array(), array(
                'client_id'     => $this->getClienteId(),
                'client_secret' => $this->getClientSecret(),
                'format'        => $this->getFormat(),
            ));

            $response = $request->send()->json();

            if (!$response || empty($response['access_token'])) {
                throw new Exception('Não foi possível recuperar o access token');
            }

            $this->setAccessToken($response['access_token']);
            return $response['access_token'];

        } catch (\Exception $e) {
            throw $e;
        }
    }

}