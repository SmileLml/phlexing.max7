<?php

namespace Spiral\RoadRunner\Console\Command;

use Symfony\Component\Console\Command\Command;
use Spiral\RoadRunner\Console\Repository\ReleaseInterface;
use Spiral\RoadRunner\Console\Repository\ReleasesCollection;
use Spiral\RoadRunner\Console\Repository\RepositoryInterface;
use Spiral\RoadRunner\Version as RoadRunnerVersion;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

class VersionFilterOption extends Option
{
    /**
     * @param Command $command
     * @param string $name
     * @param string $short
     */
    public function __construct($command, $name = 'filter', $short = 'f')
    {
        parent::__construct($command, $name, $short);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDescription()
    {
        return 'Required version of RoadRunner binaries';
    }

    /**
     * {@inheritDoc}
     */
    protected function default()
    {
        return RoadRunnerVersion::constraint();
    }

    /**
     * @param ReleasesCollection $releases
     * @return string
     */
    public function choices($releases)
    {
        $versions = $releases
            ->map(static function (ReleaseInterface $release) : string {
                return $release->getVersion();
            })
            ->toArray()
        ;

        return \implode(', ', \array_unique($versions));
    }

    /**
     * @param InputInterface $input
     * @param StyleInterface $io
     * @param RepositoryInterface $repo
     * @return ReleasesCollection
     */
    public function find($input, $io, $repo)
    {
        $constraint = $this->get($input, $io);

        // All available releases
        $available = $repo->getReleases()
            ->sortByVersion()
        ;

        // With constraints
        $filtered = $available->satisfies($constraint);

        $this->validateNotEmpty($filtered, $available, $constraint);

        return $filtered;
    }

    /**
     * @param ReleasesCollection $filtered
     * @param ReleasesCollection $all
     * @param string $constraint
     */
    private function validateNotEmpty($filtered, $all, $constraint)
    {
        if ($filtered->empty()) {
            $header = 'Could not find any available RoadRunner binary version which meets version criterion (--%s=%s)';
            $header = \sprintf($header, $this->name, $constraint);

            $footer = 'Available: ' . $this->choices($all);

            throw new \UnexpectedValueException(\implode("\n", [$header, $footer]));
        }
    }
}
