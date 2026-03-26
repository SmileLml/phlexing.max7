<?php

/**
 * The field list class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'field.class.php';

class fieldList
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed[]
     */
    public $fields = array();

    /**
     * @var mixed[]|null
     */
    public $labelData;

    /**
     * @var mixed[]|null
     */
    public $valueData;

    /**
     * @var mixed[]|null
     */
    public $autoLoadRule;

    /**
     * @var mixed[]|null
     */
    public $defaultOrders;

    /**
     * @var mixed[]|null
     */
    public $ordersForFull;

    /**
     * @param string|null $name
     * @param mixed[]|null $fields
     */
    public function __construct($name = null, $fields = null)
    {
        if(is_null($name))
        {
            $this->name = '';
        }
        else
        {
            $this->name = $name;

            if(isset(static::$map[$name]))
            {
                trigger_error("[ZIN] The fieldList named \"$name\" already exist.", E_USER_ERROR);
            }

            static::$map[$name] = $this;
        }

        if(is_array($fields))
        {
            foreach($fields as $field)
            {
                $this->add($field);
            }
        }
    }

    /**
     * Magic method for using field.
     *
     * @access public
     * @param  string $name  - field name.
     * @return field
     * @param mixed[] $args
     */
    public function __call($name, $args)
    {
        return $this->field($name);
    }

    /**
     * @param mixed[]|object|null $fieldProps
     * @param string $name
     */
    public function field($name, $fieldProps = null)
    {
        $field = $this->get($name);
        if(is_null($field))
        {
            $field = new field($name, $this);
            $this->add($field);
        }
        if(!is_null($fieldProps)) $field->set($fieldProps);
        return $field;
    }

    /**
     * @param string $name
     */
    public function get($name)
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : null;
    }

    /**
     * @param \zin\field|mixed[]|\zin\stdClass $field
     */
    public function add($field)
    {
        if(!($field instanceof field)) $field = get_object_vars($field);
        if(is_array($field))           $field = new field($field);

        $this->fields[$field->getName()] = $field;
        return $this;
    }

    /**
     * @param string|mixed[]|\zin\field|\zin\fieldList|null $info
     */
    public function merge($info)
    {
        if(is_null($info)) return $this;

        if(is_string($info))
        {
            if(str_starts_with($info, '!'))
            {
                return $this->remove(substr($info, 1));
            }

            if(str_contains($info, '/'))
            {
                $info = static::getListFields($info);
            }
            else
            {
                $listNames = explode(',', $info);
                $info = array();
                foreach($listNames as $listName)
                {
                    $fieldList = static::getList($listName);
                    if($fieldList) $info[] = $fieldList;
                }
            }
        }

        if(is_array($info))
        {
            foreach($info as $field)
            {
                $this->merge($field);
            }
            return $this;
        }

        if($info instanceof field)     return $this->mergeField($info);
        if($info instanceof fieldList) return $this->mergeList($info);
        return $this;
    }

    /**
     * @param \zin\field $field
     */
    public function mergeField($field)
    {
        $oldField = $this->get($field->getName());
        if(is_null($oldField)) return $this->add($field);

        $oldField->merge($field);
        return $this;
    }

    /**
     * @param \zin\fieldList $fieldList
     */
    public function mergeList($fieldList)
    {
        foreach($fieldList->fields as $field)
        {
            if(is_null($field)) continue;
            $this->mergeField($field);
        }
        return $this;
    }

    /**
     * @param string ...$names
     */
    public function remove(...$names)
    {
        foreach($names as $name)
        {
            $nameList = explode(',', $name);
            foreach($nameList as $nameItem)
            {
                unset($this->fields[$nameItem]);
            }
        }
        return $this;
    }

    /**
     * @param string|mixed[] $moveNames
     * @param string $beforeName
     */
    public function moveBefore($moveNames, $beforeName)
    {
        if($beforeName === '$BEGIN' || $beforeName === '$END')
        {
            return $this->moveAfter($moveNames, $beforeName);
        }

        $fields = $this->fields;
        $keys   = array_keys($fields);

        $beforeIndex = array_search($beforeName, $keys);
        if($beforeIndex === false) return $this;

        $moveNames    = is_string($moveNames) ? explode(',', $moveNames) : $moveNames;
        $sortedFields = array();
        foreach($keys as $key)
        {
            if(in_array($key, $moveNames)) continue;
            if($key === $beforeName)
            {
                foreach($moveNames as $moveName)
                {
                    $sortedFields[$moveName] = $fields[$moveName];
                }
            }
            $sortedFields[$key] = $fields[$key];
        }

        $this->fields = $sortedFields;
        return $this;
    }

    /**
     * @param string|mixed[] $moveNames
     * @param string $afterName
     */
    public function moveAfter($moveNames, $afterName)
    {
        $names = array_merge(array($afterName), (is_string($moveNames) ? explode(',', $moveNames) : $moveNames));
        $this->fields = static::sortFields($this->fields, $names);

        return $this;
    }

    /**
     * @param string|mixed[] $moveNames
     */
    public function moveToBegin($moveNames)
    {
        return $this->moveAfter($moveNames, '$BEGIN');
    }

    /**
     * @param string|mixed[] $moveNames
     */
    public function moveToEnd($moveNames)
    {
        return $this->moveAfter($moveNames, '$END');
    }

    /**
     * @param string|mixed[] ...$sortNames
     */
    public function sort(...$sortNames)
    {
        foreach($sortNames as $names)
        {
            $this->fields = static::sortFields($this->fields, $names);
        }
        return $this;
    }

    public function names()
    {
        return array_keys($this->fields);
    }

    /**
     * @param string|mixed[] $names
     * @param string|mixed[] $prop
     * @param mixed $value
     */
    public function set($names, $prop, $value = null)
    {
        $names = is_string($names) ? explode(',', $names) : $names;
        foreach($names as $name)
        {
            $field = $this->get($name);
            if(is_null($field)) continue;

            if(is_array($prop)) $field->set($prop);
            else                $field->$prop($value);
        }
        return $this;
    }

    /**
     * @param mixed[]|object $labelData
     */
    public function setLabelData($labelData)
    {
        if(is_object($labelData)) $labelData = get_object_vars($labelData);
        $this->labelData = $labelData;
        return $this;
    }

    /**
     * @param mixed[]|object $valueData
     */
    public function setValueData($valueData)
    {
        if(is_object($valueData)) $valueData = get_object_vars($valueData);
        $this->valueData = $valueData;
        return $this;
    }

    /**
     * @param string|mixed[] $targets
     * @param string|mixed[]|null $loadNames
     */
    public function autoLoad($targets, $loadNames = null)
    {
        if(is_string($targets)) $targets = array($targets => $loadNames);
        $this->autoLoadRule = is_null($this->autoLoadRule) ? $targets : array_merge($this->autoLoadRule, $targets);
        return $this;
    }

    /**
     * @param string|mixed[] ...$orders
     */
    public function orders(...$orders)
    {
        $this->defaultOrders = $orders;
        return $this;
    }

    /**
     * @param string|mixed[] ...$orders
     */
    public function fullModeOrders(...$orders)
    {
        $this->ordersForFull = $orders;
        return $this;
    }

    /**
     * @param string|mixed[]|null $names
     */
    public function toList($names = null)
    {
        if(is_null($names))
        {
            $list = $this->fields;
        }
        else
        {
            if(is_string($names)) $names = explode(',', $names);

            $list = array();
            foreach($names as $name)
            {
                $field = $this->get($name);
                if($field) $list[$name] = $field;
            }
        }

        if(!empty($this->defaultOrders))
        {
            foreach($this->defaultOrders as $orders) $list = static::sortFields($list, $orders);
        }

        return $list;
    }

    /**
     * @param string|mixed[]|null $names
     */
    public function toArray($names = null)
    {
        $list = array();
        foreach($this->toList($names) as $field)
        {
            $list[$field->getName()] = $field->toArray();
        }
        return $list;
    }

    /**
     * @var mixed[]
     */
    protected static $map = array();

    /**
     * @var string|null
     */
    public static $currentName;

    /**
     * @param string|mixed[]|\zin\field|\zin\fieldList|null ...$args
     * @param string $currentName
     */
    public static function define($currentName, ...$args)
    {
        static::$currentName = $currentName;
        $fieldList = static::ensure($currentName);

        if(!empty($args)) static::extend($fieldList, ...$args);
        return $fieldList;
    }

    /**
     * @param string $listName
     */
    public static function getList($listName)
    {
        return isset(static::$map[$listName]) ? static::$map[$listName] : null;
    }

    /**
     * @param string $listName
     * @param string|null $fieldNames
     */
    public static function getListFields($listName, $fieldNames = null)
    {
        if(is_null($fieldNames)) list($listName, $fieldNames) = explode('/', $listName);
        if(is_null($fieldNames)) return null;

        $fieldList = static::getList($listName);
        return is_null($fieldList) ? array() : $fieldList->toList($fieldNames);
    }

    /**
     * @param string $name
     */
    public static function ensure($name)
    {
        if(isset(static::$map[$name])) return static::$map[$name];
        return new fieldList($name);
    }

    public static function current()
    {
        if(is_null(static::$currentName))
        {
            trigger_error("[ZIN] The current fieldList name is not defined.", E_USER_ERROR);
        }
        return static::ensure(static::$currentName);
    }

    /**
     * @param string|mixed[]|\zin\field|\zin\fieldList|null ...$args
     * @param \zin\fieldList $fieldList
     */
    public static function extend($fieldList, ...$args)
    {
        foreach($args as $arg)
        {
            if(is_null($arg)) continue;
            $fieldList->merge($arg);
        }
        return $fieldList;
    }

    /**
     * @param string|mixed[]|\zin\field|\zin\fieldList|null ...$args
     */
    public static function build(...$args)
    {
        $fieldList = new fieldList();
        return static::extend($fieldList, ...$args);
    }

    /**
     * @param string|mixed[] $names
     * @param mixed[] $fields
     */
    public static function sortFields(&$fields, $names)
    {
        if(is_string($names)) $names = explode(',', $names);
        if(empty($names) || count($names) < 2) return $fields;

        $keys         = array_keys($fields);
        $firstName    = array_shift($names);
        $sortedFields = array();

        if($firstName === '$BEGIN')
        {
            foreach($names as $name) $sortedFields[$name] = $fields[$name];
            foreach($keys as $key)
            {
                if(in_array($key, $names)) continue;
                $sortedFields[$key] = $fields[$key];
            }
            return $sortedFields;
        }

        if($firstName === '$END')
        {
            foreach($keys as $key)
            {
                if(in_array($key, $names)) continue;
                $sortedFields[$key] = $fields[$key];
            }
            foreach($names as $name) $sortedFields[$name] = $fields[$name];
            return $sortedFields;
        }

        foreach($keys as $key)
        {
            if($key === $firstName)
            {
                $sortedFields[$key] = $fields[$key];
                foreach($names as $name) $sortedFields[$name] = \zget($fields, $name, '');
            }
            elseif(in_array($key, $names))
            {
                continue;
            }
            $sortedFields[$key] = $fields[$key];
        }

        return $sortedFields;
    }
}
