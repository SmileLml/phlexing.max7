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
public function getTasks4Calendar($executionID, $status = 'all', $orderBy = 'status_asc, id_desc', $pager = null)
{
    return $this->loadExtension('calendar')->getTasks4Calendar($executionID, $status, $orderBy, $pager);
}

public function getEfforts4Calendar($executionID, $account = '', $year = '')
{
    return $this->loadExtension('calendar')->getEfforts4Calendar($executionID, $account, $year);
}
