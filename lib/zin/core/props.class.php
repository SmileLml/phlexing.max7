<?php
/**
 * The props class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

use zin\utils\classlist;
use zin\utils\styleset;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'classlist.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'styleset.class.php';

/**
 * Manage properties for html element and widgets
 */
class props extends \zin\utils\dataset
{
    /**
     * Style property
     *
     * @access public
     * @var    styleset
     */
    public $style;

    /**
     * Class property
     *
     * @access public
     * @var    classlist
     */
    public $class;

    /**
     * @var mixed[]
     */
    public static $booleanAttrs = array('allowfullscreen', 'async', 'autofocus', 'autoplay', 'checked', 'controls', 'default', 'defer', 'disabled', 'formnovalidate', 'inert', 'ismap', 'itemscope', 'loop', 'multiple', 'muted', 'nomodule', 'novalidate', 'open', 'playsinline', 'readonly', 'required', 'reversed', 'selected');

    /**
     * Create properties instance
     *
     * @access public
     * @param array $props - Properties list array
     */
    public function __construct($props = array())
    {
        $this->style = new styleset();
        $this->class = new classlist();

        parent::__construct($props);
    }

    /**
     * Method for sub class to modify value on setting it
     *
     * @access public
     * @param string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     */
    protected function setVal($prop, $value)
    {
        if($prop === 'class')                      $this->class->set($value);
        elseif($prop === 'style')                  $this->style->set($value);
        elseif(str_starts_with($prop, '~'))        $this->style->set(substr($prop, 1), $value);
        elseif($prop === '--')                     $this->style->cssVar($value);
        elseif(str_starts_with($prop, '--'))       $this->style->cssVar(substr($prop, 2), $value);
        elseif(str_starts_with($prop, ':'))        $this->set('data-' . substr($prop, 1), $value);
        elseif(str_starts_with($prop, '@'))        $this->bindEvent(substr($prop, 1), $value);
        else                                       parent::setVal($prop, $value);
        return $this;
    }

    /**
     * @return mixed
     * @param string $prop
     */
    protected function getVal($prop)
    {
        if($prop === 'class' || $prop === '.')
        {
            if(!$this->class->count()) return null;
            return $this->class->toStr();
        }
        if($prop === 'style' || $prop === '~')
        {
            if(!$this->style->getCount(true)) return null;
            return $this->style->toStr();
        }
        return parent::getVal($prop);
    }

    /**
     * @param string|string[] $name
     * @param mixed $value
     */
    public function reset($name, $value = null)
    {
        if(is_array($name))
        {
            foreach($name as $n) $this->reset($n);
            return;
        }
        if($name === 'class') return $this->class->clear();
        if($name === 'style') return $this->style->clear();

        $this->remove($name);
        if($value) $this->setVal($name, $value);
    }

    /**
     * @param string|mixed[] $name
     * @param string|mixed[] $handler
     */
    public function bindEvent($name, $handler = null)
    {
        if(is_array($name))
        {
            foreach($name as $key => $value) $this->bindEvent($key, $value);
            return;
        }

        $events = parent::getVal("@$name");
        if(is_null($events)) $events = array();

        if(is_array($handler)) $events   = array_merge($events, $handler);
        else                   $events[] = $handler;

        parent::setVal("@$name", $events);
    }

    public function events()
    {
        $events = array();
        foreach($this->storedData as $name => $value)
        {
            if(str_starts_with($name, '@')) $events[substr($name, 1)] = $value;
        }

        return $events;
    }

    public function hasEvent()
    {
        foreach($this->storedData as $name => $value)
        {
            if(str_starts_with($name, '@') && $name !== '@init') return true;
        }

        return false;
    }

    /**
     * Convert props to html string
     *
     * Example:
     *
     *     // Properties data map:
     *     $map = array(
     *         'id' => 'sayHelloBtn',
     *         'data-title' => 'Say "Hello"!',
     *         'data-content' => null,
     *         'data-show' => true,
     *     );
     *     // Output string: id="sayHelloBtn" data-title="Say &quot;Hello&quot;!" data-show="true"
     *
     * @access public
     * @param mixed[]|string $skipProps
     */
    public function toStr($skipProps = array())
    {
        if(is_string($skipProps)) $skipProps = explode(',', $skipProps);

        $pairs = array();

        if($this->class->count())        $pairs[] = 'class="' . $this->class->toStr() . '"';
        if($this->style->getCount(true)) $pairs[] = 'style="' . $this->style->toStr() . '"';

        $initCode = array();

        foreach($this->storedData as $name => $value)
        {
            /* Handle boolean attributes */
            if(in_array($name, static::$booleanAttrs)) $value = $value ? true : null;

            /* Skip any null value or events setting */
            if($value === null || in_array($name, $skipProps)) continue;

            if($name === 'zui-init' || str_starts_with($name, '@'))
            {
                $initCode[] = is_array($value) ? implode("\n", $value) : $value;
                continue;
            }

            /* Convert non-string to json */
            if(($value === true || $value === '') && !str_starts_with($name, 'data-'))
            {
                $pairs[] = $name;
            }
            else
            {
                if(!is_string($value)) $value = json_encode($value);

                $pairs[] = $name . '="' . static::encodeValue($value, str_starts_with($name, 'zui-create-')) . '"';
            }
        }

        if($initCode) $pairs[] = 'zui-init="$element.off(\'.zin.on\');' . static::encodeValue(implode(';', $initCode)) . '"';

        return implode(' ', $pairs);
    }

    /**
     * @param bool $skipEvents
     */
    public function toJSON($skipEvents = false)
    {
        $data      = $this->storedData;
        $styleData = $this->style->get();

        if(!empty($styleData)) $data['style'] = $styleData;
        if(!empty($this->class->toJSON())) $data['class'] = $this->class->toStr();

        if($skipEvents)
        {
            foreach($data as $name => $value)
            {
                if(str_starts_with($name, '@')) unset($data[$name]);
            }
        }
        return $data;
    }

    /**
     * @param mixed[]|string $skipProps
     * @param bool $skipFalse
     */
    public function skip($skipProps = array(), $skipFalse = false)
    {
        if(is_string($skipProps)) $skipProps = explode(',', $skipProps);

        $data = $this->toJSON();
        foreach($data as $name => $value)
        {
            if($value === null || in_array($name, $skipProps)) unset($data[$name]);
            if($skipFalse && $value === false) unset($data[$name]);
        }

        return $data;
    }

    /**
     * @param mixed[]|string $firstListProps
     */
    public function split($firstListProps = array())
    {
        if(is_string($firstListProps)) $firstListProps = explode(',', $firstListProps);

        $data       = $this->toJSON();
        $firstList  = array();
        $restList   = array();
        foreach($data as $name => $value)
        {
            if(in_array($name, $firstListProps)) $firstList[$name] = $value;
            else                                 $restList[$name]  = $value;
        }

        return array($firstList, $restList);
    }

    /**
     * @param mixed[]|string $pickProps
     */
    public function pick($pickProps = array())
    {
        if(is_string($pickProps)) $pickProps = explode(',', $pickProps);

        $data = $this->toJSON();
        foreach($data as $name => $value)
        {
            if($value === null || !in_array($name, $pickProps)) unset($data[$name]);
        }

        return $data;
    }

    /**
     * Clone a new instance
     *
     * @access public
     * @return props
     */
    public function copy()
    {
        $props = new props($this->storedData);
        $props->style = clone $this->style;
        $props->class = clone $this->class;
        return $props;
    }

    /**
     * @param mixed $value
     * @param bool $doubleEncode
     */
    public static function encodeValue($value, $doubleEncode = false)
    {
        return htmlspecialchars($value, ENT_COMPAT | ENT_SUBSTITUTE | ENT_HTML5, null, $doubleEncode);
    }
}
