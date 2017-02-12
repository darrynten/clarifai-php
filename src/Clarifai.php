<?php
/**
 * Clarifai Library
 *
 * @category Library
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Clarifai Class
 *
 * @category Library
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */
class Clarifai
{
    /**
     * GuzzleHttp Client
     *
     * @var Client $client
     */
    protected $client;

    /**
     * The Clarifai url
     *
     * @var string $url
     */
    protected $url = 'https://api.clarifai.com';

    /**
     * The version of the Clarifai API
     *
     * @var string $version
     */
    protected $version = 'v2';

    /**
     * Clarifai Client ID
     *
     * TODO using bearer token for now
     *
     * @var string $clientId
     */
    private $clientId;

    /**
     * Clarifai Client Secret
     *
     * TODO using bearer token for now
     *
     * @var string $clientSecret
     */
    private $clientSecret;

    /**
     * Clarifai Bearer Token
     *
     * TODO using this for now
     *
     * @var string $bearerToken
     */
    private $bearerToken;

    /**
     * Clarifai constructor
     *
     * @param string $clientId The client ID
     * @param string $clientSecret The client secret
     * @param string $bearerToken The bearer token
     */
    public function __construct($clientId, $clientSecret, $bearerToken)
    {
        // TODO oauth
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        // Temporary
        $this->bearerToken = $bearerToken;

        $this->client = new Client();
    }

    /**
     * Makes a request to Clarifai
     *
     * @param string $method The API method
     * @param string $path The path
     * @param array $parameters The request parameters
     *
     * @return object
     *
     * @throws ClarifaiApiException
     */
    public function request(string $method, string $path, array $parameters = [])
    {
        // TODO will change when oauth is implemented
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->bearerToken,
            ],
        ];

        // TODO check for batch operation

        return $this->handleRequest($method, $this->url.$path, $options, $parameters);
    }

    /**
     * Makes a request using Guzzle
     *
     * @param string $method The HTTP request method (GET/POST/etc)
     * @param string $uri The resource
     * @param array $options Request options
     * @param array $parameters Request parameters
     *
     * @see Clarifai::request()
     *
     * @return []
     * @throws ClarifaiApiException
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
            return json_decode($response->getBody());
        } catch (RequestException $exception) {
            $message = $exception->getMessage();

            throw new ClarifaiApiException($message, $exception->getCode(), $exception);
        }
    }
}
