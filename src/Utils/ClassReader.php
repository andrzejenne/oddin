<?php


namespace BigBIT\Oddin\Utils;

/**
 * Class PropertyAnnotationReader
 * @package BigBIT\Oddin\Utils
 */
class ClassReader
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
                    if (isset($statements[$property[0]])) {
                        $cls = $statements[$property[0]];
                    } else {
                        if ($this->classMapResolver->getClassPath($property[0])) {
                            $cls = $property[0];
                        } else {
                            $cls = $namespace . '\\' . $property[0];
                        }
                    }
                    $properties[$property[1]] = $cls;
                }
            }

        }

        return $properties;
    }

    /**
     * @param string $line
     * @param string $namespace
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
    private function getUseStatements(string $className)
    {
        $classPath = $this->classMapResolver->getClassPath($className);
        $content = file_get_contents($classPath);

        return $this->getUseStatementsFromContent($content);
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

}
