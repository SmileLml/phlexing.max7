<?php

namespace Spiral\Tokenizer\Reflection;

use Spiral\Tokenizer\Exception\ReflectionException;

/**
 * ReflectionInvocation used to represent function or static method call found by ReflectionFile.
 * This reflection is very useful for static analysis and mainly used in Translator component to
 * index translation function usages.
 */
final class ReflectionInvocation
{
    /**
     * @readonly
     * @var string
     */
    private $filename;
    /**
     * @readonly
     * @var int
     */
    private $line;
    /**
     * @var class-string
     * @readonly
     */
    private $class;
    /**
     * @readonly
     * @var string
     */
    private $operator;
    /**
     * @readonly
     * @var string
     */
    private $name;
    /**
     * @var ReflectionArgument[]
     * @readonly
     */
    private $arguments;
    /**
     * @readonly
     * @var string
     */
    private $source;
    /**
     * @var int
     * @readonly
     */
    private $level;
    /**
     * New call reflection.
     *
     * @param class-string $class
     * @param ReflectionArgument[] $arguments
     * @param int $level Was a function used inside another function call?
     * @param string $filename
     * @param int $line
     * @param string $operator
     * @param string $name
     * @param string $source
     */
    public function __construct($filename, $line, $class, $operator, $name, $arguments, $source, $level)
    {
        $this->filename = $filename;
        $this->line = $line;
        $this->class = $class;
        $this->operator = $operator;
        $this->name = $name;
        $this->arguments = $arguments;
        $this->source = $source;
        $this->level = $level;
    }

    /**
     * Function usage filename.
     */
    public function getFilename()
    {
        return \str_replace('\\', '/', $this->filename);
    }

    /**
     * Function usage line.
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Parent class.
     *
     * @return class-string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Method operator (:: or ->).
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Function or method name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Call made by class method.
     */
    public function isMethod()
    {
        return !empty($this->class);
    }

    /**
     * Function usage src.
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Count of arguments in call.
     */
    public function countArguments()
    {
        return \count($this->arguments);
    }

    /**
     * All parsed function arguments.
     *
     * @return ReflectionArgument[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Get call argument by it position.
     */
    public function getArgument(int $index)
    {
        if (!isset($this->arguments[$index])) {
            throw new ReflectionException(\sprintf("No such argument with index '%d'", $index));
        }

        return $this->arguments[$index];
    }

    /**
     * Invoking level.
     */
    public function getLevel()
    {
        return $this->level;
    }
}
