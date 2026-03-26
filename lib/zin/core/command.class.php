<?php
/**
 * The command class file of zin lib.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'helper.func.php';

/**
 * The command class.
 */
class command
{
    /**
     * @param null|mixed[]|string|object ...$args
     * @param mixed[] $nodes
     */
    public static function addClass($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->props->class->add(...$args);
            $node->removeBuildData();
        }
    }

    /**
     * @param string|mixed[] ...$args
     * @param mixed[] $nodes
     */
    public static function removeClass($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->props->class->remove($args);
            $node->removeBuildData();
        }
    }

    /**
     * @param mixed[] $nodes
     * @param string $name
     * @param bool|null $toggle
     */
    public static function toggleClass($nodes, $name, $toggle = null)
    {
        foreach($nodes as $node)
        {
            $node->props->class->toggle($name, $toggle);
            $node->removeBuildData();
        }
    }

    /**
     * @param \zin\props|mixed[]|string $prop
     * @param mixed $value
     * @param mixed[] $nodes
     */
    public static function prop($nodes, $prop, $value = null)
    {
        foreach($nodes as $node)
        {
            $node->setProp($prop, $value);
        }
    }

    /**
     * @param string|mixed[]|object $keyOrData
     * @param mixed $value
     * @param mixed[] $nodes
     */
    public static function data($nodes, $keyOrData, $value = null)
    {
        foreach($nodes as $node)
        {
            $node->add(setData($keyOrData, $value));
        }
    }

    /**
     * @param mixed ...$codes
     * @param mixed[] $nodes
     */
    public static function html($nodes, ...$codes)
    {
        foreach($nodes as $node)
        {
            $node->empty();
            $node->add(html(...$codes));
        }
    }

    /**
     * @param mixed ...$args
     * @param mixed[] $nodes
     */
    public static function append($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args);
        }
    }

    /**
     * @param mixed[] $nodes
     */
    public static function remove($nodes)
    {
        foreach($nodes as $node)
        {
            $node->remove();
        }
    }

    /**
     * @param mixed ...$args
     * @param mixed[] $nodes
     */
    public static function text($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->empty();
            $node->add(text(...$args));
        }
    }

    /**
     * @param mixed[] $nodes
     */
    public static function empty($nodes)
    {
        foreach($nodes as $node)
        {
            $node->empty();
        }
    }

    /**
     * @param mixed ...$args
     * @param mixed[] $nodes
     */
    public static function prepend($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args, 'children', true);
        }
    }

    /**
     * @param mixed ...$args
     * @param mixed[] $nodes
     */
    public static function before($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args, 'before');
        }
    }

    /**
     * @param mixed ...$args
     * @param mixed[] $nodes
     */
    public static function after($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args, 'after');
        }
    }

    /**
     * @param mixed ...$args
     * @param mixed[] $nodes
     */
    public static function replaceWith($nodes, ...$args)
    {
        foreach($nodes as $node)
        {
            $node->replaceWith(...$args);
        }
    }

    /**
     * @param null|string|\zin\jsCallback $selectorOrCallback
     * @param null|mixed[]|string|\zin\jsCallback $handlerOrOptions
     * @param mixed[] $nodes
     * @param string $event
     */
    public static function on($nodes, $event, $selectorOrCallback = null, $handlerOrOptions = null)
    {
        foreach($nodes as $node)
        {
            $node->add(on::bind($event, $selectorOrCallback, $handlerOrOptions), 'children');
        }
    }

    /**
     * @param mixed[] $nodes
     * @param string $event
     */
    public static function off($nodes, $event)
    {
        foreach($nodes as $node)
        {
            $node->off($event);
        }
    }

    /**
     * @param string|mixed[]|object $selectors
     * @param mixed[] $nodes
     */
    public static function closest($nodes, $selectors)
    {
        foreach($nodes as $node)
        {
            $result = $node->closest($selectors);
            if($result) return array($result);
        }
        return array();
    }

    /**
     * @param string|mixed[]|object $selectors
     * @param mixed[] $nodes
     */
    public static function find($nodes, $selectors)
    {
        $list = array();
        foreach($nodes as $node)
        {
            $result = $node->find($selectors);
            if($result) $list = array_merge($list, $result);
        }
        return $list;
    }

    /**
     * @param string|mixed[]|object|null $selectors
     * @param mixed[] $nodes
     */
    public static function first($nodes, $selectors = null)
    {
        if($selectors === null) return reset($nodes);
        foreach($nodes as $node)
        {
            $result = $node->findFirst($selectors);
            if($result) return array($result);
        }
        return array();
    }

    /**
     * @param string|mixed[]|object|null $selectors
     * @param mixed[] $nodes
     */
    public static function last($nodes, $selectors = null)
    {
        if($selectors === null) return end($nodes);
        foreach($nodes as $node)
        {
            $result = $node->findLast($selectors);
            if($result) return array($result);
        }
        return array();
    }

    /**
     * @param callable|\Collator $callback
     * @param mixed[] $nodes
     */
    public static function each($nodes, $callback)
    {
        foreach($nodes as $node)
        {
            if($callback instanceof \Closure) $callback($node);
            else call_user_func($callback, $node);
        }
    }

    /**
     * Magic static method for setting property value.
     *
     * @access public
     * @param  string $name  - Property name.
     * @param  array  $args  - Property values.
     * @return setting
     */
    public static function __callStatic($name, $args)
    {
        if(isDebug())
        {
            triggerError('Command not found: ' . $name);
        }
    }
}
