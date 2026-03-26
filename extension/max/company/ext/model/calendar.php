<?php
/**
 * The model file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
public function getEffort($parent, $begin, $end, $product = 0, $project = 0, $execution = 0, $user = '', $showUser = 'all', $userType = '')
{
    return $this->loadExtension('calendar')->getEffort($parent, $begin, $end, $product, $project, $execution, $user, $showUser, $userType);
}

public function getTodo($parent, $begin, $end, $pager = null)
{
    return $this->loadExtension('calendar')->getTodo($parent, $begin, $end, $pager);
}

public function getChildren($deptID)
{
    return $this->loadExtension('calendar')->getChildren($deptID);
}

/**
 * @param int $deptID
 * @param string $type
 * @param string $params
 */
public function getDeptUserPairs($deptID = 0, $type = 'inside', $params = '')
{
    return $this->loadExtension('calendar')->getDeptUserPairs($deptID, $type, $params);
}
