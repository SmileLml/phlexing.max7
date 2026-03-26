<?php

namespace Spiral\Core\Internal\Tracer;

use Spiral\Core\Exception\Traits\ClosureRendererTrait;

/**
 * @internal
 */
final class Trace
{
    use ClosureRendererTrait;
    private const ARRAY_MAX_LEVEL = 3;
    /**
     * @readonly
     * @var string|null
     */
    public $alias;
    /**
     * @var mixed[]
     */
    public $info = [];
    /**
     * @param mixed[] $info
     */
    public function __construct($info = [])
    {
        $this->info = $info;
        $this->alias = $info['alias'] ?? null;
    }
    public function __toString()
    {
        $info = [];
        foreach ($this->info as $key => $item) {
            $info[] = "$key: {$this->stringifyValue($item)}";
        }
        return \implode("\n", $info);
    }
    /**
     * @param mixed $item
     */
    private function stringifyValue($item)
    {
        switch (true) {
            case \is_string($item):
                return "'$item'";
            case \is_scalar($item):
                return \var_export($item, true);
            case $item instanceof \Closure:
                return $this->renderClosureSignature(new \ReflectionFunction($item));
            case $item instanceof \ReflectionFunctionAbstract:
                return $this->renderClosureSignature($item);
            case $item instanceof \UnitEnum:
                return get_class($item) . "::$item->name";
            case \is_object($item):
                return 'instance of ' . get_class($item);
            case \is_array($item):
                return $this->renderArray($item);
            default:
                return \get_debug_type($item);
        }
    }
    /**
     * @param mixed[] $array
     * @param int $level
     */
    private function renderArray($array, $level = 0)
    {
        if ($array === []) {
            return '[]';
        }
        if ($level >= self::ARRAY_MAX_LEVEL) {
            return 'array';
        }
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = \sprintf('%s: %s', $key, \is_array($value)
                ? $this->renderArray($value, $level + 1)
                : $this->stringifyValue($value));
        }

        $pad = \str_repeat('  ', $level);
        return "[\n  $pad" . \implode(",\n  $pad", $result) . "\n$pad]";
    }
}
