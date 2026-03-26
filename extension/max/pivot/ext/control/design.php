<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * Design pivot.
     *
     * @param  int    $pivotID
     * @param  string $from browse|preview
     * @param  string $params
     * @access public
     * @return void
     */
    public function design($pivotID, $from = 'browse', $params = '')
    {
        $this->pivot->checkAccess($pivotID, 'browse');

        $dimension = $this->session->backDimension ? $this->session->backDimension : 0;
        $group     = $this->session->backGroup ? $this->session->backGroup : '';

        $pivot = $this->pivot->getByID($pivotID, true, 'design');
        if($this->pivotZen->isPublishAction($_POST))
        {
            $pivotState = $this->pivotZen->initPivotState($pivot);
            $this->pivot->update($pivotID, $pivotState);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('pivot', $pivotID, 'pivotreleased');

            $this->pivotZen->clearCache($pivotID);
            $pivot = $this->pivot->getByID($pivotID);

            if($from == 'preview' && $pivot->stage == 'published')
            {
                unset($_SESSION['backDimension']);
                unset($_SESSION['backGroup']);

                $locate = $this->createLink('pivot', 'preview', "dimension=$dimension&group=$group&module=pivot&method=show&params=$params");
            }
            else
            {
                $locate = $this->createLink('pivot', 'browse');
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $objectFields = $this->loadModel('dataview')->getObjectFields();

        $this->loadModel('dimension')->setSwitcherMenu($pivot->dimension);

        $groups = $this->loadModel('tree')->getGroupPairs($pivot->dimension, 0, 2, 'pivot');
        if(!in_array($pivot->group, $groups)) $groups = arrayUnion($this->tree->getModulesName(array($pivot->group)), $groups);

        $this->pivotZen->handleDesignAction($pivot);

        $this->view->title        = $this->lang->pivot->design;
        $this->view->from         = $from;
        $this->view->params       = $params;
        $this->view->backLink     = $from == 'browse' ? inlink('browse', "dimensionID=$pivot->dimension") : inlink('preview', "dimension=$dimension&group=$group&module=pivot&method=show&params=$params");
        $this->view->dimensionID  = $pivot->dimension;
        $this->view->saveLink     = inlink('design', "pivotID=$pivotID");
        $this->view->pivot        = $pivot;
        $this->view->data         = $pivot;
        $this->view->fieldList    = $this->pivot->getPivotFieldList($pivotID);
        $this->view->groups       = $groups;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->objectFields = $objectFields;
        $this->view->tableOptions = $this->loadModel('bi')->prepareFieldObjects();
        $this->view->tableList    = $this->bi->getTableList();
        $this->display();
    }
}
