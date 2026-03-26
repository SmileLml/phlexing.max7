<?php

namespace Spiral\RoadRunner\Console\Repository;

use Composer\Semver\Semver;
use Composer\Semver\VersionParser;
use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Console\Environment\Stability;

abstract class Release implements ReleaseInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $stability;

    /**
     * @var string
     */
    private $version;

    /**
     * @var AssetsCollection
     */
    private $assets;

    /**
     * @var string
     */
    private $repository;

    /**
     * @param string $name
     * @param string $version
     * @param string $repository
     * @param iterable $assets
     */
    public function __construct($name, $version, $repository, $assets = [])
    {
        $this->version = $version;
        $this->repository = $repository;

        $this->name = $this->simplifyReleaseName($name);
        $this->assets = AssetsCollection::create($assets);

        $this->stability = $this->parseStability($version);
    }

    /**
     * @param string $version
     * @return string
     */
    private function parseStability($version)
    {
        return VersionParser::parseStability($version);
    }

    /**
     * @param string $name
     * @return string
     */
    private function simplifyReleaseName($name)
    {
        $version = (new VersionParser())->normalize($name);

        $parts = \explode('-', $version);
        $number = \substr($parts[0], 0, -2);

        return isset($parts[1])
            ? $number . '-' . $parts[1]
            : $number
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getRepositoryName()
    {
        return $this->repository;
    }

    /**
     * {@inheritDoc}
     */
    public function getStability()
    {
        return $this->stability;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * {@inheritDoc}
     * @param string $constraint
     */
    public function satisfies($constraint)
    {
        return Semver::satisfies($this->getName(), $constraint);
    }
}
