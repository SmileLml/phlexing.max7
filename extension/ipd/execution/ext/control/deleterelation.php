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
class execution extends control
{
    /**
     * 删除任务关系。
     * Delete Relation.
     *
     * @param  int    $relationID
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function deleteRelation($relationID, $projectID, $executionID)
    {
        $this->execution->deleteRelation($relationID);
        return $this->sendSuccess(array('load' => inlink('relation', "executionID=$executionID")));
    }
}
