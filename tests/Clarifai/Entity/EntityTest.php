<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Entity;

abstract class EntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider setterGetterProvider
     *
     * @param string $method Entity method
     * @param array $mockData Data needed for target class creation
     * @param bool $isArray Is current method return an array?
     */
    public function testGettersAndSetters($method, $mockData, $isArray = false)
    {
        if ($isArray) {
            $this->assertEmpty($this->entity->{'get'.$method}());
        } else {
            $this->assertNull($this->entity->{'get'.$method}());
        }

        $this->assertSame(
            $this->entity,
            $this->entity->{'set' . $method}($mockData)
        );
        $this->assertEquals(
            $mockData,
            $this->entity->{'get' . $method}()
        );
    }
}
