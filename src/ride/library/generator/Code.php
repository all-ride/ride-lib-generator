<?php

namespace ride\library\generator;

/**
 * Helper class for PHP code processing
 */
class Code {

    /**
     * Private scope
     * @var string
     */
    const SCOPE_PRIVATE = 'private';

    /**
     * Protected scope
     * @var string
     */
    const SCOPE_PROTECTED = 'protected';

    /**
     * Public scope
     * @var string
     */
    const SCOPE_PUBLIC = 'public';

    /**
     * Resolves the namespace and class of a full class name
     * @param string $className Full class name
     * @param string $namespace Namespace of the class
     * @param string $name Name of the class inside the namespace
     * @return null
     */
    public static function resolveClassName($className, &$namespace, &$name) {
        $positionSeparator = strrpos($className, '\\');
        if ($positionSeparator === false) {
            $namespace = null;
            $name = $className;
        } elseif ($positionSeparator === 0) {
            $namespace = null;
            $name = substr($className, 1);
        } else {
            $namespace = substr($className, 0, $positionSeparator);
            $name = substr($className, $positionSeparator + 1);
        }
    }

    /**
     * Checks whether the provided type is a type which cannot be used as type
     * in a method signature
     * @return boolean
     */
    public static function isUndefinableType($type) {
        switch ($type) {
            case 'bool':
            case 'boolean':
            case 'int':
            case 'integer':
            case 'double':
            case 'float':
            case 'string':
            case 'datetime':
            case 'time':
            case 'mixed':
                return true;
        }

        return false;
    }

    /**
     * Checks if the provided type is a valid doc type
     * @param string $type
     * @return boolean
     */
    public static function isValidType($type) {
        if (!is_string($type) || !$type) {
            return false;
        }

        if (strpos($type, '|') === false) {
            $types = array($type);
        } else {
            $types = explode('|', $type);
        }

        foreach ($types as $type) {
            if (!self::isValidName($type, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the provided name is a valid code name
     * @param string $name
     * @param boolean $isClassAllowed
     * @return boolean
     */
    public static function isValidName($name, $isClassAllowed = false) {
        if (!is_string($name) || !$name) {
            return false;
        }

        if (is_numeric($name{0})) {
            return false;
        }

        if ($isClassAllowed) {
            if (!preg_match('/^([a-zA-Z0-9_\\\\])*$/', $name)) {
                return false;
            }
        } else {
            if (!preg_match('/^([a-zA-Z0-9_])*$/', $name)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the provided scope is valid
     * @param string $scope
     * @return boolean
     */
    public static function isValidScope($scope) {
        return $scope === self::SCOPE_PUBLIC || $scope === self::SCOPE_PRIVATE || $scope === Code::SCOPE_PROTECTED;
    }

}
