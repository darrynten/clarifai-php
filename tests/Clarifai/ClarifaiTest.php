<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\Clarifai;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use PHPUnit_Framework_TestCase;

class ClarifaiTest extends PHPUnit_Framework_TestCase
{
    use HttpMockTrait;

    public static function setUpBeforeClass()
    {
        static::setUpHttpMockBeforeClass('8082', 'localhost');
    }

    public static function tearDownAfterClass()
    {
        static::tearDownHttpMockAfterClass();
    }

    public function setUp()
    {
        $this->setUpHttpMock();
    }

    public function tearDown()
    {
        $this->tearDownHttpMock();
    }

    public function testInstanceOf()
    {
        $clarifai = new Clarifai('', '');
        $this->assertInstanceOf(Clarifai::class, $clarifai);
    }

    public function testRequest()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/foo')
            ->then()
                ->body('{}')
            ->end();
        $this->http->setUp();

        $clarifai = new Clarifai('', '');

        $this->assertEquals(json_decode('{}'), $clarifai->handleRequest('GET', 'http://localhost:8082/foo', [], []));
    }

    public function testRequestEmptyResponse()
    {
        $this->http->mock
            ->when()
                ->methodIs('GET')
                ->pathIs('/foo')
            ->then()
                ->body('{ value: 1 }')
            ->end();
        $this->http->setUp();

        $clarifai = new Clarifai('', '');

        $this->assertEquals(
            json_decode('{ body: { code: 1 } }'),
            $clarifai->handleRequest('GET', 'http://localhost:8082/foo', [], [])
        );
    }

    public function testRequestWithToken()
    {
        $clientId = 'client_id';
        $clientSecret = 'client_secret';
        $method = 'method';
        $path = 'path';
        $token = 'token';
        $tokenType = 'token_type';
        $result = 'result';

        // Creates a partially mock of Clarifai with mocked `handleRequest` method
        $clarifai = \Mockery::mock(
            'DarrynTen\Clarifai\Clarifai[handleRequest]',
            [
                $clientId,
                $clientSecret
            ]
        );

        $clarifai->shouldReceive('handleRequest')
            ->once()
            ->with(
                'POST',
                'https://api.clarifai.com/v1/token',
                [
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                    ],
                ]
            )
            ->andReturn(
                (object) [
                    'expires_in' => '60',
                    'access_token' => $token,
                    'token_type' => $tokenType,
                ]
            )
        ;

        $clarifai->shouldReceive('handleRequest')
            ->twice()
            ->with(
                $method,
                'https://api.clarifai.com'.$path,
                [
                    'headers' => [
                        'Authorization' => $tokenType . ' ' . $token,
                    ]
                ],
                []
            )
            ->andReturn($result);

        // Only one token request should be made to Clarifai API
        $this->assertEquals(
            $result,
            $clarifai->request($method, $path)
        );

        $this->assertEquals(
            $result,
            $clarifai->request($method, $path)
        );
    }
}
