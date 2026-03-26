<?php
/**
 * The zen file of workflowlayout module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     workflowlayout
 * @link        https://www.zentao.net
 */
class workflowlayoutZen extends workflowlayout
{
    /**
     * Assign vars for UI.
     *
     * @param  string $module
     * @param  string $action
     * @param  int    $ui
     * @access public
     * @return void
     */
    public function assignVarsForUI($module, $action, $ui = 0)
    {
        $this->loadModel('workflowcondition');
        unset($this->lang->workflowcondition->operatorList['include']);
        unset($this->lang->workflowcondition->operatorList['notinclude']);

        $allConditions = $this->workflowlayout->getAllGroupedConditions($module, $action);
        unset($allConditions[$ui]);

        $this->view->fields = $this->loadModel('workflowfield')->getFieldPairs($module);
        $this->view->uiList = $this->workflowlayout->getUIList($module, $action);
        $this->view->others = $allConditions;
        $this->view->module = $module;
        $this->view->action = $action;
    }

    /**
     * After response for UI.
     *
     * @param  string    $locateURL
     * @access public
     * @return void
     */
    public function afterResponseForUI($locateURL)
    {
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => array('name' => 'openInModal', 'params' => array($locateURL))));
    }
}
