<?php

/**
 * The field class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'core' . DS . 'setting.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';

use \zin\fieldList;

class field extends setting
{
    /**
     * @var \zin\fieldList|null
     */
    public $fieldList;

    /**
     * @var \zin\field|null
     */
    public $parent;

    /**
     * @var string|null
     */
    public $dataType;

    /**
     * @var mixed
     */
    public $default;

    /**
     * @param string|object|mixed[]|null $nameOrProps
     * @param \zin\fieldList|null $fieldList
     * @param \zin\field|null $parent
     */
    public function __construct($nameOrProps = null, $fieldList = null, $parent = null)
    {
        $this->fieldList = $fieldList;
        $this->parent    = $parent;

        if(is_string($nameOrProps))           $nameOrProps = array('name' => $nameOrProps);
        elseif($nameOrProps instanceof field) $nameOrProps = $nameOrProps->toArray();
        elseif(is_object($nameOrProps))       $nameOrProps = get_object_vars($nameOrProps);

        parent::__construct($nameOrProps);
    }

    public function getName()
    {
        return $this->get('name');
    }

    /**
     * @param string|null $name
     */
    public function name($name)
    {
        return $this->setVal('name', $name);
    }

    /**
     * @param string|null $type
     */
    public function type($type)
    {
        return $this->setVal('type', $type);
    }

    /**
     * @param string|null $group
     */
    public function group($group)
    {
        return $this->setVal('group', $group);
    }

    /**
     * @param string|null $id
     */
    public function id($id)
    {
        return $this->setVal('id', $id);
    }

    /**
     * @param mixed ...$classList
     */
    public function className(...$classList)
    {
        return $this->setClass('className', ...$classList);
    }

    /**
     * @param string|mixed[]|int|null $value
     */
    public function value($value)
    {
        return $this->setVal('value', $value);
    }

    /**
     * @param string|int|null $width
     */
    public function width($width)
    {
        return $this->setVal('width', $width);
    }

    /**
     * @param bool|null $required
     */
    public function required($required = true)
    {
        return $this->setVal('required', $required);
    }

    /**
     * @param bool|null $disabled
     */
    public function disabled($disabled = true)
    {
        return $this->setVal('disabled', $disabled);
    }

    /**
     * @param bool|null $readonly
     */
    public function readonly($readonly = true)
    {
        return $this->setVal('readonly', $readonly);
    }

    /**
     * @param string|null $placeholder
     */
    public function placeholder($placeholder)
    {
        return $this->setVal('placeholder', $placeholder);
    }

    /**
     * @param bool|null $foldable
     */
    public function foldable($foldable = true)
    {
        return $this->setVal('foldable', $foldable);
    }

    /**
     * @param bool|null $pinned
     */
    public function pinned($pinned = true)
    {
        return $this->setVal('pinned', $pinned);
    }

    /**
     * @param bool|null $hidden
     */
    public function hidden($hidden = true)
    {
        return $this->setVal('hidden', $hidden);
    }

    /**
     * @param bool|null $strong
     */
    public function strong($strong = true)
    {
        return $this->setVal('strong', $strong);
    }

    /**
     * @param bool|string|null $label
     * @param string|object|mixed[] $classOrProps
     */
    public function label($label, $classOrProps = null)
    {
        $this->setVal('label', $label);
        if (is_string($classOrProps)) $this->labelClass($classOrProps);
        else if (!is_null($classOrProps)) $this->labelProps($classOrProps);
        return $this;
    }

    /**
     * @param bool|string|null $for
     */
    public function labelFor($for)
    {
        return $this->setVal('labelFor', $for);
    }

    /**
     * @param mixed ...$classList
     */
    public function labelClass(...$classList)
    {
        return $this->setClass('labelClass', ...$classList);
    }

    /**
     * @param mixed[]|object|null $props
     */
    public function labelProps($props)
    {
        return $this->addToMap('labelProps', $props);
    }

    /**
     * @param int|string|null $width
     */
    public function labelWidth($width)
    {
        return $this->setVal('labelWidth', $width);
    }

    /**
     * @param string|object|mixed[] $classOrProps
     * @param string|null $hint
     */
    public function labelHint($hint, $classOrProps = null)
    {
        $this->setVal('labelHint', $hint);
        if (is_string($classOrProps)) $this->labelHintClass($classOrProps);
        else if (!is_null($classOrProps)) $this->labelHintProps($classOrProps);
        return $this;
    }

    /**
     * @param mixed ...$classList
     */
    public function labelHintClass(...$classList)
    {
        return $this->setClass('labelHintClass', ...$classList);
    }

    /**
     * @param mixed[]|object|null $props
     */
    public function labelHintProps($props)
    {
        return $this->addToMap('labelHintProps', $props);
    }

    /**
     * @param mixed[]|object|string|null $icon
     */
    public function labelHintIcon($icon)
    {
        return $this->setVal('labelHintIcon', $icon);
    }

    /**
     * @param mixed[]|false|null $actions
     * @param bool $reset
     * @param string|null $key
     */
    public function labelActions($actions, $reset = false, $key = null)
    {
        if($actions === false)  return $this->remove('actions');
        if($reset)              return $this->setVal('actions', $actions);
        if(is_array($actions))  return $this->mergeToList('actions', $actions, $key);
        return $this;
    }

    /**
     * @param mixed $action
     * @param string|null $key
     */
    public function labelAction($action, $key = null)
    {
        return $this->addToList('labelActions', $action, $key);
    }

    /**
     * @param mixed ...$classList
     */
    public function labelActionsClass(...$classList)
    {
        return $this->setClass('labelActionsClass', ...$classList);
    }

    /**
     * @param mixed[]|object|null $props
     */
    public function labelActionsProps($props)
    {
        return $this->addToMap('labelActionsProps', $props);
    }

    /**
     * @param bool|mixed[]|null $checkbox
     */
    public function checkbox($checkbox = true)
    {
        return $this->setVal('checkbox', $checkbox);
    }

    /**
     * @param bool|null $wrapBefore
     */
    public function wrapBefore($wrapBefore = true)
    {
        return $this->setVal('wrapBefore', $wrapBefore);
    }

    /**
     * @param bool|null $wrapAfter
     */
    public function wrapAfter($wrapAfter = true)
    {
        return $this->setVal('wrapAfter', $wrapAfter);
    }

    /**
     * @param string $side
     * @param bool $wrap
     */
    public function wrap($side = 'before', $wrap = true)
    {
        return $this->setVal($side == 'before' ? 'wrapBefore' : 'wrapAfter', $wrap);
    }

    /**
     * @param bool|string|null $text
     */
    public function text($text)
    {
        return $this->setVal('text', $text);
    }

    /**
     * @param bool|string|null $tip
     */
    public function tip($tip)
    {
        return $this->setVal('tip', $tip);
    }

    /**
     * @param mixed ...$classList
     */
    public function tipClass(...$classList)
    {
        return $this->setClass('tipClass', ...$classList);
    }

    /**
     * @param mixed[]|object|null $tipProps
     */
    public function tipProps($tipProps)
    {
        return $this->addToMap('tipProps', $tipProps);
    }

    /**
     * @param bool|null $multiple
     */
    public function multiple($multiple = true)
    {
        return $this->setVal('multiple', $multiple);
    }

    /**
     * @param mixed[]|object|string|null $nameOrData
     * @param mixed $value
     */
    public function data($nameOrData, $value = null)
    {
        if(is_string($nameOrData)) $nameOrData = array($nameOrData => $value);
        return $this->addToMap('data', $nameOrData);
    }

    /**
     * @param string|null $itemName
     */
    public function createChild($itemName = null)
    {
        $item = new field($itemName, null, $this);
        return $item;
    }

    /**
     * @param string|mixed[]|object|false|null $control
     * @param mixed[]|object|null $props
     */
    public function control($control, $props = null)
    {
        if($control === false) return $this->remove('control');

        if(is_object($props)) $props = get_object_vars($props);
        if(is_string($control) && is_array($props)) $control = array('control' => $control) + $props;
        elseif($control instanceof node)            $control = node($control);

        return $this->setVal('control', $control);
    }

    /**
     * @param string|null $itemName
     */
    public function controlBegin($itemName)
    {
        return $this->createChild($itemName);
    }

    public function controlEnd()
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should call "controlBegin($name)" firstly.', E_USER_ERROR);
        }

        $control = $this->toArray();
        if(!isset($control['control'])) $control['control'] = $this->getName();
        unset($control['name']);

        return $this->parent->control($control);
    }

    /**
     * Set items.
     *
     * @access public
     * @param  array|false|null $items  - Items.
     * @return field
     */
    public function items($items)
    {
        if($items === false) return $this->remove('items');
        return $this->setVal('items', $items);
    }

    /**
     * Add item.
     * @param mixed $item
     * @param string|null $key
     */
    public function item($item, $key = null)
    {
        return $this->addToList('items', $item, $key);
    }

    /**
     * @param string|null $itemName
     */
    public function itemBegin($itemName = null)
    {
        return $this->createChild($itemName);
    }

    public function itemEnd()
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should call "itemBegin($name)" firstly.', E_USER_ERROR);
        }
        $this->parent->item($this);
        return $this->parent;
    }

    /**
     * @param mixed ...$children
     */
    public function children(...$children)
    {
        return $this->mergeToList('children', $children);
    }

    /**
     * @param string|mixed[]|null $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @param string|null $dataType
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @param string $name
     */
    public function moveBefore($name)
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveBefore($this->getName(), $name);
        return $this;
    }

    /**
     * @param string $name
     */
    public function moveAfter($name)
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveAfter($this->getName(), $name);
        return $this;
    }

    public function moveToBegin()
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveToBegin($this->getName());
        return $this;
    }

    public function moveToEnd()
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveToEnd($this->getName());
        return $this;
    }

    public function detach()
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->remove($this->getName());
        return $this;
    }

    public function toArray()
    {
        $array = parent::toArray();
        if(isset($array['items']))
        {
            $items = array();
            foreach($array['items'] as $key => $item)
            {
                if($item instanceof \zin\utils\dataset) $item = $item->toArray();
                elseif($item instanceof \stdClass)      $item = get_object_vars($item);
                $items[$key] = $item;
            }
            $array['items'] = $items;
        }
        if(isset($array['control']) && is_array($array['control']) && isset($array['control']['control']) && count($array['control']) == 1)
        {
            $array['control'] = $array['control']['control'];
        }
        return $array;
    }
}
