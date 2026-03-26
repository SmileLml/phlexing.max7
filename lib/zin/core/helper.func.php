<?php
/**
 * The helpers of zin lib of ZenTaoPMS.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

/**
 * Check if the debug mode is on.
 *
 * @param int $level - The min debug level.
 * @return bool
 */
function isDebug($level = 1)
{
    global $config;
    $debug = isset($config->debug) ? $config->debug : 0;
    if(is_bool($debug)) $debug = $debug ? 1 : 0;

    return $debug >= $level;
}

/**
 * Trigger an error.
 *
 * @param string $message - The error message.
 * @param int    $level   - The error level.
 * @param string $scope   - The error scope.
 * @return void
 */
function triggerError($message, $level = E_USER_ERROR, $scope = 'ZIN')
{
    trigger_error("[$scope] $message", $level);
}
