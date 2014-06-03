<?php

namespace ride\library\generator;

use ride\library\generator\exception\GeneratorException;

/**
 * Generic implementaion of the definition of a variable
 */
class GenericCodeVariable implements CodeVariable {

    /**
     * Name of the variable
     * @var string
     */
    protected $name;

    /**
     * Description of the variable
     * @var string
     */
    protected $description;

    /**
     * Type of the variable
     * @var string
     */
    protected $type;

    /**
     * Default value for the variable
     * @var mixed
     */
    protected $defaultValue;

    /**
     * Flag to see if this variable has a default value
     * @var boolean
     */
    protected $hasDefaultValue;

    /**
     * Constructs a new variable
     * @param string $name Name of the variable
     * @param string $type Type of the variable
     * @return null
     */
    public function __construct($name, $type) {
        $this->setName($name);
        $this->setType($type);

        $this->description = null;
        $this->hasDefaultValue = false;
    }

    /**
     * Sets the name of the variable
     * @param string $name
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when an
     * invalid value has been provided
     */
    public function setName($name) {
        if (!Code::isValidName($name, false)) {
            throw new GeneratorException('Could not set the name of the variable: invalid or empty string provided');
        }

        $this->name = $name;
    }

    /**
     * Gets the name of the variable
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the description of the variable for code documentation
     * @param string $description
     * @return null
     */
    public function setDescription($description) {
        if ($description !== null && !is_string($description)) {
            throw new GeneratorException('Could not set the description of the variable: invalid string provided');
        }

        $this->description = $description;
    }

    /**
     * Gets the description of the variable for code documentation
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the type of this variable
     * @param string $type
     * @return null
     * @throws \ride\library\generator\exception\GeneratorException when an
     * invalid value has been provided
     */
    public function setType($type) {
        if (!Code::isValidName($type, true)) {
            throw new GeneratorException('Could not set the type of the variable: invalid or empty string provided');
        }

        $this->type = $type;
    }

    /**
     * Gets the type of the variable
     * @return string Full class name, array or scalar type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Gets whether this variable has a default value
     * @return boolean
     */
    public function hasDefaultValue() {
        return $this->hasDefaultValue;
    }

    /**
     * Sets the default value of the variable
     * @param mixed $defaultValue
     * @return null
     */
    public function setDefaultValue($defaultValue) {
        $this->defaultValue = $defaultValue;

        $this->hasDefaultValue = true;
    }

    /**
     * Gets the default of the variable
     * @return mixed
     */
    public function getDefaultValue() {
        return $this->defaultValue;
    }

}
