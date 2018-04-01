<?php

namespace ride\library\generator;

use PHPUnit\Framework\TestCase;

class GenericCodePropertyTest extends TestCase {

    public function testConstruct() {
        $name = 'className';
        $type = 'string';

        $codeProperty = new GenericCodeProperty($name, $type);

        $this->assertEquals($name, $codeProperty->getName());
        $this->assertEquals($type, $codeProperty->getType());
        $this->assertNull($codeProperty->getDescription());
        $this->assertNull($codeProperty->getDefaultValue());
        $this->assertFalse($codeProperty->hasDefaultValue());
        $this->assertEquals(Code::SCOPE_PUBLIC, $codeProperty->getScope());
    }

    /**
     * @dataProvider providerSetScopeThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetScopeThrowsExceptionOnInvalidValue($scope) {
        $codeProperty = new GenericCodeProperty('name', 'string');
        $codeProperty->setScope($scope);
    }

    public function providerSetScopeThrowsExceptionOnInvalidValue() {
        return array(
            array('test'),
            array(null),
            array(array()),
            array($this),
        );
    }

}
