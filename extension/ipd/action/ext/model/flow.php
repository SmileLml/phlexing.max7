<?php
/**
 * __construct
 *
 * @access public
 * @return void
 */
public function __construct($appName = '')
{
    parent::__construct($appName);
    if($this->app->isServing() || (defined('RUN_MODE') and RUN_MODE == 'api')) $this->loadExtension('flow')->loadCustomLang();
}

/**
 * Get deleted objects.
 *
 * @param  string $type    all|hidden
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 * @param string $objectType
 */
public function getTrashes($objectType, $type, $orderBy, $pager = null)
{
    return $this->loadExtension('flow')->getTrashes($objectType, $type, $orderBy, $pager);
}

/**
 * Transform the actions for display.
 *
 * @param mixed[] $actions
 * @access public
 * @return void
 */
public function transformActions($actions)
{
    return $this->loadExtension('flow')->transformActions($actions);
}
