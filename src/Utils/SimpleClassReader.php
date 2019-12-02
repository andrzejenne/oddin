<?php


namespace BigBIT\Oddin\Utils;

/**
 * Class PropertyAnnotationReader
 * @package BigBIT\Oddin\Utils
 */
class SimpleClassReader
{
    const DEPRECATED_PROPERTY_REG = '/@property\s+([^\s\$]+)\s+\$([\w\d]+)/',
        USE_REG = '/use\s+([^\s;]+)(?:\s+as\s+([^;]+))?/';

    /** @var ClassMapResolver */
    private ClassMapResolver $classMapResolver;

    /** @var bool */
    private bool $isDeprecatedAllowed = false;

    /**
     * ClassReader constructor.
     * @param ClassMapResolver $classMapResolver
     */
    public function __construct(ClassMapResolver $classMapResolver)
    {
        $this->classMapResolver = $classMapResolver;
    }

    /**
     * @return $this
     */
    public function allowDeprecated() {
        $this->isDeprecatedAllowed = true;

        return $this;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @return array
     * @throws \Exception
     */
    public function getProperties(\ReflectionClass $reflectionClass)
    {
        $properties = $this->getReflectionClassProperties($reflectionClass);
        while ($parentClassReflection = $reflectionClass->getParentClass()) {
            $properties += $this->getReflectionClassProperties($parentClassReflection);
            $reflectionClass = $parentClassReflection;
        }

        return $properties;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @return mixed
     * @throws \Exception
     */
    private function getReflectionClassProperties(\ReflectionClass $reflectionClass) {
        $properties = [];
        $statements = $this->getUseStatements($reflectionClass->name);
        $namespace = $reflectionClass->getNamespaceName();

        if ($this->isDeprecatedAllowed) {
            $docComment = $reflectionClass->getDocComment();
            if ($docComment) {
                $lines = explode(PHP_EOL, $docComment);

                foreach ($lines as $line) {
                    $property = $this->getPropertyFromLine($line);
                    if ($property) {
                        $properties[$property[1]] = $this->getAnnotationPropertyClassWithNameSpace($property,
                            $statements, $namespace);
                    }
                }

            }
        }

        foreach ($reflectionClass->getProperties() as $property) {
            $properties[$property->getName()] = $this->getReflectionPropertyClassWithNameSpace($property, $statements, $namespace);
        }

        return $properties;
    }

    /**
     * @param string $line
     * @return null|array
     * @deprecated
     */
    private function getPropertyFromLine(string $line): ?array
    {
        preg_match(static::DEPRECATED_PROPERTY_REG, $line, $matches);

        if (count($matches)) {
            return [$matches[1], $matches[2]];
        }

        return null;
    }

    /**
     * @param string $className
     * @return array
     * @throws \Exception
     */
    private function getUseStatements(string $className): array
    {
        $classPath = $this->classMapResolver->getClassPath($className);

        if (file_exists($classPath)) {
            $content = @file_get_contents($classPath);

            if ($content) {
                return $this->getUseStatementsFromContent($content);
            }
        }

        return [];
    }

    /**
     * @param string $content
     * @return array
     */
    private function getUseStatementsFromContent(string $content): array
    {
        $lines = explode(PHP_EOL, $content);

        $statements = [];

        foreach ($lines as $line) {
            // @todo - break on class ?
            $statement = $this->getUseStatementFromLine($line);
            if ($statement) {
                $statements[$statement[1]] = $statement[0];
            }
        }

        return $statements;
    }

    /**
     * @param string $line
     * @return array|null
     */
    private function getUseStatementFromLine(string $line): ?array
    {
        preg_match(static::USE_REG, $line, $matches);

        if (count($matches)) {
            if (isset($matches[2])) {
                $alias = $matches[2];
            } else {
                $alias = explode('\\', $matches[1]);
                $alias = end($alias);
            }

            return [$matches[1], $alias];
        }

        return null;
    }

    /**
     * @param array $property
     * @param array $statements
     * @param string $namespace
     * @return string
     * @throws \Exception
     * @deprecated
     */
    private function getAnnotationPropertyClassWithNameSpace(array $property, array $statements, string $namespace) {
        return $this->getPropertyClassWithNamesSpace($property[0], $statements, $namespace);
    }

    /**
     * @param \ReflectionProperty $property
     * @param array $statements
     * @param string $namespace
     * @return string
     * @throws \Exception
     */
    private function getReflectionPropertyClassWithNameSpace(\ReflectionProperty $property, array $statements, string $namespace) : string
    {
        $reflectionType = $property->getType();
        if ($reflectionType instanceof \ReflectionNamedType) {
            $type = $reflectionType->getName();
        }
        else {
            throw new \Exception("Cannot resolve {$property->getName()} property type");
        }

        return $this->getPropertyClassWithNamesSpace($type, $statements, $namespace);
    }

    /**
     * @param string $type
     * @param array $statements
     * @param string $namespace
     * @return string
     * @throws \Exception
     */
    private function getPropertyClassWithNamesSpace(string $type, array $statements, string $namespace): string
    {
        if (isset($statements[$type])) {
            return $statements[$type];
        } else {
            if ($this->classMapResolver->getClassPath($type)) {
                return $type;
            } else {
                return $namespace . '\\' . $type;
            }
        }
    }
}
