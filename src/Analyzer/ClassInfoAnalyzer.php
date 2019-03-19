<?php

/*
 * This file is part of the "default-project" package.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Analyzer;

use Greeflas\StaticAnalyzer\PhpClassInfo;
use ReflectionMethod;
use ReflectionProperty;
use Symfony\Component\Finder\Finder;

/**
 * Class ClassInfoAnalyzer - collects information about class by the name
 */
class ClassInfoAnalyzer
{
    private $fullClassName;
    private $classType = 'default';
    private $classProperties;
    private $classMethods;


    public function __construct($fullClassName)
    {
        $this->fullClassName = $fullClassName;
    }


    /**
     * @return string
     */
    public function getClassType(): string
    {
        return $this->classType;
    }

    /**
     * @return array
     */
    public function getClassProperties(): array
    {
        return $this->classProperties;
    }

    /**
     * @return array
     */
    public function getClassMethods(): array
    {
        return $this->classMethods;
    }


    /**
     * @return object
     */
    public function analyze(): object
    {
        $finder = Finder::create()
            ->in('./src/')
            ->files()
            ->name('/^[A-Z].+\.php$/');


        foreach ($finder as $file) {
            $namespace = PhpClassInfo::getFullClassName($file->getPathname());

            try {
                $reflector = new \ReflectionClass($namespace);
            } catch (\ReflectionException $e) {
                continue;
            }

            if ($reflector->getShortName() == $this->fullClassName) {
                if ($reflector->isAbstract()) {
                    $this->classType = 'abstract';
                } elseif ($reflector->isFinal()) {
                    $this->classType = 'final';
                }

                $this->classProperties['public'] = \count($reflector->getProperties(ReflectionProperty::IS_PUBLIC));
                $this->classProperties['protected'] = \count($reflector->getProperties(ReflectionProperty::IS_PROTECTED));
                $this->classProperties['private'] = \count($reflector->getProperties(ReflectionProperty::IS_PRIVATE));

                $this->classMethods['public'] = \count($reflector->getMethods(ReflectionMethod::IS_PUBLIC));
                $this->classMethods['protected'] = \count($reflector->getMethods(ReflectionMethod::IS_PROTECTED));
                $this->classMethods['private'] = \count($reflector->getMethods(ReflectionMethod::IS_PRIVATE));
            }
        }

        return $this;
    }
}
