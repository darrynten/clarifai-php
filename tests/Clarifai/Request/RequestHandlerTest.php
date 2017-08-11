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

        $clarifai = new RequestHandler('');
        $clarifai->handleRequest('GET', 'http://localhost:8082/foo', []);
    }
}
