<?php

namespace ride\library\generator;

use \PHPUnit_Framework_TestCase;

class GenericCodeVariableTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider providerConstruct
     */
    public function testConstruct($name, $type) {
        $codeVariable = new GenericCodeVariable($name, $type);

        $this->assertEquals($name, $codeVariable->getName());
        $this->assertEquals($type, $codeVariable->getType());
        $this->assertNull($codeVariable->getDescription());
        $this->assertNull($codeVariable->getDefaultValue());
        $this->assertFalse($codeVariable->hasDefaultValue());
    }

    public function providerConstruct() {
        return array(
            array('name', 'type'),
            array('value', 'string|array'),
        );
    }

    public function testSetDefaultValue() {
        $name = 'className';
        $type = 'string';
        $value = 'default';

        $codeVariable = new GenericCodeVariable($name, $type);

        $this->assertFalse($codeVariable->hasDefaultValue());
        $this->assertNull($codeVariable->getDefaultValue());

        $codeVariable->setDefaultValue($value);

        $this->assertTrue($codeVariable->hasDefaultValue());
        $this->assertEquals($value, $codeVariable->getDefaultValue());
    }

    /**
     * @dataProvider providerSetNameThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetNameThrowsExceptionOnInvalidValue($name) {
        new GenericCodeVariable($name, 'string');
    }


    public function providerSetNameThrowsExceptionOnInvalidValue() {
        return array(
            array('vendor\\MyClass'),
            array('1noNumberToStart'),
            array('testé"(§è!'),
            array(''),
            array(null),
            array(false),
            array(array()),
            array($this),
        );
    }

    /**
     * @dataProvider providerSetTypeThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetTypeThrowsExceptionOnInvalidValue($type) {
        new GenericCodeVariable('name', $type);
    }

    public function providerSetTypeThrowsExceptionOnInvalidValue() {
        return array(
            array('1noNumberToStart'),
            array('testé"(§è!'),
            array(''),
            array(null),
            array(false),
            array(array()),
            array($this),
        );
    }

    /**
     * @dataProvider providerSetDescriptionThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetDescriptionThrowsExceptionOnInvalidValue($description) {
        $codeVariable = new GenericCodeVariable('name', 'string');
        $codeVariable->setDescription($description);
    }

    public function providerSetDescriptionThrowsExceptionOnInvalidValue() {
        return array(
            array(array()),
            array($this),
        );
    }

}
