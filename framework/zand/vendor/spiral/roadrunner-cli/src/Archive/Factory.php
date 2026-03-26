<?php

namespace Spiral\RoadRunner\Console\Archive;

use Spiral\RoadRunner\Console\Repository\AssetInterface;

/**
 * @psalm-import-type ArchiveMatcher from FactoryInterface
 */
class Factory implements FactoryInterface
{
    /**
     * @var array<ArchiveMatcher>
     */
    private $matchers = [];

    /**
     * FactoryTrait constructor.
     */
    public function __construct()
    {
        $this->bootDefaultMatchers();
    }

    /**
     * @return void
     */
    private function bootDefaultMatchers()
    {
        $this->extend($this->matcher('zip',
            static function (\SplFileInfo $info) : ArchiveInterface {
                return new ZipPharArchive($info);
            }
        ));

        $this->extend($this->matcher('tar.gz',
            static function (\SplFileInfo $info) : ArchiveInterface {
                return new TarPharArchive($info);
            }
        ));

        $this->extend($this->matcher('phar',
            static function (\SplFileInfo $info) : ArchiveInterface {
                return new PharArchive($info);
            }
        ));
    }

    /**
     * @param string $extension
     * @param \Closure $then
     * @return ArchiveMatcher
     */
    private function matcher($extension, $then)
    {
        return static function (\SplFileInfo $info) use ($extension, $then) : ?ArchiveInterface {
            return substr_compare(\strtolower($info->getFilename()), '.' . $extension, -strlen('.' . $extension)) === 0 ? $then($info) : null;
        }
        ;
    }

    /**
     * {@inheritDoc}
     * @return $this
     * @param \Closure $matcher
     */
    public function extend($matcher)
    {
        \array_unshift($this->matchers, $matcher);

        return $this;
    }

    /**
     * @param \SplFileInfo $file
     * @return ArchiveInterface
     */
    public function create($file)
    {
        $errors = [];

        foreach ($this->matchers as $matcher) {
            try {
                if ($archive = $matcher($file)) {
                    return $archive;
                }
            } catch (\Throwable $e) {
                $errors[] = '  - ' . $e->getMessage();
                continue;
            }
        }

        $error = \sprintf('Can not open the archive "%s":%s', $file->getFilename(), \PHP_EOL) .
            \implode(\PHP_EOL, $errors)
        ;

        throw new \InvalidArgumentException($error);
    }

    /**
     * {@inheritDoc}
     * @param \Spiral\RoadRunner\Console\Repository\AssetInterface $asset
     * @param \Closure|null $progress
     * @param string|null $temp
     */
    public function fromAsset($asset, $progress = null, $temp = null)
    {
        $temp = $this->getTempDirectory($temp) . '/' . $asset->getName();

        $file = new \SplFileObject($temp, 'wb+');

        try {
            foreach ($asset->download($progress) as $chunk) {
                $file->fwrite($chunk);
            }
        } catch (\Throwable $e) {
            @\unlink($temp);

            throw $e;
        }

        return $this->create($file);
    }

    /**
     * @param string|null $temp
     * @return string
     */
    private function getTempDirectory($temp)
    {
        if ($temp) {
            if (! \is_dir($temp) || ! \is_writable($temp)) {
                throw new \LogicException(\sprintf('Directory "%s" is not writeable', $temp));
            }

            return $temp;
        }

        return \sys_get_temp_dir();
    }
}
