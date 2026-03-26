<?php
/**
 * The control file of workflowrelation module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowrelation
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowrelation extends control
{
    /**
     * Set relations of a flow.
     *
     * @param  string $prev
     * @access public
     * @return void
     */
    public function admin($prev, $next = '')
    {
        if($_POST)
        {
            $result = $this->workflowrelation->save($prev);
            if(isset($result['result']) && $result['result'] == 'fail') return $this->send($result);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->app->loadLang('workflowfield', 'flow');

        $flow = $this->loadModel('workflow', 'flow')->getByModule($prev);

        $relations = $this->workflowrelation->getList($prev);

        $nextFlows = array();
        foreach($relations as $relation) $nextFlows[] = $relation->next;

        $flows = $this->workflow->getRelationPairs($prev, $nextFlows);

        $this->view->title      = $this->lang->workflowrelation->admin . ' - ' . $flow->name;
        $this->view->relations  = $relations;
        $this->view->prev       = $prev;
        $this->view->next       = $next;
        $this->view->flow       = $flow;
        $this->view->flows      = arrayUnion(array(''), $flows);
        $this->view->editorMode = 'advanced';
        $this->display();
    }
}
