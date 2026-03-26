<?php

namespace Spiral\Tokenizer\Config;

use Spiral\Core\InjectableConfig;

/**
 * Tokenizer component configuration.
 *
 * @psalm-type TDirectories = array<array-key, string>
 *
 * @psalm-type TScope = array{
 *     "directories": TDirectories,
 *     "exclude": TDirectories
 * }
 */
final class TokenizerConfig extends InjectableConfig
{
    public const CONFIG = 'tokenizer';

    /**
     * @var array{
     *     cache: array{directory: null, enabled: bool},
     *     debug: bool,
     *     directories: TDirectories,
     *     exclude: TDirectories,
     *     scopes: array<non-empty-string, TScope>
     * }
     */
    protected $config = [
        'cache' => [
            'directory' => null,
            'enabled' => false,
        ],
        'debug' => false,
        'directories' => [],
        'exclude' => [],
        'scopes' => [],
    ];

    public function isDebug()
    {
        return (bool)($this->config['debug'] ?? false);
    }

    /**
     * @return TDirectories
     */
    public function getDirectories()
    {
        return $this->config['directories'] ?? [(string)\getcwd()];
    }

    /**
     * @return TDirectories
     */
    public function getExcludes()
    {
        return $this->config['exclude'] ?? ['vendor', 'tests'];
    }

    /**
     * @return TScope
     * @param string $scope
     */
    public function getScope($scope)
    {
        $directories = $this->config['scopes'][$scope]['directories'] ?? $this->getDirectories();
        $excludes = $this->config['scopes'][$scope]['exclude'] ?? $this->getExcludes();

        return [
            'directories' => $directories,
            'exclude' => $excludes,
        ];
    }

    /**
     * Check if tokenizer listeners cache is enabled.
     */
    public function isCacheEnabled()
    {
        return (bool)($this->config['cache']['enabled'] ?? false);
    }

    /**
     * Get tokenizer listeners cache directory.
     */
    public function getCacheDirectory()
    {
        $dir = $this->config['cache']['directory'] ?? null;
        \assert(\is_string($dir) || $dir === null, 'Invalid cache directory.');

        return $dir;
    }
}
