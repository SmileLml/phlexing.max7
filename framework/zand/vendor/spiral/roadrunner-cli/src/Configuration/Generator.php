<?php

namespace Spiral\RoadRunner\Console\Configuration;

use Spiral\RoadRunner\Console\Configuration\Section\Rpc;
use Spiral\RoadRunner\Console\Configuration\Section\SectionInterface;
use Spiral\RoadRunner\Console\Configuration\Section\Version;
use Symfony\Component\Yaml\Yaml;

class Generator
{
    /** @var SectionInterface[] */
    protected $sections = [];

    /** @psalm-var non-empty-array<class-string<SectionInterface>> */
    protected const REQUIRED_SECTIONS = [
        Version::class,
        Rpc::class,
    ];

    /**
     * @param \Spiral\RoadRunner\Console\Configuration\Plugins $plugins
     */
    public function generate($plugins)
    {
        $this->collectSections($plugins->getPlugins());

        return Yaml::dump($this->getContent(), 10);
    }

    protected function getContent()
    {
        $content = [];
        foreach ($this->sections as $section) {
            $content += $section->render();
        }

        return $content;
    }

    /**
     * @param mixed[] $plugins
     */
    protected function collectSections($plugins)
    {
        $sections = \array_merge(self::REQUIRED_SECTIONS, $plugins);

        foreach ($sections as $section) {
            $this->fromSection(new $section());
        }
    }

    /** @psalm-return non-empty-array<SectionInterface>
     * @param \Spiral\RoadRunner\Console\Configuration\Section\SectionInterface $section */
    protected function fromSection($section)
    {
        if (!isset($this->sections[\get_class($section)])) {
            $this->sections[\get_class($section)] = $section;
        }

        foreach ($section->getRequired() as $required) {
            $this->fromSection(new $required());
        }
    }
}
