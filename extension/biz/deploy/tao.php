<?php
/**
 * The tao file of deploy module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
class deployTao extends deployModel
{
    /**
     * 编辑步骤。
     * Edit step.
     *
     * @param  object    $step
     * @param  object    $oldStep
     * @access protected
     * @return bool
     */
    protected function editStep($step, $oldStep)
    {
        $this->dao->update(TABLE_DEPLOYSTEP)->data($step)->where('id')->eq($oldStep->id)->exec();
        if(dao::isError()) return false;

        $changes  = common::createChanges($oldStep, $step);
        $actionID = $this->loadModel('action')->create('deploystep', $oldStep->id, 'edited');
        $this->action->logHistory($actionID, $changes);

        return !dao::isError();
    }

    /**
     * 创建步骤。
     * Create step.
     *
     * @param  object    $step
     * @access protected
     * @return bool|int
     */
    protected function createStep($step)
    {
        $this->dao->insert(TABLE_DEPLOYSTEP)->data($step)->autoCheck()->exec();
        if(dao::isError()) return false;

        $stepID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('deploystep', $stepID, 'created');
        return $stepID;
    }
}
