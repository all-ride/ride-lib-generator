<?php

namespace ride\library\generator;

/**
 * Interface for the definition of a class method
 */
interface CodeMethod {

    /**
     * Gets the name of this method
     * @return string
     */
    public function getName();

    /**
     * Gets the description of the variable for code documentation
     * @return string
     */
    public function getDescription();

    /**
     * Gets the scope of this method
     * @return string
     */
    public function getScope();

    /**
     * Gets whether this is an abstract method
     * @return boolean
     */
    public function isAbstract();

    /**
     * Gets the arguments of this method
     * @return array Instances of CodeVariable
     * @see CodeVariable
     */
    public function getArguments();

    /**
     * Gets the return value of the method
     * @return CodeVariable|null Instance of CodeVariable if set, null otherwise
     */
    public function getReturnValue();

    /**
     * Gets the source for this method
     * @return string
     */
    public function getSource();

    /**
     * Gets the use imports of class names
     * @return array Array with the full class as key and null or alias as value
     */
    public function getUse();

}
