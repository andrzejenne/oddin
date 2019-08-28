<?php


namespace BigBIT\Oddin\Utils;

/**
 * Class PropertyAnnotationReader
 * @package BigBIT\Oddin\Utils
 */
class SimpleClassReader
{
    const PROPERTY_REG = '/@property\s+([^\s\$]+)\s+\$([\w\d]+)/',
        USE_REG = '/use\s+([^\s;]+)(?:\s+as\s+([^;]+))?/';

    /** @var ClassMapResolver */
    private $classMapResolver;

    /**
     * ClassReader constructor.
     * @param ClassMapResolver $classMapResolver
     */
    public function __construct(ClassMapResolver $classMapResolver)
    {
        $this->classMapResolver = $classMapResolver;
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

        $docComment = $reflectionClass->getDocComment();
        if ($docComment) {
            $lines = explode(PHP_EOL, $docComment);

            foreach ($lines as $line) {
                $property = $this->getPropertyFromLine($line);
                if ($property) {
                    $properties[$property[1]] = $this->getPropertyClassWithNameSpace($property, $statements, $namespace);
                }
            }

        }

        return $properties;
    }

    /**
     * @param string $line
     * @return null|array
     */
    private function getPropertyFromLine(string $line): ?array
    {
        preg_match(static::PROPERTY_REG, $line, $matches);

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
     * @return mixed|string
     * @throws \Exception
     */
    private function getPropertyClassWithNameSpace(array $property, array $statements, string $namespace) {
        if (isset($statements[$property[0]])) {
            return $statements[$property[0]];
        } else {
            if ($this->classMapResolver->getClassPath($property[0])) {
                return $property[0];
            } else {
                return $namespace . '\\' . $property[0];
            }
        }
    }
}
