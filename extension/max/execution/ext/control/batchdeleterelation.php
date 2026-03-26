<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     execution
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class execution extends control
{
    /**
     * 批量删除任务关系。
     * Batch delete relations.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchDeleteRelation($projectID, $executionID)
    {
        foreach($this->post->relationIdList as $relationID) $this->execution->deleteRelation($relationID);
        return $this->sendSuccess(array('load' => inlink('relation', "executionID=$executionID")));
    }
}
