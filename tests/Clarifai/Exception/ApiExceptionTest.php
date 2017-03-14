<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\Request\RequestHandler;
use DarrynTen\Clarifai\Exception\ApiException;

class ApiExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testApiException()
    {
        $this->expectException(ApiException::class);

        $clarifai = new RequestHandler('', '');

        $clarifai->request('GET', 'ht://d/d', ['foo' => 'bar']);
    }

    public function testApiPostException()
    {
        $this->expectException(ApiException::class);

        $clarifai = new RequestHandler('', '');

        $clarifai->request('POST', 'ht://d/d', ['foo' => 'bar']);
    }

    public function testApiJsonException()
    {
        $this->expectException(ApiException::class);

        throw new ApiException(
            json_encode(
                [
                    'errors' => [
                        'code' => 1,
                    ],
                    'status' => '404',
                    'title' => 'Not Found',
                    'detail' => 'Detail'
                ]
            )
        );
    }

    // test requests
}
