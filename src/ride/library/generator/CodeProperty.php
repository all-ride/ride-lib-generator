<?php

namespace ride\library\generator;

/**
 * Interface for the definition of a class property
 */
interface CodeProperty extends CodeVariable {

    /**
     * Gets the scope of the variable
     * @return string public, protected or private
     */
    public function getScope();

}
