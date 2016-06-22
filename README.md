# Ride: Event Library

Code generator library of the PHP Ride framework.

## CodeGenerator

The CodeGenerator interface is the main interface of this library.
You can use it to create classes, methods, properties and variables.
After setting up you class, you can pass it to the _generateClass_ method to get the PHP code.

## CodeClass

The CodeClass interface is the representation of a class or interface.
You can add constants, properties and methods to it.
When done, you can pass it to the _generateClass_ method of a CodeGenerator to get the PHP code.

## CodeMethod

The CodeMethod interface is the representation of a class method.
You can add arguments to it, set whether it's abstract or static, set the source, ...
When the method is defined, you can add it to a CodeClass instance.

## CodeProperty

The CodeProperty interface is the representation of a class property.
It extends from the CodeVariable and can thus be used as a variable as well.
The difference between the two is that a property has a scope.

## CodeVariable

The CodeVariable interface is the representation of a variable.
It can be used for a class constant, a method argument or a return value.
 
## Code Sample

Check this code sample to see some possibilities of this library:

```php
<?php

use ride\library\generator\CodeGenerator;

function generateCode(CodeGenerator $generator) {
    // create a constant
    $constant = $generator->createVariable('MY_CONSTANT', 'string');
    $constant->setDefaultValue('constant');
    $constant->setDescription('Dummy constant');

    // create a property
    $isActiveProperty = $generator->createProperty('isActive', 'boolean', 'private');
    $isActiveProperty->setDefaultValue(false);
    $isActiveProperty->setDescription('Flag to see if this instance is active');

    // create needed variables
    $dataVariable = $generator->createVariable('data', 'vendor\\library\\data\\DataContainer');
    $dataVariable->setDescription('Data container to check');

    $isValidVariable = $generator->createVariable('isValid', 'boolean');
    $isValidVariable->setDescription('Flag to see if the provided data is valid');

    // create methods
    $isActiveMethod = $generator->createMethod('isActive');
    $isActiveMethod->setDescription('Checks if this instance is active');
    $isActiveMethod->setSource('return $this->isActive;');
    $isActiveMethod->setReturnValue($isActiveProperty);

    $setIsActiveMethod = $generator->createMethod('setIsActive', array($isActiveProperty));
    $setIsActiveMethod->setDescription('Sets the active state of this instance');
    $setIsActiveMethod->setSource('$this->isActive = $isActive;');

    $checkDataMethod = $generator->createMethod('checkData', array($dataVariable));
    $checkDataMethod->setDescription('Checks the provided data');
    $checkDataMethod->setSource('return $data->isValid();');
    $checkDataMethod->setReturnValue($isValidVariable);

    // join it all together in a class
    $class = $generator->createClass('vendor\\library\\MyClass', null, array('vendor\\library\\MyInterface'));
    $class->addConstant($constant);
    $class->addProperty($isActiveProperty);
    $class->addMethod($isActiveMethod);
    $class->addMethod($setIsActiveMethod);
    $class->addMethod($checkDataMethod);

    // generate the PHP code
    return $this->generator->generateClass($class);
}
```

This function will generate the following PHP code:

```php
<?php

namespace vendor\\library;

use vendor\\library\\data\\DataContainer;

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
```
