<?php

namespace ride\library\generator;

/**
 * Interface for the definition of a variable
 */
interface CodeVariable {

    /**
     * Gets the name of the variable
     * @return string
     */
    public function getName();

    /**
     * Gets the description of the variable for code documentation
     * @return string
     */
    public function getDescription();

    /**
     * Gets the type of the variable
     * @return string Full class name, array or scalar type
     */
    public function getType();

    /**
     * Gets whether this variable has a default value
     * @return boolean
     */
    public function hasDefaultValue();

    /**
     * Gets the default of the variable
     * @return mixed
     */
    public function getDefaultValue();

}
