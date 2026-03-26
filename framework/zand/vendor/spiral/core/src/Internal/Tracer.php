<?php

namespace Spiral\Core\Internal;

use Spiral\Core\Internal\Tracer\Trace;

/**
 * @internal
 */
final class Tracer
{
    /**
     * Trace blocks
     *
     * @var Trace[][]
     */
    private $traces = [];
    public function __toString()
    {
        return $this->traces === [] ? '' : "Container trace list:\n" . $this->renderTraceList($this->traces);
    }
    /**
     * @param string $header Message before stack list
     */
    public function combineTraceMessage($header)
    {
        return "$header\n$this";
    }
    /**
     * @param mixed ...$details
     * @param bool $nextLevel
     */
    public function push($nextLevel, ...$details)
    {
        $trace = $details === [] ? null : new Trace($details);
        if ($nextLevel || $this->traces === []) {
            $this->traces[] = $trace === null ? [] : [$trace];
        } elseif ($trace !== null) {
            end($this->traces);
            $this->traces[key($this->traces)][] = $trace;
        }
    }
    /**
     * @param bool $previousLevel
     */
    public function pop($previousLevel = false)
    {
        if ($this->traces === []) {
            return;
        }
        if ($previousLevel) {
            \array_pop($this->traces);
            return;
        }
        end($this->traces);
        $key = key($this->traces);
        $list = &$this->traces[$key];
        \array_pop($list);
    }
    public function getRootAlias()
    {
        return $this->traces[0][0]->alias ?? '';
    }
    /**
     * @param Trace[][] $blocks
     */
    private function renderTraceList($blocks)
    {
        $result = [];
        $i = 0;
        foreach ($blocks as $block) {
            \array_push($result, ...$this->blockToStringList($block, $i++));
        }
        return \implode("\n", $result);
    }
    /**
     * @param Trace[] $items
     * @param int<0, max> $level
     *
     * @return string[]
     */
    private function blockToStringList($items, $level = 0)
    {
        $result = [];
        $padding = \str_repeat('  ', $level);
        $firstPrefix = "$padding- ";
        // Separator
        $s = "\n";
        $nexPrefix = "$s$padding  ";
        foreach ($items as $item) {
            $result[] = $firstPrefix . \str_replace($s, $nexPrefix, (string)$item);
        }
        return $result;
    }
}
