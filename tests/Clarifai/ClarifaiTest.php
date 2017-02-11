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
        $clarifai = new Clarifai('', '', '');
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

        $clarifai = new Clarifai('', '', '');

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

        $clarifai = new Clarifai('', '', '');

        $this->assertEquals(
            json_decode('{ body: { code: 1 } }'),
            $clarifai->handleRequest('GET', 'http://localhost:8082/foo', [], [])
        );
    }
}
