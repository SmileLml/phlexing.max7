<?php
/**
 * The model file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 * @param mixed[]|string $skipProductIDList
 * @param int|string|mixed[] $appendStoryID
 * @param string $account
 * @param int $limit
 * @param string $type
 */
public function getUserStoryPairs($account, $limit = 10, $type = 'story', $skipProductIDList = array(), $appendStoryID = 0)
{
    return $this->loadExtension('calendar')->getUserStoryPairs($account, $limit, $type, $skipProductIDList, $appendStoryID);
}
