<?php

namespace Spiral\RoadRunner\Console\Repository;

use Spiral\RoadRunner\Console\Environment\Stability;

/**
 * @template-extends Collection<ReleaseInterface>
 * @psalm-import-type StabilityType from Stability
 */
final class ReleasesCollection extends Collection
{
    /**
     * @param string ...$constraints
     * @return $this
     */
    public function satisfies(...$constraints)
    {
        $result = $this;

        foreach ($this->constraints($constraints) as $constraint) {
            $result = $result->filter(static function (ReleaseInterface $r) use ($constraint) : bool {
                return $r->satisfies($constraint);
            });
        }

        return $result;
    }

    /**
     * @param string ...$constraints
     * @return $this
     */
    public function notSatisfies(...$constraints)
    {
        $result = $this;

        foreach ($this->constraints($constraints) as $constraint) {
            $result = $result->except(static function (ReleaseInterface $r) use ($constraint) : bool {
                return $r->satisfies($constraint);
            });
        }

        return $result;
    }

    /**
     * @param array<string> $constraints
     * @return array<string>
     */
    private function constraints($constraints)
    {
        $result = [];

        foreach ($constraints as $constraint) {
            foreach (\explode('|', $constraint) as $expression) {
                $result[] = $expression;
            }
        }

        return \array_unique(
            \array_filter(
                \array_map('\\trim', $result)
            )
        );
    }

    /**
     * @return $this
     */
    public function withAssets()
    {
        return $this->filter(static function (ReleaseInterface $r) : bool {
            return ! $r->getAssets()
                ->empty();
        }
        );
    }

    /**
     * @return $this
     */
    public function sortByVersion()
    {
        $result = $this->items;

        $sort = function (ReleaseInterface $a, ReleaseInterface $b): int {
            return \version_compare($this->comparisonVersionString($b), $this->comparisonVersionString($a));
        };

        \uasort($result, $sort);

        return new self($result);
    }

    /**
     * @param ReleaseInterface $release
     * @return string
     */
    private function comparisonVersionString($release)
    {
        $stability = $release->getStability();
        $weight = Stability::toInt($stability);

        return \str_replace('-' . $stability, '.' . $weight . '.', $release->getVersion());
    }

    /**
     * @return $this
     */
    public function stable()
    {
        return $this->stability(Stability::STABILITY_STABLE);
    }

    /**
     * @param string $stability
     * @return $this
     */
    public function stability($stability)
    {
        $filter = static function (ReleaseInterface $rel) use ($stability) : bool {
            return $rel->getStability() === $stability;
        };

        return $this->filter($filter);
    }

    /**
     * @param string $stability
     * @return $this
     */
    public function minimumStability($stability)
    {
        $weight = Stability::toInt($stability);

        return $this->filter(function (ReleaseInterface $release) use ($weight): bool {
            return Stability::toInt($release->getStability()) >= $weight;
        });
    }
}
