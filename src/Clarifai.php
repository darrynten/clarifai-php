<?php

namespace DarrynTen\Clarifai;

use DarrynTen\Clarifai\ClarifaiApiException;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


/**
 * Clarifai Library
 *
 * @package Clarifai
 */
class Clarifai {

  /**
   * GuzzleHttp Client
   *
   * @var Client $client
   */
  protected $client;

  /**
   * The Clarifai endpoint
   *
   * @var string $endpoint
   */
  protected $endpoint = '';

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
   * @var string $bearerToken
   */
  private $bearerToken;

  /**
   * Clarifai constructor
   *
   * @param string $clientId
   *   The client ID
   * @param string $clientSecret
   *   The client secret
   */
  public function __construct($clientId, $clientSecret, $bearerToken) {
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
   * @param string $method
   *   The API method
   * @param string $path
   *   The path
   * @param array $parameters
   *   The request parameters
   *
   * @return object
   *
   * @throws ClarifaiApiException
   */
  public function request(String $method, String $path, Array $parameters = []) {

    // TODO will change when oauth is implemented
    $options = [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->bearerToken
      ]
    ];

    // TODO check for batch operation

    return $this->handleRequest($method, $this->endpoint . $path, $options, $parameters);
  }

  /**
   * Makes a request using Guzzle
   *
   * @see Clarifai::request()
   */
  public function handleRequest(String $method, String $uri = '', Array $options = [], Array $parameters = []) {
    // Are we going a GET or a POST?
    if (!empty($parameters)) {
      if ($method === 'GET') {
        // Send as get params
        $options['query'] = $parameters;
      } else {
        // Otherwise send JSON in the body
        $options['json'] = (object) $parameters;
      }
    }

    // Let's go
    try {
      $response = $this->client->request($method, $uri, $options);
      $data = json_decode($response->getBody());

      // All good
      return $data;
    } catch (RequestException $exception) {
      $message = $exception->getMessage();

      throw new ClarifaiApiException($message, $exception->getCode(), $exception);
    }
  }
 }
