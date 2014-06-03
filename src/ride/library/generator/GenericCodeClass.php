<?php

namespace ride\library\generator;

use ride\library\generator\exception\GeneratorException;

/**
 * Generic implementaion of the definition of a class method
 */
class GenericCodeClass implements CodeClass {

    /**
     * Full name of the class
     * @var string
     */
    protected $name;

    /**
     * Description of the class
     * @var string
     */
    protected $description;

    /**
     * Use imports of the class
     * @var array
     */
    protected $use;

    /**
     * Full name of the extended class
     * @var string
     */
    protected $extends;

    /**
     * Array with full names of the implemented classses
     * @var array
     */
    protected $implements;

    /**
     * Flag to see if it's an interface
     * @var boolean
     */
    protected $isInterface;

    /**
     * Flag to see if it's a abstract class
     * @var boolean
     */
    protected $isAbstract;

    /**
     * Constants of the class
     * @var array
     */
    protected $constants;

    /**
     * Properties of the class
     * @var array
     */
    protected $properties;

    /**
     * Methods of the class
     * @var array
     */
    protected $methods;

    /**
     * Constructs a new class
     * @param string $name Name of the class
     * @param string $extends Name of the extended class
     * @param array $implements Names of the implemented classes
     * @param boolean $isInterface Flag to see if the class is an interface
     * @param boolean $isAbstract Flag to see if the class is abstract
     * @return null
     */
    public function __construct($name, $extends = null, array $implements = array(), $isInterface = false, $isAbstract = false) {
        $this->setName($name);
        $this->setIsInterface($isInterface);
        $this->setIsAbstract($isAbstract);

        if ($extends) {
            $this->setExtends($extends);
        }

        foreach ($implements as $implementedClass) {
            $this->addImplements($implementedClass);
        }

        $this->constants = array();
        $this->properties = array();
        $this->methods = array();
        $this->use = array();
    }

    /**
     * Sets the name of the method
     * @param string $name
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when an
     * invalid value has been provided
     */
    public function setName($name) {
        if (!Code::isValidName($name, false)) {
            throw new GeneratorException('Could not set the name of the method: invalid or empty name provided');
        }

        $this->name = $name;
    }

    /**
     * Gets the name of the method
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the description of the class for code documentation
     * @param string $description
     * @return null
     */
    public function setDescription($description) {
        if ($description !== null && !is_string($description)) {
            throw new GeneratorException('Could not set the description of the class: invalid string provided');
        }

        $this->description = $description;
    }

    /**
     * Gets the description of the class for code documentation
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the name of the extended class
     * @param string $extends
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when an
     * invalid value has been provided
     */
    public function setExtends($extends) {
        if (!Code::isValidName($extends, true)) {
            throw new GeneratorException('Could not set the name of the extended class: invalid or empty name provided');
        }

        $this->extends = $extends;
    }

    /**
     * Gets the name of the extended class
     * @return string
     */
    public function getExtends() {
        return $this->extends;
    }

    /**
     * Adds the name of a implemented class
     * @param string $implementedClass
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when an
     * invalid value has been provided
     */
    public function addImplements($implementedClass) {
        if (!Code::isValidName($implementedClass, true)) {
            throw new GeneratorException('Could not add the name of the implemented class: invalid or empty name provided');
        }

        $this->implements[$implementedClass] = true;
    }

    /**
     * Removes the name of a implemented class
     * @param string $implementedClass
     * @return boolean True when the implemented class has been found and
     * removed, false otherwise
     */
    public function removeImplements($implementedClass) {
        if (!isset($this->implements[$implementedClass])) {
            return false;
        }

        unset($this->implements[$implementedClass]);

        return true;
    }

    /**
     * Gets the names of the implemented classes
     * @return array Array with the name of the implemented class as key
     */
    public function getImplements() {
        return $this->implements;
    }

    /**
     * Adds a use import
     * @param string $className Full class name
     * @param string $alias Class alias
     * @return null
     */
    public function addUse($className, $alias = null) {
        if (!Code::isValidName($className, true)) {
            throw new GeneratorException('Could not add use import: invalid className provided');
        }

        if ($alias !== null && !Code::isValidName($className, false)) {
            throw new GeneratorException('Could not add use import: invalid alias provided');
        }

        $this->use[$className] = $alias;
    }

    /**
     * Gets the use imports of class names
     * @return array Array with the full class as key and null or alias as value
     */
    public function getUse() {
        return $this->use;
    }

    /**
     * Sets whether this class is an interface
     * @param boolean $isInterface
     * @return null
     */
    public function setIsInterface($isInterface) {
        $this->isInterface = $isInterface;
    }

    /**
     * Gets whether this class is an interface
     * @return boolean
     */
    public function isInterface() {
        return $this->isInterface;
    }

    /**
     * Sets whether this class is abstract
     * @param boolean $isAbstract
     * @return null
     */
    public function setIsAbstract($isAbstract) {
        $this->isAbstract = $isAbstract;
    }

    /**
     * Gets whether this class is abstract
     * @return boolean
     */
    public function isAbstract() {
        return $this->isAbstract;
    }

    /**
     * Adds a constant to this class
     * @param CodeVariable $constant
     * @return null
     */
    public function addConstant(CodeVariable $constant) {
        $this->constants[$constant->getName()] = $constant;
    }

    /**
     * Checks if a constant is set
     * @param string Name of the constant
     * @return boolean True if found, false otherwise
     */
    public function hasConstant($name) {
        return isset($this->constants[$name]);
    }

    /**
     * Gets a constant from the class
     * @param string $name Name of the property
     * @return CodeVariable|null Instance of CodeVariable if found, null
     * otherwise
     */
    public function getConstant($name) {
        if (!$this->hasConstant($name)) {
            return null;
        }

        return $this->constants[$name];
    }

    /**
     * Removes a constant from this class
     * @param string $name Name of the constant
     * @return boolean True if found and removed, false otherwise
     */
    public function removeConstant($name) {
        if (!$this->hasConstant($name)) {
            return false;
        }

        unset($this->constants[$name]);

        return true;
    }

    /**
     * Sets the constants of the class
     * @param array $constants Array with instances of CodeVariable
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when a non
     * CodeVariable instance has been detected
     * @see CodeVariable
     */
    public function setConstants(array $constants) {
        foreach ($constants as $index => $constant) {
            if (!$constant instanceof CodeVariable) {
                throw new GeneratorException('Could not set the class constants: non ride\\library\\generator\\CodeVariable instance detected at index ' . $index);
            }
        }

        $this->constants = $constants;
    }

    /**
     * Gets the constants of the class
     * @return array Array with CodeVariable instances
     * @see CodeVariable
     */
    public function getConstants() {
        return $this->constants;
    }

    /**
     * Adds a property to this class
     * @param CodeProperty $method
     * @return null
     */
    public function addProperty(CodeProperty $property) {
        $this->properties[$property->getName()] = $property;
    }

    /**
     * Checks if a property is set
     * @param string Name of the property
     * @return boolean True if found, false otherwise
     */
    public function hasProperty($name) {
        return isset($this->properties[$name]);
    }

    /**
     * Gets a property from the class
     * @param string $name Name of the property
     * @return CodeProperty|null Instance of CodeProperty if found, null
     * otherwise
     */
    public function getProperty($name) {
        if (!$this->hasProperty($name)) {
            return null;
        }

        return $this->properties[$name];
    }

    /**
     * Removes a property from this class
     * @param string $name Name of the property
     * @return boolean True if found and removed, false otherwise
     */
    public function removeProperty($name) {
        if (!$this->hasProperty($name)) {
            return false;
        }

        unset($this->properties[$name]);

        return true;
    }

    /**
     * Sets the properties of the class
     * @param array $properties Array with instances of CodeProperty
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when a non
     * CodeProperty instance has been detected
     * @see CodeProperty
     */
    public function setProperties(array $properties) {
        foreach ($properties as $index => $property) {
            if (!$property instanceof CodeProperty) {
                throw new GeneratorException('Could not set the class properties: non ride\\library\\generator\\CodeProperty instance detected at index ' . $index);
            }
        }

        $this->properties = $properties;
    }

    /**
     * Gets the properties of the class
     * @return array Array with CodeProperty instances
     * @see CodeProperty
     */
    public function getProperties() {
        return $this->properties;
    }

    /**
     * Adds a method to this class
     * @param CodeMethod $method
     * @return null
     */
    public function addMethod(CodeMethod $method) {
        $this->methods[$method->getName()] = $method;
    }

    /**
     * Checks if a method is set
     * @param string Name of the method
     * @return boolean True if found, false otherwise
     */
    public function hasMethod($name) {
        return isset($this->methods[$name]);
    }

    /**
     * Gets a method from the class
     * @param string $name Name of the method
     * @return CodeMethod|null Instance of CodeMethod if found, null otherwise
     */
    public function getMethod($name) {
        if (!$this->hasMethod($name)) {
            return null;
        }

        return $this->methods[$name];
    }

    /**
     * Removes an method from this class
     * @param string $name Name of the method
     * @return boolean True if found and removed, false otherwise
     */
    public function removeMethod($name) {
        if (!$this->hasMethod($name)) {
            return false;
        }

        unset($this->methods[$name]);

        return true;
    }

    /**
     * Sets the methods of the class
     * @param array $methods Array with instances of CodeMethod
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when a non
     * CodeMethod instance has been detected
     * @see CodeMethod
     */
    public function setMethods(array $methods) {
        foreach ($methods as $index => $method) {
            if (!$method instanceof CodeMethod) {
                throw new GeneratorException('Could not set the class methods: non ride\\library\\generator\\CodeMethod instance detected at index ' . $index);
            }
        }

        $this->methods = $methods;
    }

    /**
     * Gets the methods of the class
     * @return array Array with CodeMethod instances
     * @see CodeMethod
     */
    public function getMethods() {
        return $this->methods;
    }

}
