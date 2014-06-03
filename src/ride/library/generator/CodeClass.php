<?php

namespace ride\library\generator;

/**
 * Interface for a class definition
 */
interface CodeClass {

    /**
     * Gets the name of the method
     * @return string
     */
    public function getName();

    /**
     * Gets the description of the variable for code documentation
     * @return string
     */
    public function getDescription();

    /**
     * Gets the name of the extended class
     * @return string
     */
    public function getExtends();

    /**
     * Gets the names of the implemented classes
     * @return array Array with the name of the implemented class as key
     */
    public function getImplements();

    /**
     * Gets the use imports of class names
     * @return array Array with the full class as key and null or alias as value
     */
    public function getUse();

    /**
     * Gets whether this class is an interface
     * @return boolean
     */
    public function isInterface();

    /**
     * Gets whether this class is abstract
     * @return boolean
     */
    public function isAbstract();

    /**
     * Gets the constants of the class
     * @return array Array with CodeVariable instances
     * @see CodeVariable
     */
    public function getConstants();

    /**
     * Gets the properties of the class
     * @return array Array with CodeProperty instances
     * @see CodeProperty
     */
    public function getProperties();

    /**
     * Gets the methods of the class
     * @return array Array with CodeMethod instances
     * @see CodeMethod
     */
    public function getMethods();

}
