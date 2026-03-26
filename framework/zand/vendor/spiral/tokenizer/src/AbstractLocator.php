<?php

namespace Spiral\Tokenizer;

use Psr\Log\LoggerAwareInterface;
use Spiral\Core\Container\InjectableInterface;
use Spiral\Logger\Traits\LoggerTrait;
use Spiral\Tokenizer\Exception\LocatorException;
use Spiral\Tokenizer\Reflection\ReflectionFile;
use Spiral\Tokenizer\Traits\TargetTrait;
use Symfony\Component\Finder\Finder;

/**
 * Base class for Class and Invocation locators.
 */
abstract class AbstractLocator implements InjectableInterface, LoggerAwareInterface
{
    use LoggerTrait;
    use TargetTrait;

    public const INJECTOR = Tokenizer::class;
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;
    /**
     * @readonly
     * @var bool
     */
    protected $debug = false;
    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param bool $debug
     */
    public function __construct($finder, $debug = false)
    {
        $this->finder = $finder;
        $this->debug = $debug;
    }

    /**
     * Available file reflections. Generator.
     *
     * @throws \Exception
     *
     * @return \Generator<int, ReflectionFile, mixed, void>
     */
    protected function availableReflections()
    {
        foreach ($this->finder->getIterator() as $file) {
            $reflection = new ReflectionFile((string)$file);

            if ($reflection->hasIncludes()) {
                // We are not analyzing files which has includes, it's not safe to require such reflections
                $this->getLogger()->warning(
                    \sprintf('File `%s` has includes and excluded from analysis', (string) $file),
                    ['file' => $file]
                );

                continue;
            }

            yield $reflection;
        }
    }

    /**
     * Safely get class reflection, class loading errors will be blocked and reflection will be
     * excluded from analysis.
     *
     * @template T
     * @param class-string<T> $class
     * @return \ReflectionClass<T>
     *
     * @throws LocatorException
     */
    protected function classReflection($class)
    {
        $loader = static function ($class) {
            if ($class === LocatorException::class) {
                return;
            }

            throw new LocatorException(\sprintf("Class '%s' can not be loaded", $class));
        };

        //To suspend class dependency exception
        \spl_autoload_register($loader);

        try {
            //In some cases reflection can thrown an exception if class invalid or can not be loaded,
            //we are going to handle such exception and convert it soft exception
            return new \ReflectionClass($class);
        } catch (\Throwable $e) {
            if ($e instanceof LocatorException && $e->getPrevious() != null) {
                $e = $e->getPrevious();
            }

            $this->getLogger()->error(
                \sprintf(
                    '%s: %s in %s:%s',
                    $class,
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                ),
                ['error' => $e]
            );

            throw new LocatorException($e->getMessage(), (int) $e->getCode(), $e);
        } finally {
            \spl_autoload_unregister($loader);
        }
    }
}
