<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\ClarifaiApiException;
use PHPUnit_Framework_TestCase;

class ClarifaiApiExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testApiException()
    {
        $this->expectException(ClarifaiApiException::class);

        $clarifai = new Clarifai('', '', '');

        $clarifai->request('GET', 'ht://d/d', ['foo' => 'bar']);
    }

    public function testApiPostException()
    {
        $this->expectException(ClarifaiApiException::class);

        $clarifai = new Clarifai('', '', '');

        $clarifai->request('POST', 'ht://d/d', ['foo' => 'bar']);
    }

    public function testApiJsonException()
    {
        $this->expectException(ClarifaiApiException::class);

        throw new ClarifaiApiException(
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
