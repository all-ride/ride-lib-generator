<?php

namespace ride\library\generator;

use \PHPUnit_Framework_TestCase;

class CodeTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider providerResolveClassName
     */
    public function testResolveClassName($className, $namespace, $name) {
        $resultingNamespace = null;
        $resultingName = null;

        Code::resolveClassName($className, $resultingNamespace, $resultingName);

        $this->assertEquals($namespace, $resultingNamespace);
        $this->assertEquals($name, $resultingName);
    }

    public function providerResolveClassName() {
        return array(
            array('MyClass', null, 'MyClass'),
            array('ride\\library\\StringHelper', 'ride\\library', 'StringHelper'),
            array('\\ride\\library\\StringHelper', '\\ride\\library', 'StringHelper'),
        );
    }

    /**
     * @dataProvider providerUndefinableType
     */
    public function testUndefinableType($expected, $type) {
        $this->assertEquals($expected, Code::isUndefinableType($type));
    }

    public function providerUndefinableType() {
        return array(
            array(true, 'bool'),
            array(true, 'boolean'),
            array(true, 'int'),
            array(true, 'integer'),
            array(true, 'double'),
            array(true, 'float'),
            array(true, 'string'),
            array(true, 'datetime'),
            array(true, 'time'),
            array(true, 'mixed'),
            array(false, 'any'),
            array(false, array()),
            array(false, $this),
        );
    }

    /**
     * @dataProvider providerIsValidType
     */
    public function testIsValidType($expected, $type) {
        $this->assertEquals($expected, Code::isValidType($type));
    }

    public function providerIsValidType() {
        return array(
            array(true, 'variable'),
            array(true, 'string|array'),
            array(true, 'vendor\\MyClass'),
            array(false, '1noNumberToStart'),
            array(false, 'testé"(§è!'),
            array(false, ''),
            array(false, null),
            array(false, false),
            array(false, array()),
            array(false, $this),
        );
    }

    /**
     * @dataProvider providerIsValidName
     */
    public function testIsValidName($expected, $name, $isClassAllowed) {
        $this->assertEquals($expected, Code::isValidName($name, $isClassAllowed));
    }

    public function providerIsValidName() {
        return array(
            array(true, 'variable', false),
            array(true, 'vendor\\MyClass', true),
            array(false, 'vendor\\MyClass', false),
            array(false, '1noNumberToStart', false),
            array(false, 'testé"(§è!', false),
            array(false, '', false),
            array(false, null, false),
            array(false, false, false),
            array(false, array(), false),
            array(false, $this, false),
        );
    }

    /**
     * @dataProvider providerIsValidScope
     */
    public function testIsValidScope($expected, $scope) {
        $this->assertEquals($expected, Code::isValidScope($scope));
    }

    public function providerIsValidScope() {
        return array(
            array(true, 'private'),
            array(true, 'protected'),
            array(true, 'public'),
            array(false, 'any'),
            array(false, array()),
            array(false, $this),
        );
    }

}
