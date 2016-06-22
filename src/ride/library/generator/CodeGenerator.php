<?php

namespace ride\library\generator;

/**
 * Interface for a code generator
 */
interface CodeGenerator {

    /**
     * Creates a new class definition
     * @param string $name Name of the class
     * @param string $extends Name of the extended class
     * @param array $implements Names of the implemented classes
     * @param boolean $isInterface Flag to see if the class is an interface
     * @param boolean $isAbstract Flag to see if the class is abstract
     * @return CodeClass
     */
    public function createClass($name, $extends = null, array $implements = array(), $isInterface = false, $isAbstract = false);

    /**
     * Creates a new method definition
     * @param string $name Name of the method
     * @param array $arguments Arguments for the method, instances of
     * CodeVariable
     * @param string $source Source code of the method
     * @param string $scope Scope of the method
     * @param boolean $isAbstract Flag to see if this method is abstract
     * @return CodeMethod
     */
    public function createMethod($name, array $arguments = array(), $source = null, $scope = null, $isAbstract = false, $isStatic = false);

    /**
     * Creates a new property definition
     * @param string $name Name of the property
     * @param string $type Type of the property
     * @param string $scope Scope of the property, defaults to public
     * @return CodeProperty
     */
    public function createProperty($name, $type, $scope = null);

    /**
     * Constructs a new variable definition
     * @param string $name Name of the variable
     * @param string $type Type of the variable
     * @return CodeVariable
     */
    public function createVariable($name, $type);

    /**
     * Generates the source for the provided class definition
     * @param CodeClass $class
     * @return string Source of the class
     */
    public function generateClass(CodeClass $class);

}
