<?php


namespace BBIT\Oddin\Utils;

/**
 * Class PropertyAnnotationReader
 * @package BBIT\Oddin\Utils
 */
class PropertyAnnotationReader
{
    const REG = '/@property\s+([^\s\$]+)\s+\$([\w\d]+)/';

    /**
     * @param \ReflectionClass $reflectionClass
     * @return array
     */
    public function getProperties(\ReflectionClass $reflectionClass) {
        $properties = [];

        $docComment = $reflectionClass->getDocComment();
        if ($docComment) {
            $lines = explode(PHP_EOL, $docComment);

            foreach ($lines as $line) {
                $property = $this->getPropertyFromLine($line);
                if ($property) {
                    $properties[$property[1]] = $property[0];
                }
            }

        }

        return $properties;
    }

    /**
     * @param string $line
     * @return null|array
     */
    private function getPropertyFromLine(string $line) {
        preg_match(static::REG, $line, $matches);

        if (count($matches)) {
            return [$matches[1], $matches[2]];
        }

        return null;
    }

}
