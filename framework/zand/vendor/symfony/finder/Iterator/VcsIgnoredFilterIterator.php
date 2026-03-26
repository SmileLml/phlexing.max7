<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Gitignore;

/**
 * @extends \FilterIterator<string, \SplFileInfo>
 */
final class VcsIgnoredFilterIterator extends \FilterIterator
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var array<string, array{0: string, 1: string}|null>
     */
    private $gitignoreFilesCache = [];

    /**
     * @var array<string, bool>
     */
    private $ignoredPathsCache = [];

    /**
     * @param \Iterator<string, \SplFileInfo> $iterator
     * @param string $baseDir
     */
    public function __construct($iterator, $baseDir)
    {
        $this->baseDir = $this->normalizePath($baseDir);

        foreach ($this->parentDirectoriesUpwards($this->baseDir) as $parentDirectory) {
            if (@is_dir("{$parentDirectory}/.git")) {
                $this->baseDir = $parentDirectory;
                break;
            }
        }

        parent::__construct($iterator);
    }

    public function accept()
    {
        $file = $this->current();

        $fileRealPath = $this->normalizePath($file->getRealPath());

        return !$this->isIgnored($fileRealPath);
    }

    /**
     * @param string $fileRealPath
     */
    private function isIgnored($fileRealPath)
    {
        if (is_dir($fileRealPath) && substr_compare($fileRealPath, '/', -strlen('/')) !== 0) {
            $fileRealPath .= '/';
        }

        if (isset($this->ignoredPathsCache[$fileRealPath])) {
            return $this->ignoredPathsCache[$fileRealPath];
        }

        $ignored = false;

        foreach ($this->parentDirectoriesDownwards($fileRealPath) as $parentDirectory) {
            if ($this->isIgnored($parentDirectory)) {
                // rules in ignored directories are ignored, no need to check further.
                break;
            }

            $fileRelativePath = substr($fileRealPath, \strlen($parentDirectory) + 1);

            if (null === $regexps = $this->readGitignoreFile("{$parentDirectory}/.gitignore")) {
                continue;
            }

            [$exclusionRegex, $inclusionRegex] = $regexps;

            if (preg_match($exclusionRegex, $fileRelativePath)) {
                $ignored = true;

                continue;
            }

            if (preg_match($inclusionRegex, $fileRelativePath)) {
                $ignored = false;
            }
        }

        return $this->ignoredPathsCache[$fileRealPath] = $ignored;
    }

    /**
     * @return list<string>
     * @param string $from
     */
    private function parentDirectoriesUpwards($from)
    {
        $parentDirectories = [];

        $parentDirectory = $from;

        while (true) {
            $newParentDirectory = \dirname($parentDirectory);

            // dirname('/') = '/'
            if ($newParentDirectory === $parentDirectory) {
                break;
            }

            $parentDirectories[] = $parentDirectory = $newParentDirectory;
        }

        return $parentDirectories;
    }

    /**
     * @param string $from
     * @param string $upTo
     */
    private function parentDirectoriesUpTo($from, $upTo)
    {
        return array_filter(
            $this->parentDirectoriesUpwards($from),
            static function (string $directory) use ($upTo) : bool {
                return strncmp($directory, $upTo, strlen($upTo)) === 0;
            }
        );
    }

    /**
     * @return list<string>
     * @param string $fileRealPath
     */
    private function parentDirectoriesDownwards($fileRealPath)
    {
        return array_reverse(
            $this->parentDirectoriesUpTo($fileRealPath, $this->baseDir)
        );
    }

    /**
     * @return array{0: string, 1: string}|null
     * @param string $path
     */
    private function readGitignoreFile($path)
    {
        if (\array_key_exists($path, $this->gitignoreFilesCache)) {
            return $this->gitignoreFilesCache[$path];
        }

        if (!file_exists($path)) {
            return $this->gitignoreFilesCache[$path] = null;
        }

        if (!is_file($path) || !is_readable($path)) {
            throw new \RuntimeException("The \"ignoreVCSIgnored\" option cannot be used by the Finder as the \"{$path}\" file is not readable.");
        }

        $gitignoreFileContent = file_get_contents($path);

        return $this->gitignoreFilesCache[$path] = [
            Gitignore::toRegex($gitignoreFileContent),
            Gitignore::toRegexMatchingNegatedPatterns($gitignoreFileContent),
        ];
    }

    /**
     * @param string $path
     */
    private function normalizePath($path)
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            return str_replace('\\', '/', $path);
        }

        return $path;
    }
}
