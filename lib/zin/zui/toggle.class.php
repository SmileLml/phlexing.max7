<?php
/**
 * The zui toggle class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'core' . DS . 'set.class.php';

class toggle
{
    /**
     * @param string $name
     * @param mixed[] $args
     */
    public static function __callStatic($name, $args)
    {
        return toggle($name, empty($args) ? array() : $args[0]);
    }
}

/**
 * Add data-toggle=* to widget element and set other data attributes.
 *
 * @param  string $name
 * @param  array  $dataset
 */
function toggle($name, $dataset = array())
{
    $props = array('zui-toggle' => $name);
    if(!empty($dataset)) $props["zui-toggle-$name"] = $dataset;
    return set($props);
}
