<?php

namespace ride\library\generator;

/**
 * Generic implementation of the code generator interface
 */
class GenericCodeGenerator implements CodeGenerator {

    /**
     * Flag to see if tabs should be used for indentation
     * @var boolean
     */
    protected $useTabs = false;

    /**
     * Number of spaces or tabs to use for indentation
     * @var integer
     */
    protected $indentation = 4;

    /**
     * Resolved use statements
     * @var array
     */
    protected $use;

    /**
     * Creates a new class
     * @param string $name Name of the class
     * @param string $extends Name of the extended class
     * @param array $implements Names of the implemented classes
     * @param boolean $isInterface Flag to see if the class is an interface
     * @param boolean $isAbstract Flag to see if the class is abstract
     * @return GenericCodeClass
     */
    public function createClass($name, $extends = null, array $implements = array(), $isInterface = false, $isAbstract = false) {
        return new GenericCodeClass($name, $extends, $implements, $isInterface, $isAbstract);
    }

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
    public function createMethod($name, array $arguments = array(), $source = null, $scope = null, $isAbstract = false, $isStatic = false) {
        return new GenericCodeMethod($name, $arguments, $source, $scope, $isAbstract, $isStatic);
    }

    /**
     * Creates a new property
     * @param string $name Name of the property
     * @param string $type Type of the property
     * @param string $scope Scope of the property, defaults to public
     * @return null
     */
    public function createProperty($name, $type, $scope = null) {
        return new GenericCodeProperty($name, $type, $scope);
    }

    /**
     * Constructs a new variable
     * @param string $name Name of the variable
     * @param string $type Type of the variable
     * @return GenericCodeVariable
     */
    public function createVariable($name, $type) {
        return new GenericCodeVariable($name, $type);
    }

    /**
     * Generates the source for the provided class definition
     * @param CodeClass $class
     * @return string Source of the class
     */
    public function generateClass(CodeClass $class) {
        $namespace = null;
        $name = null;
        Code::resolveClassName($class->getName(), $namespace, $name);

        $this->use = $class->getUse();

        $methods = $this->generateMethods($class->getMethods());
        $properties = $this->generateProperties($class->getProperties());
        $constants = $this->generateConstants($class->getConstants());
        $header = $this->generateHeader($class, $name);
        $use = $this->generateUse($namespace);

        $source = "<?php\n\n";
        if ($namespace) {
            $source .= "namespace " . $namespace . ";\n\n";
        }

        if ($use) {
            $source .= $use . "\n";
        }

        $source .= $header . "\n\n";

        if ($constants) {
            $source .= $constants;
        }

        if ($properties) {
            $source .= $properties;
        }

        if ($methods) {
            $source .= $methods;
        }

        $source .= "}\n";

        return $source;
    }

    protected function generateUse($currentNamespace) {
        $source = '';

        ksort($this->use);

        foreach ($this->use as $class => $alias) {
            $namespace = null;
            $name = null;

            Code::resolveClassName($class, $namespace, $name);

            if ($namespace === $currentNamespace && $alias === null) {
                continue;
            }

            $source .= 'use ' . (!strpos($class, '\\') ? '\\' : '') . $class;
            if ($alias && $alias != $name) {
                $source .= ' as ' . $alias;
            }

            $source .= ";\n";
        }

        return $source;
    }

    protected function generateHeader(CodeClass $class, $name) {
        $source = '';

        $description = $class->getDescription();
        if ($description) {
            $source .= "/**\n";
            $source .= " * " . str_replace("\n", "\n * ", $description) . "\n";
            $source .= " */\n";
        }

        if ($class->isInterface()) {
            $source .= 'interface ';
        } elseif ($class->isAbstract()) {
            $source .= 'abstract class ';
        } else {
            $source .= 'class ';
        }

        $source .= $name . ' ';

        $extends = $class->getExtends();
        if ($extends) {
            $source .= 'extends ' . $this->useClass($extends) . ' ';
        }

        $implements = $class->getImplements();
        if ($implements) {
            $useImplements = array();

            foreach ($implements as $implementedClass => $null) {
                $useImplements[$this->useClass($implementedClass)] = $null;
            }

            $source .= 'implements ' . implode(', ', array_keys($useImplements)) . ' ';
        }
        $source .= '{';

        return $source;
    }

    protected function generateConstants(array $constants) {
        if (!$constants) {
            return null;
        }

        $source = '';

        foreach ($constants as $constant) {
            $source .= $this->generateConstant($constant) . "\n\n";
        }

        return $source;
    }

    protected function generateConstant(CodeVariable $constant, $indent = 1) {
        $source = '';

        $source .= 'const ' . $constant->getName();
        $source .= ' = ' . var_export($property->getDefaultValue(), true);
        $source .= ';';

        return $this->indent($source, $indent);
    }

    protected function generateProperties(array $properties) {
        if (!$properties) {
            return null;
        }

        $source = '';

        foreach ($properties as $property) {
            $source .= $this->generateProperty($property) . "\n\n";
        }

        return $source;
    }

    protected function generateProperty(CodeProperty $property, $indent = 1) {
        $description = $property->getDescription();
        $type = $property->getType();
        $source = '';

        if ($description || $type) {
            $source .= "/**\n";
            if ($description) {
                $source .= " * " . $description . "\n";
            }
            if ($type) {
                $source .= " * @var " . (strpos($type, '\\') && $type{0} != '\\' ? '\\' : '') . $type . "\n";
            }
            $source .= " */\n";
        }

        $source .= $property->getScope() . ' $' . $property->getName();
        if ($property->hasDefaultValue()) {
            $source .= ' = ' . $this->generateDefaultValue($property->getDefaultValue());
        }
        $source .= ';';

        return $this->indent($source, $indent);
    }

    protected function generateMethods(array $methods) {
        if (!$methods) {
            return null;
        }

        $source = '';

        foreach ($methods as $method) {
            $source .= $this->generateMethod($method) . "\n\n";
        }

        return $source;
    }

    protected function generateMethod(CodeMethod $method, $indent = 1) {
        $doc = '';

        $description = $method->getDescription();
        if ($description) {
            $doc .= $description . "\n";
        }

        $arguments = $method->getArguments();
        foreach ($arguments as $argumentName => $argument) {
            $argumentString = '';

            $type = $argument->getType();
            if (!Code::isUndefinableType($type)) {
                $argumentString .= $this->useClass($type) . ' ';
            }

            $argumentString .= '$' . $argument->getName();

            if ($argument->hasDefaultValue()) {
                $argumentString .= ' = ' . $this->generateDefaultValue($argument->getDefaultValue());
            }

            $arguments[$argumentName] = $argumentString;
            $doc .= '@param ' . (strpos($type, '\\') && $type{0} != '\\' ? '\\' : '') . $type . ' $' . $argument->getName() . ' ' . $argument->getDescription() . "\n";
        }
        $arguments = implode(', ', $arguments);

        $returnValue = $method->getReturnValue();
        if (!$returnValue) {
            $doc .= "@return null\n";
        } else {
            $type = $returnValue->getType();
            $doc .= '@return ' . (strpos($type, '\\') && $type{0} != '\\' ? '\\' : '') . $type . ' ' . $returnValue->getDescription() . "\n";
        }

        $header = '';
        if ($doc) {
            $header .= "/**\n * " . str_replace("\n", "\n * ", trim($doc)) . "\n */\n";
        }
        if ($method->isAbstract()) {
            $header .= 'abstract ';
        }
        $header .= $method->getScope() . ' ';
        if ($method->isStatic()) {
            $header .= 'static ';
        }
        $header .= 'function ' . $method->getName() . '(';
        $header .= $arguments;
        $header .= ') {';

        $source = $this->indent($header, $indent) . "\n";
        $source .= $this->indent($method->getSource(), $indent + 1) . "\n";
        $source .= $this->indent('}', $indent);

        $use = $method->getUse();
        foreach ($use as $class => $alias) {
            $this->useClass($class, $alias);
        }

        return $source;
    }

    protected function generateDefaultValue($value) {
        if ($value === array()) {
            return 'array()';
        } else {
            return var_export($value, true);
        }
    }

    protected function useClass($class, $alias = null) {
        if ($class == 'array') {
            return $class;
        }

        if (!isset($this->use[$class])) {
            $this->use[$class] = $alias;
        } elseif ($alias && $this->use[$class] != $alias) {
            throw new GeneratorException('Could not generate method: unable to use 2 different aliasses for the same class use import');
        }

        if ($alias === null) {
            $namespace = null;
            $name = null;

            Code::resolveClassName($class, $namespace, $name);

            $alias = $name;
        }

        return $alias;
    }

    /**
     * Indents the provided string
     * @param string $string String to indent
     * @param integer $times Number of times to indent
     * @return string Indented string
     */
    protected function indent($string, $times) {
        $indentation = str_repeat($this->useTabs ? "\t" : " ", $this->indentation);

        $lines = explode("\n", $string);
        foreach ($lines as $index => $line) {
            $lines[$index] = str_repeat($indentation, $times) . $line;
        }

        return implode("\n", $lines);
    }

}
