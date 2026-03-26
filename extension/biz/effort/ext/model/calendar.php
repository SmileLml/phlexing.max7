<?php
/**
 * The model file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
public function getEfforts4Calendar($account = '', $year = '', $type = '', $id = 0)
{
    if($type == 'execution') return $this->loadModel('execution')->getEfforts4Calendar($id, $account, $year);
    return $this->loadExtension('calendar')->getEfforts4Calendar($account, $year);
}

public function printCell($col, $effort, $mode = 'datatable', $executions = array())
{
    return $this->loadExtension('calendar')->printCell($col, $effort, $mode, $executions);
}
