<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Request;

use DarrynTen\Clarifai\Request\RequestHandler;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;

class RequestHandlerTest extends \PHPUnit_Framework_TestCase
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
        $clarifai = new RequestHandler('', '');
        $this->assertInstanceOf(RequestHandler::class, $clarifai);
    }

    public function testRequest()
    {
        $data = '{\'key\':\'data\'}';

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/foo')
            ->then()
            ->body($data)
            ->end();
        $this->http->setUp();

        $clarifai = new RequestHandler('', '');

        $this->assertEquals(
            json_decode($data),
            $clarifai->handleRequest('GET', 'http://localhost:8082/foo', [])
        );
    }

    public function testRequestWithQuery()
    {
        $parameters = ['data' => 'value'];
        $data = '{\'key\':\'data\'}';

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/foo?data=value')
            ->queryParamsAre($parameters)
            ->then()
            ->body($data)
            ->end();
        $this->http->setUp();

        $clarifai = new RequestHandler('', '');

        $this->assertEquals(
            json_decode($data),
            $clarifai->handleRequest('GET', 'http://localhost:8082/foo', [], $parameters)
        );
    }

    public function testRequestWithJson()
    {
        $parameters = ['data123' => 'value'];
        $data = '{\'key\':\'data\'}';

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/foo')
            ->then()
            ->body($data)
            ->end();
        $this->http->setUp();

        $clarifai = new RequestHandler('', '');

        $this->assertEquals(
            json_decode($data),
            $clarifai->handleRequest('POST', 'http://localhost:8082/foo', [], $parameters)
        );
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

        $clarifai = new RequestHandler('', '');

        $this->assertEquals(
            json_decode('{ body: { code: 1 } }'),
            $clarifai->handleRequest('GET', 'http://localhost:8082/foo', [])
        );
    }

    /**
     * @expectedException \DarrynTen\Clarifai\Exception\ApiException
     * @expectedExceptionMessage Server error: `GET http://localhost:8082/foo` resulted in a `500 Internal Server Error
     * @expectedExceptionCode 500
     */
    public function testRequestException()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/foo')
            ->then()
            ->statusCode(500)
            ->end();
        $this->http->setUp();

        $clarifai = new RequestHandler('', '');
        $clarifai->handleRequest('GET', 'http://localhost:8082/foo', []);
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

        // Creates a partially mock of RequestHandler with mocked `handleRequest` method
        $clarifai = \Mockery::mock(
            'DarrynTen\Clarifai\Request\RequestHandler[handleRequest]',
            [
                $clientId,
                $clientSecret,
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
                (array)[
                    'expires_in' => '60',
                    'access_token' => $token,
                    'token_type' => $tokenType,
                ]
            );

        $clarifai->shouldReceive('handleRequest')
            ->twice()
            ->with(
                $method,
                'https://api.clarifai.com/v2/'.$path,
                [
                    'headers' => [
                        'Authorization' => $tokenType . ' ' . $token,
                        'User-Agent' => 'Clarifai PHP (https://github.com/darrynten/clarifai-php);v0.9.3;' . phpversion(),
                    ],
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
