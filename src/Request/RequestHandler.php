<?php
/**
 * Clarifai Library
 *
 * @category Library
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Request;

use DarrynTen\Clarifai\Exception\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * RequestHandler Class
 *
 * @category Library
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */
class RequestHandler
{
    /**
     * GuzzleHttp Client
     *
     * @var Client $client
     */
    private $client;

    /**
     * The Clarifai url
     *
     * @var string $url
     */
    private $url = 'https://api.clarifai.com';

    /**
     * The version of the Clarifai API
     *
     * @var string $version
     */
    private $version = 'v2';

    /**
     * Clarifai Client ID
     *
     * @var string $clientId
     */
    private $clientId;

    /**
     * The time until which token is valid
     *
     * @var \DateTime
     */
    private $tokenExpireTime;

    /**
     * Clarifai Client Secret
     *
     * @var string $clientSecret
     */
    private $clientSecret;

    /**
     * Clarifai Token
     *
     * @var string $token
     */
    private $token;

    /**
     * Clarifai Token type
     *
     * @var string $token
     */
    private $tokenType;


    /**
     * Request handler constructor
     *
     * @param string $clientId The client ID
     * @param string $clientSecret The client secret
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenExpireTime = new \DateTime();
        $this->client = new Client();
    }

    /**
     * Makes a request using Guzzle
     *
     * @param string $method The HTTP request method (GET/POST/etc)
     * @param string $uri The resource
     * @param array $options Request options
     * @param array $parameters Request parameters
     *
     * @see RequestHandler::request()
     *
     * @return array
     * @throws ApiException
     */
    public function handleRequest(string $method, string $uri = '', array $options = [], array $parameters = [])
    {
        // Are we going a GET or a POST?
        if (!empty($parameters)) {
            if ($method === 'GET') {
                // Send as get params
                $options['query'] = $parameters;
            } else {
                // Otherwise send JSON in the body
                $options['json'] = (object)$parameters;
            }
        }

        // Let's go
        try {
            $response = $this->client->request($method, $uri, $options);

            // All good
            return json_decode($response->getBody(), true);
        } catch (RequestException $exception) {
            $message = $exception->getMessage();

            throw new ApiException($message, $exception->getCode(), $exception);
        }
    }

    /**
     * Get token for Clarifai API requests
     *
     * @return string
     */
    private function getAuthToken()
    {
        // Generate a new token if current is expired or empty
        if (!$this->token || new \DateTime() > $this->tokenExpireTime) {
            $this->requestToken();
        }

        return $this->tokenType . ' ' . $this->token;
    }

    /**
     * Make request to Clarifai API for the new token
     */
    private function requestToken()
    {
        $tokenResponse = $this->handleRequest(
            'POST',
            $this->url . '/v1/token', // endpoint is available only in v1
            [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
            ]
        );

        $this->tokenExpireTime->modify(
            sprintf('+%s seconds', $tokenResponse['expires_in'])
        );
        $this->token = $tokenResponse['access_token'];
        $this->tokenType = $tokenResponse['token_type'];
    }

    /**
     * Makes a request to Clarifai
     *
     * @param string $method The API method
     * @param string $path The path
     * @param array $parameters The request parameters
     *
     * @return []
     *
     * @throws ApiException
     */
    public function request(string $method, string $path, array $parameters = [])
    {
        $options = [
            'headers' => [
                'Authorization' => $this->getAuthToken(),
            ],
        ];

        // TODO check for batch operation
        return $this->handleRequest(
            $method,
            sprintf('%s/%s/%s', $this->url, $this->version, $path),
            $options,
            $parameters
        );
    }
}
