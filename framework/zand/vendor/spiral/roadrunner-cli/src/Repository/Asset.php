<?php

namespace Spiral\RoadRunner\Console\Repository;

abstract class Asset implements AssetInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @param string $name
     * @param string $uri
     */
    public function __construct($name, $uri)
    {
        $this->name = $name;
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
