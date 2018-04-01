<?php

namespace ride\library\generator;

use PHPUnit\Framework\TestCase;

class GenericCodeGeneratorTest extends TestCase {

    public function setUp() {
        $this->generator = new GenericCodeGenerator();
    }

    public function testCreateClass() {
        $name = 'className';
        $extends = 'anotherClassName';
        $implements = array(
            'interface1' => true,
            'interface2' => true,
        );
        $isInterface = true;
        $isAbstract = false;

        $codeClass = $this->generator->createClass($name, $extends, array_keys($implements), $isInterface, $isAbstract);

        $this->assertNotNull($codeClass);
        $this->assertTrue($codeClass instanceof GenericCodeClass);
        $this->assertEquals($name, $codeClass->getName());
        $this->assertEquals($extends, $codeClass->getExtends());
        $this->assertEquals($implements, $codeClass->getImplements());
        $this->assertEquals($isInterface, $codeClass->isInterface());
        $this->assertEquals($isAbstract, $codeClass->isAbstract());
    }

    public function testCreateMethod() {
        $name = 'name';
        $arguments = array();
        $source = 'phpinfo();';
        $scope = 'protected';
        $isAbstract = true;
        $isStatic = true;

        $codeMethod = $this->generator->createMethod($name, $arguments, $source, $scope, $isAbstract, $isStatic);

        $this->assertNotNull($codeMethod);
        $this->assertTrue($codeMethod instanceof GenericCodeMethod);
        $this->assertEquals($name, $codeMethod->getName());
        $this->assertEquals($arguments, $codeMethod->getArguments());
        $this->assertEquals($source, $codeMethod->getSource());
        $this->assertEquals($scope, $codeMethod->getScope());
        $this->assertEquals($isAbstract, $codeMethod->isAbstract());
        $this->assertEquals($isStatic, $codeMethod->isStatic());
    }

    public function testCreateProperty() {
        $name = 'name';
        $type = 'string';
        $scope = 'protected';

        $codeProperty = $this->generator->createProperty($name, $type, $scope);

        $this->assertNotNull($codeProperty);
        $this->assertTrue($codeProperty instanceof GenericCodeProperty);
        $this->assertEquals($name, $codeProperty->getName());
        $this->assertEquals($type, $codeProperty->getType());
        $this->assertEquals($scope, $codeProperty->getScope());
    }

    public function testCreateVariable() {
        $name = 'name';
        $type = 'string';

        $codeVariable = $this->generator->createVariable($name, $type);

        $this->assertNotNull($codeVariable);
        $this->assertTrue($codeVariable instanceof GenericCodeVariable);
        $this->assertEquals($name, $codeVariable->getName());
        $this->assertEquals($type, $codeVariable->getType());
    }

    public function testGenerateSimpleInterface() {
        $method = $this->generator->createMethod('isActive');

        $interface = $this->generator->createClass('vendor\\library\\MyInterface');
        $interface->setIsInterface(true);
        $interface->addMethod($method);

        $source = $this->generator->generateClass($interface);
        $expected =
'<?php

namespace vendor\\library;

interface MyInterface {

    /**
     * @return null
     */
    public function isActive();

}
';

        $this->assertEquals($expected, $source);
    }

    public function testGenerateAdvancedInterface() {
        $variable = $this->generator->createVariable('isActive', 'boolean');
        $variable->setDescription('Flag to see if this instance is active');

        $isActiveMethod = $this->generator->createMethod('isActive');
        $isActiveMethod->setDescription('Checks if this instance is active');
        $isActiveMethod->setReturnValue($variable);

        $setIsActiveMethod = $this->generator->createMethod('setIsActive', array($variable));
        $setIsActiveMethod->setDescription('Sets the active state of this instance');

        $data = $this->generator->createVariable('data', 'vendor\\library\\data\\DataContainer');
        $data->setDescription('Data container');

        $checkDataMethod = $this->generator->createMethod('checkData', array($data));
        $checkDataMethod->setDescription('Checks the provided data');
        $checkDataMethod->setReturnValue($this->generator->createVariable('isValid', 'boolean'));

        $interface = $this->generator->createClass('vendor\\library\\MyInterface');
        $interface->setIsInterface(true);
        $interface->addMethod($isActiveMethod);
        $interface->addMethod($setIsActiveMethod);
        $interface->addMethod($checkDataMethod);

        $source = $this->generator->generateClass($interface);
        $expected =
'<?php

namespace vendor\\library;

use vendor\\library\\data\\DataContainer;

interface MyInterface {

    /**
     * Checks if this instance is active
     * @return boolean Flag to see if this instance is active
     */
    public function isActive();

    /**
     * Sets the active state of this instance
     * @param boolean $isActive Flag to see if this instance is active
     * @return null
     */
    public function setIsActive($isActive);

    /**
     * Checks the provided data
     * @param \\vendor\\library\\data\\DataContainer $data Data container
     * @return boolean
     */
    public function checkData(DataContainer $data);

}
';

        $this->assertEquals($expected, $source);
    }

    public function testGenerateClass() {
        $constant = $this->generator->createVariable('MY_CONSTANT', 'string');
        $constant->setDefaultValue('constant');
        $constant->setDescription('Dummy constant');

        $isActiveProperty = $this->generator->createProperty('isActive', 'boolean', 'private');
        $isActiveProperty->setDefaultValue(false);
        $isActiveProperty->setDescription('Flag to see if this instance is active');

        $dataVariable = $this->generator->createVariable('data', 'vendor\\library\\data\\DataContainer');
        $dataVariable->setDescription('Data container to check');

        $isValidVariable = $this->generator->createVariable('isValid', 'boolean');
        $isValidVariable->setDescription('Flag to see if the provided data is valid');

        $isActiveMethod = $this->generator->createMethod('isActive');
        $isActiveMethod->setDescription('Checks if this instance is active');
        $isActiveMethod->setSource('return $this->isActive;');
        $isActiveMethod->setReturnValue($isActiveProperty);

        $setIsActiveMethod = $this->generator->createMethod('setIsActive', array($isActiveProperty));
        $setIsActiveMethod->setDescription('Sets the active state of this instance');
        $setIsActiveMethod->setSource('$this->isActive = $isActive;');

        $checkDataMethod = $this->generator->createMethod('checkData', array($dataVariable));
        $checkDataMethod->setDescription('Checks the provided data');
        $checkDataMethod->setSource('return $data->isValid();');
        $checkDataMethod->setReturnValue($isValidVariable);
        $checkDataMethod->addUse('vendor\\library\\helper\\MyHelper');

        $class = $this->generator->createClass('vendor\\library\\MyClass', null, array('vendor\\library\\MyInterface'));
        $class->addConstant($constant);
        $class->addProperty($isActiveProperty);
        $class->addMethod($isActiveMethod);
        $class->addMethod($setIsActiveMethod);
        $class->addMethod($checkDataMethod);

        $source = $this->generator->generateClass($class);
        $expected =
        '<?php

namespace vendor\\library;

use vendor\\library\\data\\DataContainer;
use vendor\\library\\helper\\MyHelper;

class MyClass implements MyInterface {

    /**
     * Dummy constant
     * @var string
     */
    const MY_CONSTANT = \'constant\';

    /**
     * Flag to see if this instance is active
     * @var boolean
     */
    private $isActive = false;

    /**
     * Checks if this instance is active
     * @return boolean Flag to see if this instance is active
     */
    public function isActive() {
        return $this->isActive;
    }

    /**
     * Sets the active state of this instance
     * @param boolean $isActive Flag to see if this instance is active
     * @return null
     */
    public function setIsActive($isActive = false) {
        $this->isActive = $isActive;
    }

    /**
     * Checks the provided data
     * @param \\vendor\\library\\data\\DataContainer $data Data container to check
     * @return boolean Flag to see if the provided data is valid
     */
    public function checkData(DataContainer $data) {
        return $data->isValid();
    }

}
';

        $this->assertEquals($expected, $source);
    }

    public function testGenerateAbstractClass() {
        $resolverVariable = $this->generator->createVariable('resolver', 'vendor\\library\\resolver\\Resolver');
        $resolverVariable->setDescription('Resolver to set');

        $setResolverMethod = $this->generator->createMethod('setResolver', array($resolverVariable));
        $setResolverMethod->setIsAbstract(true);

        $class = $this->generator->createClass('vendor\\library\\MyAbstractProvider', 'vendor\\library\\provider\\AbstractProvider', array('vendor\\library\\provider\\Provider1', 'vendor\\library\\provider\\Provider2'));
        $class->setIsAbstract(true);
        $class->addMethod($setResolverMethod);
        $class->addUse('vendor\\library\\helper\\MyHelper', 'Helper');

        $source = $this->generator->generateClass($class);
        $expected =
        '<?php

namespace vendor\\library;

use vendor\\library\\helper\\MyHelper as Helper;
use vendor\\library\\provider\\AbstractProvider;
use vendor\\library\\provider\\Provider1;
use vendor\\library\\provider\\Provider2;
use vendor\\library\\resolver\\Resolver;

abstract class MyAbstractProvider extends AbstractProvider implements Provider1, Provider2 {

    /**
     * @param \\vendor\\library\\resolver\\Resolver $resolver Resolver to set
     * @return null
     */
    abstract public function setResolver(Resolver $resolver);

}
';

        $this->assertEquals($expected, $source);
    }

}
