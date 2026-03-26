<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
public function computeTaskEffort($date = '')
{
    return $this->loadExtension('effort')->computeTaskEffort($date);
}

public function getTaskEffort($execution = '')
{
    return $this->loadExtension('effort')->getTaskEffort($execution);
}
