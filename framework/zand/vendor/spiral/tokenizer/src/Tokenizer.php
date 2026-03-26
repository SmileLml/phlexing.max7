<?php

namespace Spiral\Tokenizer;

use Spiral\Core\Container\SingletonInterface;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Symfony\Component\Finder\Finder;

/**
 * Manages automatic container injections of class and invocation locators.
 */
final class Tokenizer implements SingletonInterface
{
    /**
     * Token array constants.
     */
    public const TYPE = 0;
    public const CODE = 1;
    public const LINE = 2;
    /**
     * @readonly
     * @var \Spiral\Tokenizer\Config\TokenizerConfig
     */
    private $config;

    /**
     * @param \Spiral\Tokenizer\Config\TokenizerConfig $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get pre-configured class locator for specific scope.
     * @param string $scope
     */
    public function scopedClassLocator($scope)
    {
        $dirs = $this->config->getScope($scope);

        return $this->classLocator($dirs['directories'], $dirs['exclude']);
    }

    /**
     * Get pre-configured class locator.
     * @param mixed[] $directories
     * @param mixed[] $exclude
     */
    public function classLocator(
        $directories = [],
        $exclude = []
    ) {
        return new ClassLocator($this->makeFinder($directories, $exclude), $this->config->isDebug());
    }

    /**
     * Get pre-configured invocation locator.
     * @param mixed[] $directories
     * @param mixed[] $exclude
     */
    public function invocationLocator(
        $directories = [],
        $exclude = []
    ) {
        return new InvocationLocator($this->makeFinder($directories, $exclude), $this->config->isDebug());
    }

    /**
     * Get all tokes for specific file.
     * @param string $filename
     */
    public static function getTokens($filename)
    {
        $tokens = \token_get_all(\file_get_contents($filename));

        $line = 0;
        foreach ($tokens as &$token) {
            if (isset($token[self::LINE])) {
                $line = $token[self::LINE];
            }

            if (!\is_array($token)) {
                $token = [$token, $token, $line];
            }

            unset($token);
        }

        return $tokens;
    }

    /**
     * @param array $directories Overwrites default config values.
     * @param array $exclude     Overwrites default config values.
     */
    private function makeFinder($directories = [], $exclude = [])
    {
        $finder = new Finder();

        if (empty($directories)) {
            $directories = $this->config->getDirectories();
        }

        if (empty($exclude)) {
            $exclude = $this->config->getExcludes();
        }

        return $finder->files()->in($directories)->exclude($exclude)->name('*.php');
    }
}
