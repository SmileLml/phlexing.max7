<?php
/**
 * The base node class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'helper.func.php';
require_once __DIR__ . DS . 'selector.func.php';
require_once __DIR__ . DS . 'props.class.php';

/**
 * The base node class.
 */
class node implements \JsonSerializable
{
    /**
     * Define properties
     *
     * @access public
     * @var    array
     */
    protected static $defineProps = array();

    /**
     * Default properties
     *
     * @access public
     * @var    array
     */
    protected static $defaultProps = array();

    /**
     * @var mixed[]
     */
    protected static $defineBlocks = array();

    /**
     * @var string
     */
    public $gid;

    /**
     * @var \zin\node|null
     */
    public $parent;

    /**
     * @var \zin\node|null
     */
    public $rootNode;

    /**
     * @var \zin\props
     */
    public $props;

    /**
     * @var mixed[]
     */
    public $blocks = array();

    /**
     * @var bool
     */
    public $removed = false;

    /**
     * @var mixed[]|null
     */
    public $replacedWith;

    /**
     * @var \zin\stdClass|null
     */
    public $buildData;

    /**
     * @var mixed[]
     */
    public $eventBindings = array();

    /**
     * @param mixed ...$args
     */
    public function __construct(...$args)
    {
        $this->gid   = static::nextGid();
        $this->props = new props();

        disableGlobalRender();

        $this->setDefaultProps(static::getDefaultProps());
        $this->add($args);
        $this->created();

        enableGlobalRender();
        renderInGlobal($this);
    }

    public function __debugInfo()
    {
        return (array)$this->toJSON();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function fullType()
    {
        return get_called_class();
    }

    public function type()
    {
        $type = $this->fullType();
        if(str_contains($type, '\\'))
        {
            $type = substr($type, strrpos($type, '\\') + 1);
        }
        return $type;
    }

    public function id()
    {
        return strval($this->props->get('id'));
    }

    public function displayID()
    {
        $displayID = $this->fullType() . '~' . $this->gid;

        $id = $this->id();
        if(!empty($id)) $displayID .= "#$id";

        return $displayID;
    }

    /**
     * Check if the element is match any of the selectors
     * @param  string|array|object $selectors
     */
    public function is($selectors)
    {
        $list = parseSelectors($selectors);
        foreach($list as $selector)
        {
            if($this->isMatch($selector)) return true;
        }
        return false;
    }

    /**
     * @param object $selector
     */
    public function isMatch($selector)
    {
        if(!empty($selector->id)      && $this->id() !== $selector->id)               return false;
        if(!empty($selector->tag)     && $this->type() !== $selector->tag)            return false;
        if(!empty($selector->class)   && !$this->props->class->has($selector->class)) return false;
        if(!empty($selector->parents) && !$this->hasParents(...$selector->parents))   return false;
        return true;
    }

    /**
     * @param object|string ...$parentSelectors
     */
    public function hasParents(...$parentSelectors)
    {
        $parent = $this->parent;
        if(!$parent) return false;

        foreach($parentSelectors as $selector)
        {
            $parent = $parent->closest($selector);
            if(!$parent) return false;
        }

        return true;
    }

    /**
     * @param string $event
     */
    public function off($event)
    {
        unset($this->eventBindings[$event]);
        $this->props->remove("@$event");
    }

    /**
     * @param string|mixed[]|object $selectors
     */
    public function closest($selectors)
    {
        $list = parseSelectors($selectors, true);
        $node = $this;
        while($node)
        {
            if($node->is($list)) return $node;
            $node = $node->parent;
        }
        return null;
    }

    /**
     * @param string|mixed[]|object $selectors
     * @param bool $first
     * @param bool $reverse
     * @param bool $prebuild
     */
    public function find($selectors, $first = false, $reverse = false, $prebuild = true)
    {
        if($prebuild) $this->prebuild();
        return findInNode(parseSelectors($selectors, true), $this, $first, $reverse);
    }

    /**
     * @param string|mixed[]|object $selectors
     */
    public function findFirst($selectors)
    {
        $results = $this->find($selectors, true);
        return empty($results) ? null : reset($results);
    }

    /**
     * @param string|mixed[]|object $selectors
     */
    public function findLast($selectors)
    {
        $results = $this->find($selectors, true, true);
        return empty($results) ? null : end($results);
    }

    /**
     * @param mixed[]|string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function prop($name, $defaultValue = null)
    {
        if(is_array($name))
        {
            $values = array();
            foreach($name as $index => $propName)
            {
                $values[] = $this->onGetProp($propName, is_array($defaultValue) ? (isset($defaultValue[$propName]) ? $defaultValue[$propName] : $defaultValue[$index]) : $defaultValue);
            }
            return $values;
        }

        return $this->onGetProp($name, $defaultValue);
    }

    /**
     * Set property, an array can be passed to set multiple properties
     *
     * @access public
     * @param props|array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     */
    public function setProp($prop, $value = null)
    {
        if($prop instanceof props) $prop = $prop->toJSON();

        if(is_array($prop))
        {
            foreach($prop as $name => $value) $this->setProp($name, $value);
            return $this;
        }

        if(!is_string($prop) || empty($prop)) return $this;

        if($prop[0] === '#')
        {
            $this->add($value, substr($prop, 1));
            return $this;
        }

        $this->onSetProp($prop, $value);
        return $this;
    }

    public function hasProp()
    {
        $names = func_get_args();
        if(empty($names)) return false;
        foreach($names as $name)
        {
            if(!$this->props->has($name)) return false;
        }
        return true;
    }

    /**
     * @param string|mixed[] $props
     * @param mixed $value
     */
    public function setDefaultProps($props, $value = null)
    {
        if(is_string($props)) $props = array($props => $value);
        if(!is_array($props) || empty($props)) return;

        foreach($props as $name => $value)
        {
            if($this->props->isset($name)) continue;
            $this->setProp($name, $value);
        }
    }

    public function getRestProps()
    {
        return $this->props->skip(array_keys(static::definedPropsList()));
    }

    public function getDefinedProps()
    {
        return $this->props->pick(array_keys(static::definedPropsList()));
    }

    /**
     * @param mixed $item
     * @param string $blockName
     * @param bool $prepend
     */
    public function add($item, $blockName = 'children', $prepend = false)
    {
        if($item === null || is_bool($item)) return;

        if($item instanceof \Closure) $item = $item();
        if(is_array($item))
        {
            foreach($item as $child) $this->add($child, $blockName, $prepend);
            return;
        }

        if(isDirective($item)) $this->directive($item, $blockName);
        else $this->addToBlock($blockName, $item, $prepend);
    }

    /**
     * @param mixed $child
     * @param string $name
     * @param bool $prepend
     */
    public function addToBlock($name, $child, $prepend = false)
    {
        if($child === null || is_bool($child)) return;

        if(is_array($child))
        {
            foreach($child as $blockChild)
            {
                $this->addToBlock($name, $blockChild, $prepend);
            }
            return;
        }

        if($child instanceof node) $child->parent = $this;

        if($child instanceof node)
        {
            if(isset($child->parent)) skipRenderInGlobal($child);
            $child->parent = $this;
        }

        if($name === 'children' && $child instanceof node)
        {
            $blockName = static::getNameFromBlockMap($child->fullType());
            if($blockName !== null) $name = $blockName;
        }
        elseif(is_string($child))
        {
            /* Encode html special chars. */
            $child = htmlspecialchars(strval($child), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false);
        }

        $result = $name === 'children' ? $this->onAddChild($child) : $this->onAddBlock($child, $name);
        if($result === false) return;
        if($result !== null && $result !== true) $child = $result;

        if(isset($this->blocks[$name]))
        {
            if($prepend) array_unshift($this->blocks[$name], $child);
            else         $this->blocks[$name][] = $child;
        }
        else
        {
            $this->blocks[$name]   = array($child);
        }
    }

    /**
     * @param \zin\iDirective $directive
     * @param string $blockName
     */
    public function directive($directive, $blockName = 'children')
    {
        if(!isset($directive->parent) || !$directive->parent) $directive->parent = $this;
        $directive->apply($this, $blockName);
    }

    /**
     * @param mixed $child
     */
    public function addChild($child)
    {
        return $this->addToBlock('children', $child);
    }

    public function remove()
    {
        $this->removed = true;
    }

    /**
     * @param string|null $blockName
     */
    public function empty($blockName = null)
    {
        if($blockName) unset($this->blocks[$blockName]);
        else           $this->blocks = array();

        $this->removeBuildData('all');
    }

    /**
     * @param object|mixed[] $info
     * @param string $event
     */
    public function bindEvent($event, $info)
    {
        if($info instanceof jsCallback)
        {
            $this->props->bindEvent($event, $info->toJS());
            $this->removeBuildData();
            return;
        }

        if(is_array($info)) $info = (object)$info;
        if(!isset($this->eventBindings[$event])) $this->eventBindings[$event] = array();
        $this->eventBindings[$event][] = $info;

        if(!$this->hasProp('id')) $this->setProp('id', $this->gid);
    }

    public function buildEvents()
    {
        $events = $this->eventBindings;
        if(empty($events)) return null;

        $id   = $this->id();
        $code = array
        (
            '$(function(){',
            $this->type() === 'html' ? 'const ele = document;' : 'const ele = document.getElementById("' . (empty($id) ? $this->gid : $id) . '");if(!ele)return;const $ele = $(ele); const events = new Set(($ele.attr("data-zin-events") || "").split(" ").filter(Boolean));'
        );
        foreach($events as $event => $bindingList)
        {
            $code[]   = "\$ele.on('$event.zin.on', function(e){";
            foreach($bindingList as $binding)
            {
                if(is_string($binding)) $binding = (object)array('handler' => $binding);
                $selector = isset($binding->selector) ? $binding->selector : null;
                $handler  = isset($binding->handler) ? trim($binding->handler) : '';
                $stop     = isset($binding->stop) ? $binding->stop : null;
                $prevent  = isset($binding->prevent) ? $binding->prevent : null;
                $self     = isset($binding->self) ? $binding->self : null;

                $code[]   = '(function(){';
                if($selector) $code[] = "const target = e.target.closest('$selector');if(!target) return;";
                else          $code[] = "const target = ele;";
                if($self)     $code[] = "if(ele !== e.target) return;";
                if($stop)     $code[] = "e.stopPropagation();";
                if($prevent)  $code[] = "e.preventDefault();";

                if(preg_match('/^[$A-Z_][0-9A-Z_$\[\]."\']*$/i', $handler)) $code[] = "($handler).call(target,e);";
                else $code[] = $handler;

                $code[] = '})();';
            }
            $code[] = "});events.add('$event');";
        }
        $code[] = '$ele.attr("data-zin-events", Array.from(events).join(" "));';
        $code[] = '});';
        return implode("\n", $code);
    }

    public function render()
    {
        if($this->removed) return '';

        $data = new stdClass();
        $data->html = renderToHtml(...$this->buildAll());

        context()->handleRenderNode($data, $this);

        return $data->html;
    }

    public function renderInner()
    {
        if($this->removed) return '';

        return renderToHtml(...$this->children());
    }

    /**
     * @param string $type
     */
    public function removeBuildData($type = 'children')
    {
        if(!$this->buildData) return;

        if($type === 'all')
        {
            $this->buildData = new stdClass();
        }
        elseif($type === 'before')
        {
            unset($this->buildData->before);
        }
        elseif($type === 'after')
        {
            unset($this->buildData->after);
        }
        else
        {
            unset($this->buildData->content);
            unset($this->buildData->children);
        }
   }

    /**
     * @param bool $force
     */
    public function prebuild($force = false)
    {
        $firstBuild = ($this->buildData === null || $force);
        if($firstBuild)
        {
            $context = context();
            $context->handleBeforeBuildNode($this);

            $data = new stdClass();
            $data->before   = prebuild($this->buildBefore(), $this);
            $data->children = prebuild($this->children(), $this);
            $data->content  = prebuild($this->buildContents(), $this);
            $data->after    = prebuild($this->buildAfter(), $this);

            $this->buildData = $data;

            $context->handleBuildNode($data, $this);
        }
        else
        {
            if(!isset($this->buildData->before))   $this->buildData->before   = prebuild($this->buildBefore(), $this);
            if(!isset($this->buildData->children)) $this->buildData->children = prebuild($this->children(), $this);
            if(!isset($this->buildData->content))  $this->buildData->content  = prebuild($this->buildContents(), $this);
            if(!isset($this->buildData->after))    $this->buildData->after    = prebuild($this->buildAfter(), $this);
        }

        return $this->buildData;
    }

    public function buildContents()
    {
        $content = $this->build();
        if(is_null($content) || is_bool($content)) $content = array();
        elseif(!is_array($content))                $content = array($content);
        return $content;
    }

    public function buildAll()
    {
        if($this->replacedWith !== null) return $this->replacedWith;

        $data = $this->prebuild();
        return array_merge($data->before, $data->content, $data->after);
    }

    /**
     * @param mixed ...$args
     */
    public function replaceWith(...$args)
    {
        $this->replacedWith = $args;
    }

    public function children()
    {
        return $this->block('children');
    }

    /**
     * @param string $name
     */
    public function block($name)
    {
        $list = array();
        if(isset($this->blocks[$name]))
        {
            $items = $this->blocks[$name];
            foreach($items as $item)
            {
                if(is_array($item)) $list   = array_merge($list, $item);
                else                $list[] = $item;
            }
        }
        return $list;
    }

    /**
     * @param string $name
     */
    public function hasBlock($name)
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Convert to JSON object.
     *
     * @access public
     * @return object
     */
    public function toJSON()
    {
        $json = new stdClass();
        $json->gid   = $this->gid;
        $json->type  = $this->type();
        $json->props = $this->props->toJSON();

        $json->blocks = array();
        foreach($this->blocks as $key => $block)
        {
            foreach($block as $index => $child)
            {
                if($child instanceof node || (is_object($child) && method_exists($child, 'toJSON')))
                {
                    $block[$index] = $child->toJSON();
                }
            }

            if($key === 'children')
            {
                $json->$key = $block;
                unset($json->blocks[$key]);
            }
            else
            {
                $json->blocks[$key] = $block;
            }
        }

        if(!$json->blocks) unset($json->blocks);

        $id = $this->id();
        if($id !== null) $json->id = $id;

        $parent = $this->parent;
        if($parent !== null) $json->parent = $parent->displayID();

        if($this->removed) $json->removed = true;

        return $json;
    }

    /**
     * Serialized to JSON string.
     *
     * @access public
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return json_encode($this->toJSON());
    }

    /**
     * Trigger error in debug mode.
     *
     * @access public
     * @param  string $message
     * @param  int    $level
     * @return void
     */
    public function triggerError($message, $level = E_USER_ERROR)
    {
        triggerError("{$this->displayID()}: $message", $level);
    }

    protected function build()
    {
        return $this->children();
    }

    protected function buildBefore()
    {
        return $this->block('before');
    }

    protected function buildAfter()
    {
        return $this->block('after');
    }

    protected function created()
    {
    }

    /**
     * @param mixed $child
     */
    protected function onAddChild($child)
    {
        $this->removeBuildData();
        return $child;
    }

    /**
     * @param mixed $child
     * @param string $name
     */
    protected function onAddBlock($child, $name)
    {
        $this->removeBuildData($name);
        return $child;
    }

    /**
     * @param mixed[]|string $prop
     * @param mixed $value
     */
    protected function onSetProp($prop, $value)
    {
        if($prop === 'id' && $value === '$GID') $value = $this->gid;

        $this->props->set($prop, $value);
        $this->removeBuildData();
    }

    /**
     * @param mixed $defaultValue
     * @return mixed
     * @param string $prop
     */
    protected function onGetProp($prop, $defaultValue)
    {
        return $this->props->get($prop, $defaultValue);
    }

    /**
     * Check errors in debug mode.
     *
     * @access protected
     * @return void
     */
    protected function checkErrors()
    {
        if(!isDebug()) return;

        $definedProps = static::definedPropsList();
        foreach($definedProps as $name => $definition)
        {
            if($this->hasProp($name)) continue;
            if(isset($definition['default']) && $definition['default'] !== null) continue;
            if(isset($definition['optional']) && $definition['optional']) continue;

            $this->triggerError("The value of property \"$name: {$definition['type']}\" is required.");
        }

        $wgErrors = $this->onCheckErrors();
        if(empty($wgErrors)) return;

        foreach($wgErrors as $error)
        {
            if(is_array($error)) $this->triggerError(...$error);
            else $this->triggerError($error);
        }
    }

    /**
     * The lifecycle method for checking errors in debug mode.
     *
     * @access protected
     * @return array|null
     */
    protected function onCheckErrors()
    {
        return null;
    }

    /**
     * @var mixed[]
     */
    protected static $definedPropsMap = array();

    /**
     * @var mixed[]
     */
    protected static $blockMap = array();

    /**
     * @var mixed[]
     */
    protected static $gidMap = array();

    /**
     * @var string|null
     */
    protected static $pageKey;

    public static function nextGid($prefix = 'zin_', $type = null)
    {
        global $config;
        if(!isset($config->clientCache) || !$config->clientCache)
        {
            return $prefix . uniqid();
        }

        if($type === null)
        {
            $type = get_called_class();
            if(str_starts_with($type, 'zin\\')) $type = substr($type, 4);
        }
        $lastID = isset(static::$gidMap[$type]) ? static::$gidMap[$type] : -1;
        $nextID = $lastID + 1;
        static::$gidMap[$type] = $nextID;

        $key = static::$pageKey;
        if($key === null)
        {
            global $app;
            $key = $app->rawModule . '_' . $app->rawMethod . '_';
            $pageDataID = data($app->rawModule . 'ID');
            if(!$pageDataID)
            {
                $pageData = data($app->rawModule);
                if(is_object($pageData) && isset($pageData->id))      $pageDataID = $pageData->id;
                elseif(is_array($pageData) && isset($pageData['id'])) $pageDataID = $pageData['id'];
            }
            if($pageDataID) $key .= $pageDataID . '_';
            static::$pageKey = $key;
        }

        $id = $prefix . $key . $type;
        if($nextID > 0) $id .= "_$nextID";
        return $id;
    }

    public static function getBlockMap()
    {
        $type = get_called_class();
        if(!isset(node::$blockMap[$type]))
        {
            $blockMap = array();
            if(is_array(static::$defineBlocks))
            {
                foreach(static::$defineBlocks as $blockName => $setting)
                {
                    if(!isset($setting['map'])) continue;
                    $map = $setting['map'];
                    if(is_string($map)) $map = explode(',', $map);
                    foreach($map as $name) $blockMap[$name] = $blockName;
                }
            }
            node::$blockMap[$type] = $blockMap;
        }
        return node::$blockMap[$type];
    }

    /**
     * @param string $type
     */
    public static function getNameFromBlockMap($type)
    {
        $blockMap = static::getBlockMap();
        if(str_starts_with($type, 'zin\\')) $type = substr($type, 4);
        return isset($blockMap[$type]) ? $blockMap[$type] : null;
    }

    /**
     * @param string|null $type
     */
    public static function definedPropsList($type = null)
    {
        if($type === null) $type = get_called_class();

        if(!isset(node::$definedPropsMap[$type]) && $type === get_called_class())
        {
            node::$definedPropsMap[$type] = static::parsePropsDefinition();
        }
        return node::$definedPropsMap[$type];
    }

    /**
     * @param string|null $type
     */
    public static function getDefaultProps($type = null)
    {
        $type             = $type ? $type : get_called_class();
        $defaultProps     = array();
        $definedPropsList = static::definedPropsList($type);

        foreach($definedPropsList as $name => $definition)
        {
            if(!isset($definition['default'])) continue;
            $defaultProps[$name] = $definition['default'];
        }
        return $defaultProps;
    }

    /**
     * Parse props definition
     * @param $definition
     * @example
     *
     * $definition = array('name', 'desc:string', 'title?:string|array', 'icon?:string="star"');
     * $definition = array('name' => 'mixed', 'desc' => '?string', 'title' => array('type' => 'string|array', 'optional' => true), 'icon' => array('type' => 'string', 'default' => 'star', 'optional' => true))))
     */
    protected static function parsePropsDefinition()
    {
        $parentClass  = get_parent_class(get_called_class());
        $parentProps  = array();
        $defaultProps = static::$defaultProps;
        $definition   = static::$defineProps;

        if($parentClass)
        {
            if($definition === $parentClass::$defineProps)    $definition = array();
            if($defaultProps === $parentClass::$defaultProps) $defaultProps = array();

            $parentProps = call_user_func("$parentClass::definedPropsList", $parentClass);
        }

        return parsePropsMap($definition, $parentProps, $defaultProps);
    }
}

/**
 * @param \zin\node|mixed[] $list
 */
function findInNode($selectors, $list, $first = false, $reverse = false, $onlyContent = true)
{
    if($list instanceof node)
    {
        $data = $list->buildData;
        if(!$data) return array();

        $list = $data->content ? $data->content : array();
        if(!$onlyContent) $list = array_merge($data->before ? $data->before : array(), $list, $data->after ? $data->after : array());
    }
    if($reverse) $list = array_reverse($list);
    $result = array();
    foreach($list as $child)
    {
        if(is_array($child))
        {
            $childList = findInNode($selectors, $child, $first, $reverse, $onlyContent);
            if(!empty($childList))
            {
                if($first) return $childList;
                $result = array_merge($result, $childList);
            }
            continue;
        }

        if(!($child instanceof node)) continue;

        if($child->is($selectors) && $child->type() !== 'item')
        {
            if($child->parent && ($child->parent->removed || ($child->parent instanceof wg && $child->parent->type() !== 'item' && $child->parent->is($selectors)))) continue;
            $result[$child->gid] = $child;
            if($first) return $result;
        }

        $childList = findInNode($selectors, $child, $first, $reverse, $onlyContent);
        if(!empty($childList))
        {
            if($first) return $childList;
            $result = array_merge($result, $childList);
        }
    }
    return $result;
}

/**
 * @param mixed ...$items
 */
function renderToHtml(...$items)
{
    $html = '';

    foreach($items as $item)
    {
        if(is_array($item))
        {
            $html .= renderToHtml(...$item);
            continue;
        }
        if($item instanceof node || (is_object($item) && method_exists($item, 'render')))
        {
            $html .= $item->render();
            continue;
        }
        if(is_object($item) && isset($item->html))
        {
            $html .= $item->html;
            continue;
        }
        if(!is_string($item)) $item = strval($item);
        $html .= strval($item);
    }

    return $html;
}

function prebuild($items, $parent = null)
{
    foreach($items as $index => $item)
    {
        if(is_array($item))
        {
            $items[$index] = prebuild($item, $parent);
            continue;
        }
        if(!($item instanceof node)) continue;
        if($parent) $item->parent = $parent;
        $item->prebuild();
    }
    return $items;
}

/**
 * Parse the props definition.
 *
 * @param array $definition    - The props definition.
 * @param array $parentProps   - The parent props.
 * @param array $defaultValues - The default values.
 * @return array
 */
function parsePropsMap($definition, $parentProps = array(), $defaultValues = array())
{
    $props = $parentProps;
    foreach($parentProps as $parentProp)
    {
        $name = $parentProp['name'];
        if(isset($defaultValues[$name])) $parentProp['default'] = $defaultValues[$name];
        $props[$name] = $parentProp;
    }

    foreach($definition as $name => $value)
    {
        $prop = parseProp($value, is_string($name) ? $name : null);
        $name = $prop['name'];

        if(isset($defaultValues[$name]))
        {
            $prop['default'] = $defaultValues[$name];
        }
        elseif(!isset($prop['default']) && isset($parentProps[$name]['default']) && $parentProps[$name]['default'])
        {
            $prop['default'] = $parentProps[$name]['default'];
        }

        $props[$name] = $prop;
    }

    return $props;
}

/**
 * Parse the prop definition.
 *
 * @param string|array $definition - The prop definition.
 * @param string|null  $name       - The prop name.
 * @return array
 */
function parseProp($definition, $name = null)
{
    $optional = false;
    $type     = 'mixed';
    $prop     = array();

    if(is_string($definition)) $definition = trim($definition);

    /* Parse definition like `'name?: type1|type2="default"'` . */
    if(!$name && is_string($definition))
    {
        if(str_contains($definition, ':'))
        {
            list($name, $definition) = explode(':', $definition, 2);
        }
        else
        {
            $name       = $definition;
            $definition = '';
        }
        $name = trim($name);
        if(str_ends_with($name, '?'))
        {
            $name     = substr($name, 0, strlen($name) - 1);
            $optional = true;
        }
    }

    /* Parse definition like `'name' => '?type1|type2="default"'` . */
    if(is_array($definition))
    {
        if(isset($definition['type']))     $type = $definition['type'];
        if(isset($definition['default']))  $prop['default'] = $definition['default'];
        if(isset($definition['optional'])) $optional = $definition['optional'];
    }
    else if(is_string($definition))
    {
        if(str_contains($definition, '='))
        {
            list($type, $default) = explode('=', $definition, 2);
            if(strlen($default)) $prop['default'] = json_decode(trim($default));
        }
        else
        {
            $type = $definition;
        }
    }

    $type = trim($type);
    if(str_starts_with($type, '?'))
    {
        $type     = substr($type, 1);
        $optional = true;
    }

    $typeList = explode('|', $type);
    if(in_array('null', $typeList) || in_array('mixed', $typeList))
    {
        $optional = true;
    }
    elseif($optional)
    {
        array_unshift($typeList, 'null');
    }

    $prop['name']     = $name;
    $prop['type']     = implode('|', $typeList);
    $prop['optional'] = $optional || (isset($prop['default']) && $prop['default'] !== null);
    return $prop;
}
