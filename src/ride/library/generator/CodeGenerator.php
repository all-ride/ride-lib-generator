<?php

namespace ride\library\generator;

/**
 * Interface for a code generator
 */
interface CodeGenerator {

    /**
     * Generates the source for the provided class definition
     * @param CodeClass $class
     * @return string Source of the class
     */
    public function generateClass(CodeClass $class);

}
