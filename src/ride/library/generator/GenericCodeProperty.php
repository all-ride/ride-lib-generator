<?php

namespace ride\library\generator;

use ride\library\generator\exception\GeneratorException;

/**
 * Generic implementaion of the definition of a property
 */
class GenericCodeProperty extends GenericCodeVariable implements CodeProperty {

    /**
     * Scope of the property
     * @var string
     */
    protected $scope;

    /**
     * Constructs a new property
     * @param string $name Name of the property
     * @param string $type Type of the property
     * @param string $scope Scope of the property, defaults to public
     * @return null
     */
    public function __construct($name, $type, $scope = null) {
        parent::__construct($name, $type);

        if ($scope) {
            $this->setScope($scope);
        }
    }

    /**
     * Sets the scope of this property
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
     * Gets the scope of the property
     * @return string
     */
    public function getScope() {
        return $this->scope ? $this->scope : Code::SCOPE_PUBLIC;
    }

}
