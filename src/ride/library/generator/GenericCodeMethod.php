<?php

namespace ride\library\generator;

use ride\library\generator\exception\GeneratorException;

/**
 * Generic implementaion of the definition of a class method
 */
class GenericCodeMethod implements CodeMethod {

    /**
     * Name of the method
     * @var string
     */
    protected $name;

    /**
     * Description of the method
     * @var string
     */
    protected $description;

    /**
     * Scope of the method
     * @var string
     */
    protected $scope;

    /**
     * Flag to see if it's a abstract method
     * @var boolean
     */
    protected $isAbstract;

    /**
     * Flag to see if it's a static method
     * @var boolean
     */
    protected $isStatic;

    /**
     * Arguments of the method
     * @var array
     */
    protected $arguments;

    /**
     * Return value
     * @var CodeVariable
     */
    protected $returnValue;

    /**
     * Source of the method
     * @var string
     */
    protected $source;

    /**
     * Use imports of the source
     * @var array
     */
    protected $use;

    /**
     * Constructs a new method
     * @param string $name Name of the method
     * @param array $arguments Arguments for the method, instances of
     * CodeVariable
     * @param string $source Source code of the method
     * @param string $scope Scope of the method
     * @param boolean $isAbstract Flag to see if this method is abstract
     * @return null
     */
    public function __construct($name, array $arguments = array(), $source = null, $scope = null, $isAbstract = false, $isStatic = false) {
        $this->setName($name);
        $this->setArguments($arguments);
        $this->setIsAbstract($isAbstract);
        $this->setIsStatic($isStatic);
        $this->setSource($source);

        if ($scope) {
            $this->setScope($scope);
        }

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
     * Sets the description of the method for code documentation
     * @param string $description
     * @return null
     */
    public function setDescription($description) {
        if ($description !== null && !is_string($description)) {
            throw new GeneratorException('Could not set the description of the method: invalid string provided');
        }

        $this->description = $description;
    }

    /**
     * Gets the description of the method for code documentation
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the scope of this method
     * @param string $scope
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when an
     * invalid value has been provided
     */
    public function setScope($scope) {
        if (!Code::isValidScope($scope)) {
            throw new GeneratorException('Could not set the scope of the property: only public, protected and private are allowed');
        }

        $this->scope = $scope;
    }

    /**
     * Gets the scope of the method
     * @return string
     */
    public function getScope() {
        return $this->scope ? $this->scope : Code::SCOPE_PUBLIC;
    }

    /**
     * Sets whether this method is abstract
     * @param boolean $isAbstract
     * @return null
     */
    public function setIsAbstract($isAbstract) {
        $this->isAbstract = $isAbstract;
    }

    /**
     * Gets whether this method is abstract
     * @return boolean
     */
    public function isAbstract() {
        return $this->isAbstract;
    }

    /**
     * Sets whether this method is static
     * @param boolean $isStatic
     * @return null
     */
    public function setIsStatic($isStatic) {
        $this->isStatic = $isStatic;
    }

    /**
     * Gets whether this method is static
     * @return boolean
     */
    public function isStatic() {
        return $this->isStatic;
    }

    /**
     * Adds an argument to this method
     * @param CodeVariable $argument
     * @return null
     */
    public function addArgument(CodeVariable $argument) {
        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * Checks if a argument is set
     * @param string Name of the argument
     * @return boolean True if found, false otherwise
     */
    public function hasArgument($name) {
        return isset($this->arguments[$name]);
    }

    /**
     * Gets a argument from the method
     * @param string $name Name of the argument
     * @return CodeVariable|null Instance of CodeVariable if found, null
     * otherwise
     */
    public function getArgument($name) {
        if (!$this->hasArgument($name)) {
            return null;
        }

        return $this->arguments[$name];
    }

    /**
     * Removes an argument from this method
     * @param string $name Name of the argument
     * @return boolean True if found and removed, false otherwise
     */
    public function removeArgument($name) {
        if (!$this->hasArgument($name)) {
            return false;
        }

        unset($this->arguments[$name]);

        return true;
    }

    /**
     * Sets the arguments of the method
     * @param array $arguments Array with instances of Variable
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when a non
     * CodeVariable instance has been detected
     * @see CodeVariable
     */
    public function setArguments(array $arguments) {
        foreach ($arguments as $index => $argument) {
            if (!$argument instanceof CodeVariable) {
                throw new GeneratorException('Could not set the method arguments: non ride\library\generator\CodeVariable instance detected at index ' . $index);
            }
        }

        $this->arguments = $arguments;
    }

    /**
     * Gets the arguments of the method
     * @return array Array with CodeVariable instances
     * @see CodeVariable
     */
    public function getArguments() {
        return $this->arguments;
    }

    /**
     * Sets the return value of the method
     * @param CodeVariable $returnValue
     * @return null
     */
    public function setReturnValue(CodeVariable $returnValue) {
        $this->returnValue = $returnValue;
    }

    /**
     * Gets the return value of the method
     * @return CodeVariable|null Instance of CodeVariable if set, null otherwise
     */
    public function getReturnValue() {
        return $this->returnValue;
    }

    /**
     * Sets the source code of the method
     * @param string $source
     * @return null
     */
    public function setSource($source) {
        if ($source !== null && !is_string($source)) {
            throw new GeneratorException('Could not set the source of the method: invalid string provided');
        }

        $this->source = $source;
    }

    /**
     * Gets the source code of the method
     * @return string Source code of the method
     */
    public function getSource() {
        return $this->source;
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

}
