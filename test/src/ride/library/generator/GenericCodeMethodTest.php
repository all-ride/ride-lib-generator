<?php

namespace ride\library\generator;

use PHPUnit\Framework\TestCase;

class GenericCodeMethodTest extends TestCase {

    public function testMinimalConstruct() {
        $name = 'method';

        $codeMethod = new GenericCodeMethod($name);

        $this->assertEquals($name, $codeMethod->getName());
        $this->assertEquals(array(), $codeMethod->getArguments());
        $this->assertNull($codeMethod->getSource());
        $this->assertEquals(Code::SCOPE_PUBLIC, $codeMethod->getScope());
        $this->assertFalse($codeMethod->isAbstract());
        $this->assertFalse($codeMethod->isStatic());
        $this->assertNull($codeMethod->getDescription());
        $this->assertNull($codeMethod->getReturnValue());
        $this->assertEquals(array(), $codeMethod->getUse());
    }

    /**
     * @dataProvider providerAdvancedConstruct
     */
    public function testAdvancedConstruct($name, $arguments, $source, $scope, $isAbstract, $isStatic) {
        $codeMethod = new GenericCodeMethod($name, $arguments, $source, $scope, $isAbstract, $isStatic);

        $resultArguments = array();
        foreach ($arguments as $argument) {
            $resultArguments[$argument->getName()] = $argument;
        }

        $this->assertEquals($name, $codeMethod->getName());
        $this->assertEquals($resultArguments, $codeMethod->getArguments());
        $this->assertEquals($source, $codeMethod->getSource());
        $this->assertEquals($scope, $codeMethod->getScope());
        $this->assertEquals($isAbstract, $codeMethod->isAbstract());
        $this->assertEquals($isStatic, $codeMethod->isStatic());
    }

    public function providerAdvancedConstruct() {
        return array(
            array(
                'name',
                array(),
                'phpinfo();',
                'private',
                false,
                false,
            ),
            array(
                'name',
                array(
                    new GenericCodeVariable('name', 'type'),
                ),
                'phpinfo();',
                'public',
                true,
                true,
            ),
        );
    }

    /**
     * @dataProvider providerSetNameThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetNameThrowsExceptionOnInvalidValue($name) {
        new GenericCodeMethod($name);
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
     * @dataProvider providerSetDescriptionThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetDescriptionThrowsExceptionOnInvalidValue($description) {
        $codeMethod = new GenericCodeMethod('name');
        $codeMethod->setDescription($description);
    }

    public function providerSetDescriptionThrowsExceptionOnInvalidValue() {
        return array(
            array(array()),
            array($this),
        );
    }

    /**
     * @dataProvider providerSetScopeThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetScopeThrowsExceptionOnInvalidValue($scope) {
        new GenericCodeMethod('name', array(), null, $scope);
    }

    public function providerSetScopeThrowsExceptionOnInvalidValue() {
        return array(
            array('test'),
            array(array()),
            array($this),
        );
    }

    public function testArguments() {
        $codeMethod = new GenericCodeMethod('name');
        $argument = new GenericCodeVariable('name', 'string');
        $argument2 = new GenericCodeVariable('isOk', 'boolean');

        $this->assertEquals(array(), $codeMethod->getArguments());
        $this->assertFalse($codeMethod->hasArgument('name'));
        $this->assertNull($codeMethod->getArgument('name'));

        $codeMethod->addArgument($argument);

        $this->assertEquals(array('name' => $argument), $codeMethod->getArguments());
        $this->assertTrue($codeMethod->hasArgument('name'));
        $this->assertEquals($argument, $codeMethod->getArgument('name'));

        $this->assertFalse($codeMethod->removeArgument('test'));
        $this->assertTrue($codeMethod->removeArgument('name'));

        $this->assertEquals(array(), $codeMethod->getArguments());
        $this->assertFalse($codeMethod->hasArgument('name'));
        $this->assertNull($codeMethod->getArgument('name'));

        $codeMethod->setArguments(array($argument, $argument2));

        $this->assertEquals(array('name' => $argument, 'isOk' => $argument2), $codeMethod->getArguments());
        $this->assertTrue($codeMethod->hasArgument('name'));
        $this->assertEquals($argument, $codeMethod->getArgument('name'));
        $this->assertTrue($codeMethod->hasArgument('isOk'));
        $this->assertEquals($argument2, $codeMethod->getArgument('isOk'));
    }

    /**
     * @dataProvider providerSetArgumentsThrowsExceptionWhenInvalidArgumentProvided
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetArgumentsThrowsExceptionWhenInvalidArgumentProvided(array $arguments) {
        new GenericCodeMethod('name', $arguments);
    }

    public function providerSetArgumentsThrowsExceptionWhenInvalidArgumentProvided() {
        return array(
            array(array(null)),
            array(array(true)),
            array(array('string')),
            array(array(5)),
            array(array(array())),
            array(array($this)),
        );
    }

    /**
     * @dataProvider providerSetSourceThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetSourceThrowsExceptionOnInvalidValue($source) {
        new GenericCodeMethod('name', array(), $source);
    }

    public function providerSetSourceThrowsExceptionOnInvalidValue() {
        return array(
            array(array()),
            array($this),
        );
    }

    public function testUse() {
        $codeMethod = new GenericCodeMethod('name');

        $this->assertEquals(array(), $codeMethod->getUse());

        $codeMethod->addUse('MyClass');
        $codeMethod->addUse('vendor\\MyClass', 'VC');

        $expected = array(
            'MyClass' => null,
            'vendor\\MyClass' => 'VC',
        );

        $this->assertEquals($expected, $codeMethod->getUse());
    }

    /**
     * @dataProvider providerAddUseThrowsExceptionWhenInvalidArgumentProvided
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testAddUseThrowsExceptionWhenInvalidArgumentProvided($class, $alias) {
        $codeMethod = new GenericCodeMethod('name');
        $codeMethod->addUse($class, $alias);
    }

    public function providerAddUseThrowsExceptionWhenInvalidArgumentProvided() {
        return array(
            array('1noNumberToStart', null),
            array('testé"(§è!', null),
            array('', null),
            array(false, null),
            array(array(), null),
            array($this, null),
            array('vendor\\MyClass', 'vendor\\MyClass'),
            array('vendor\\MyClass', '1noNumberToStart'),
            array('vendor\\MyClass', 'testé"(§è!'),
            array('vendor\\MyClass', ''),
            array('vendor\\MyClass', false),
            array('vendor\\MyClass', array()),
            array('vendor\\MyClass', $this),
        );
    }

}
