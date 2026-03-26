<?php

namespace Spiral\RoadRunner\Console\Repository;

/**
 * @template-extends Collection<AssetInterface>
 */
final class AssetsCollection extends Collection
{
    /**
     * @return $this
     */
    public function onlyRoadrunner()
    {
        return $this->filter(static function (AssetInterface $asset) : bool {
            return strncmp($asset->getName(), 'roadrunner', strlen('roadrunner')) === 0;
        }
        );
    }

    /**
     * @return $this
     */
    public function exceptDebPackages()
    {
        return $this->except(static function (AssetInterface $asset) : bool {
            return substr_compare(\strtolower($asset->getName()), '.deb', -strlen('.deb')) === 0;
        }
        );
    }

    /**
     * @param string $arch
     * @return $this
     */
    public function whereArchitecture($arch)
    {
        return $this->filter(static function (AssetInterface $asset) use ($arch) : bool {
            return strpos($asset->getName(), '-' . \strtolower($arch) . '.') !== false;
        }
        );
    }

    /**
     * @param string $os
     * @return $this
     */
    public function whereOperatingSystem($os)
    {
        return $this->filter(static function (AssetInterface $asset) use ($os) : bool {
            return strpos($asset->getName(), '-' . \strtolower($os) . '-') !== false;
        }
        );
    }
}
