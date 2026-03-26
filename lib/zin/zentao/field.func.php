<?php

/**
 * The field function file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'field.class.php';
require_once __DIR__ . DS . 'fieldlist.class.php';

/**
 * Define a field list.
 *
 * @param string                               $name     The name of field list.
 * @param string|array|field|fieldList|null ...$extends  The extend fields and field list.
 *
 * @return fieldList
 */
function defineFieldList($name, ...$extends)
{
    return fieldList::define($name, ...$extends);
}

function defineField($name, $listName = null)
{
    if(str_contains($name, '/') && is_null($listName))
    {
        list($listName, $name) = explode('/', $name);
    }

    if(is_null($listName)) $listName = fieldList::$currentName;
    $fieldList = fieldList::ensure($listName);
    return $fieldList->field($name);
}

function fieldList($name)
{
    return fieldList::ensure($name);
}

/**
 * @param string|object|mixed[]|null $nameOrProps
 */
function field($nameOrProps = null)
{
    return new field($nameOrProps);
}

/**
 * @param string|mixed[]|\zin\field|\zin\fieldList|null ...$args
 */
function useFields(...$args)
{
    return fieldList::build(...$args);
}
