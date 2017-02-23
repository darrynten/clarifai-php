<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Input;


class InputTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $image = 'image';
        $image2 = 'image2';
        $input = new Input($image);

        $this->assertEquals($image, $input->getImage());
        $this->assertEquals(Input::IMG_URL, $input->getImageMethod());

        $input->setImage($image2);
        $this->assertEquals($image2, $input->getImage());

        $input->setImageMethod(Input::IMG_PATH);
        $this->assertEquals(Input::IMG_PATH, $input->getImageMethod());

        $input = new Input($image, Input::IMG_PATH);
        $this->assertEquals(Input::IMG_PATH, $input->getImageMethod());

        $input = new Input($image, Input::IMG_URL);
        $this->assertEquals(Input::IMG_URL, $input->getImageMethod());

        $input = new Input($image, Input::IMG_BASE64);
        $this->assertEquals(Input::IMG_BASE64, $input->getImageMethod());
    }
}
