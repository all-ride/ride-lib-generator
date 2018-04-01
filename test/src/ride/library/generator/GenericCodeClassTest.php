<?php

namespace ride\library\generator;

use PHPUnit\Framework\TestCase;

class GenericCodeClassTest extends TestCase {

    public function testMinimalConstruct() {
        $name = 'className';
        $codeClass = new GenericCodeClass($name);

        $this->assertEquals($name, $codeClass->getName());
        $this->assertNull($codeClass->getDescription());
        $this->assertNull($codeClass->getExtends());
        $this->assertEquals(array(), $codeClass->getImplements());
        $this->assertEquals(array(), $codeClass->getUse());
        $this->assertFalse($codeClass->isInterface());
        $this->assertFalse($codeClass->isAbstract());
        $this->assertEquals(array(), $codeClass->getConstants());
        $this->assertEquals(array(), $codeClass->getProperties());
        $this->assertEquals(array(), $codeClass->getMethods());
    }

    /**
     * @dataProvider providerExtendedConstruct
     */
    public function testExtendedConstruct($name, $extends, array $implements, $isInterface, $isAbstract) {
        $codeClass = new GenericCodeClass($name, $extends, $implements, $isInterface, $isAbstract);

        $resultImplements = array();
        foreach ($implements as $interface) {
            $resultImplements[$interface] = true;
        }

        $this->assertEquals($name, $codeClass->getName());
        $this->assertEquals($extends, $codeClass->getExtends());
        $this->assertEquals($resultImplements, $codeClass->getImplements());
        $this->assertEquals($isInterface, $codeClass->isInterface());
        $this->assertEquals($isAbstract, $codeClass->isAbstract());
    }

    public function providerExtendedConstruct() {
        return array(
            array(
                'className',
                'anotherClassName',
                array(
                    'interface1',
                    'interface2',
                ),
                true,
                false,
            ),
            array(
                'vendor\\className',
                null,
                array(
                    'interface1',
                    'interface2',
                ),
                false,
                true,
            ),
        );
    }

    /**
     * @dataProvider providerSetNameThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetNameThrowsExceptionOnInvalidValue($name) {
        new GenericCodeClass($name);
    }

    public function providerSetNameThrowsExceptionOnInvalidValue() {
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
        $codeClass = new GenericCodeClass('name');
        $codeClass->setDescription($description);
    }

    public function testSetDescription() {
        $codeClass = new GenericCodeClass('name');
        $codeClass->setDescription('description');

        $this->assertSame('description', $codeClass->getDescription());
    }

    public function testRemoveImplementsShouldReturnFalse() {
        $codeClass = new GenericCodeClass('name');

        $this->assertFalse($codeClass->removeImplements('implementClass'));
    }

    public function testRemoveImplementsShouldReturnTrue() {
        $codeClass = new GenericCodeClass('name');
        $codeClass->addImplements('implementClass');

        $this->assertTrue($codeClass->removeImplements('implementClass'));
    }

    public function providerSetDescriptionThrowsExceptionOnInvalidValue() {
        return array(
            array(array()),
            array($this)
        );
    }

    /**
     * @dataProvider providerSetExtendsThrowsExceptionOnInvalidValue
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetExtendsThrowsExceptionOnInvalidValue($extends) {
        $codeClass = new GenericCodeClass('name');
        $codeClass->setExtends($extends);
    }

    public function providerSetExtendsThrowsExceptionOnInvalidValue() {
        return array(
            array('1noNumberToStart'),
            array('testé"(§è!'),
            array(null),
            array($this),
            array(array()),
        );
    }

    public function testImplements() {
        $codeClass = new GenericCodeClass('name');

        $this->assertEquals(array(), $codeClass->getImplements());

        $codeClass->addImplements('MyClass');
        $codeClass->addImplements('vendor\\MyClass');
        $codeClass->addImplements('MyClass');

        $expected = array(
            'MyClass' => true,
            'vendor\\MyClass' => true,
        );

        $this->assertEquals($expected, $codeClass->getImplements());
    }

    /**
     * @dataProvider providerAddImplementsThrowsExceptionWhenInvalidArgumentProvided
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testAddImplementsThrowsExceptionWhenInvalidArgumentProvided($class) {
        $codeClass = new GenericCodeClass('name');
        $codeClass->addImplements($class);
    }

    public function providerAddImplementsThrowsExceptionWhenInvalidArgumentProvided() {
        return array(
            array('1noNumberToStart'),
            array('testé"(§è!'),
            array(''),
            array(false),
            array(array()),
            array($this),
        );
    }

    public function testUse() {
        $codeClass = new GenericCodeClass('name');

        $this->assertEquals(array(), $codeClass->getUse());

        $codeClass->addUse('MyClass');
        $codeClass->addUse('vendor\\MyClass', 'VC');

        $expected = array(
            'MyClass' => null,
            'vendor\\MyClass' => 'VC',
        );

        $this->assertEquals($expected, $codeClass->getUse());
    }

    /**
     * @dataProvider providerAddUseThrowsExceptionWhenInvalidArgumentProvided
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testAddUseThrowsExceptionWhenInvalidArgumentProvided($class, $alias) {
        $codeClass = new GenericCodeClass('name');
        $codeClass->addUse($class, $alias);
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

    public function testConstants() {
        $codeClass = new GenericCodeClass('name');
        $constant = new GenericCodeVariable('name', 'string');
        $constant2 = new GenericCodeVariable('isOk', 'boolean');

        $this->assertEquals(array(), $codeClass->getConstants());
        $this->assertFalse($codeClass->hasConstant('name'));
        $this->assertNull($codeClass->getConstant('name'));

        $codeClass->addConstant($constant);

        $this->assertEquals(array('name' => $constant), $codeClass->getConstants());
        $this->assertTrue($codeClass->hasConstant('name'));
        $this->assertEquals($constant, $codeClass->getConstant('name'));

        $this->assertFalse($codeClass->removeConstant('test'));
        $this->assertTrue($codeClass->removeConstant('name'));

        $this->assertEquals(array(), $codeClass->getConstants());
        $this->assertFalse($codeClass->hasConstant('name'));
        $this->assertNull($codeClass->getConstant('name'));

        $codeClass->setConstants(array($constant, $constant2));

        $this->assertEquals(array('name' => $constant, 'isOk' => $constant2), $codeClass->getConstants());
        $this->assertTrue($codeClass->hasConstant('name'));
        $this->assertEquals($constant, $codeClass->getConstant('name'));
        $this->assertTrue($codeClass->hasConstant('isOk'));
        $this->assertEquals($constant2, $codeClass->getConstant('isOk'));
    }

    /**
     * @dataProvider providerSetConstantsThrowsExceptionWhenInvalidArgumentProvided
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetConstantsThrowsExceptionWhenInvalidArgumentProvided(array $constants) {
        $codeClass = new GenericCodeClass('name');
        $codeClass->setConstants($constants);
    }

    public function providerSetConstantsThrowsExceptionWhenInvalidArgumentProvided() {
        return array(
            array(array(null)),
            array(array(true)),
            array(array('string')),
            array(array(5)),
            array(array(array())),
            array(array($this)),
        );
    }

    public function testProperties() {
        $codeClass = new GenericCodeClass('name');
        $property = new GenericCodeProperty('name', 'string');
        $property2 = new GenericCodeProperty('isOk', 'boolean');

        $this->assertEquals(array(), $codeClass->getProperties());
        $this->assertFalse($codeClass->hasProperty('name'));
        $this->assertNull($codeClass->getProperty('name'));

        $codeClass->addProperty($property);

        $this->assertEquals(array('name' => $property), $codeClass->getProperties());
        $this->assertTrue($codeClass->hasProperty('name'));
        $this->assertEquals($property, $codeClass->getProperty('name'));

        $this->assertFalse($codeClass->removeProperty('test'));
        $this->assertTrue($codeClass->removeProperty('name'));

        $this->assertEquals(array(), $codeClass->getProperties());
        $this->assertFalse($codeClass->hasProperty('name'));
        $this->assertNull($codeClass->getProperty('name'));

        $codeClass->setProperties(array($property, $property2));

        $this->assertEquals(array('name' => $property, 'isOk' => $property2), $codeClass->getProperties());
        $this->assertTrue($codeClass->hasProperty('name'));
        $this->assertEquals($property, $codeClass->getProperty('name'));
        $this->assertTrue($codeClass->hasProperty('isOk'));
        $this->assertEquals($property2, $codeClass->getProperty('isOk'));
    }

    /**
     * @dataProvider providerSetPropertiesThrowsExceptionWhenInvalidArgumentProvided
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetPropertiesThrowsExceptionWhenInvalidArgumentProvided(array $properties) {
        $codeClass = new GenericCodeClass('name');
        $codeClass->setProperties($properties);
    }

    public function providerSetPropertiesThrowsExceptionWhenInvalidArgumentProvided() {
        return array(
            array(array(null)),
            array(array(true)),
            array(array('string')),
            array(array(5)),
            array(array(array())),
            array(array($this)),
        );
    }

    public function testMethods() {
        $codeClass = new GenericCodeClass('name');
        $method = new GenericCodeMethod('name');
        $method2 = new GenericCodeMethod('isOk');

        $this->assertEquals(array(), $codeClass->getMethods());
        $this->assertFalse($codeClass->hasMethod('name'));
        $this->assertNull($codeClass->getMethod('name'));

        $codeClass->addMethod($method);

        $this->assertEquals(array('name' => $method), $codeClass->getMethods());
        $this->assertTrue($codeClass->hasMethod('name'));
        $this->assertEquals($method, $codeClass->getMethod('name'));

        $this->assertFalse($codeClass->removeMethod('test'));
        $this->assertTrue($codeClass->removeMethod('name'));

        $this->assertEquals(array(), $codeClass->getMethods());
        $this->assertFalse($codeClass->hasMethod('name'));
        $this->assertNull($codeClass->getMethod('name'));

        $codeClass->setMethods(array($method, $method2));

        $this->assertEquals(array('name' => $method, 'isOk' => $method2), $codeClass->getMethods());
        $this->assertTrue($codeClass->hasMethod('name'));
        $this->assertEquals($method, $codeClass->getMethod('name'));
        $this->assertTrue($codeClass->hasMethod('isOk'));
        $this->assertEquals($method2, $codeClass->getMethod('isOk'));
    }

    /**
     * @dataProvider providerSetMethodsThrowsExceptionWhenInvalidArgumentProvided
     * @expectedException ride\library\generator\exception\GeneratorException
     */
    public function testSetMethodsThrowsExceptionWhenInvalidArgumentProvided(array $methods) {
        $codeClass = new GenericCodeClass('name');
        $codeClass->setMethods($methods);
    }

    public function providerSetMethodsThrowsExceptionWhenInvalidArgumentProvided() {
        return array(
            array(array(null)),
            array(array(true)),
            array(array('string')),
            array(array(5)),
            array(array(array())),
            array(array($this)),
        );
    }

}
