<?php
class project extends control
{
    public function approval($projectID)
    {
        $this->app->loadLang('baseline');
        $this->project->setMenu($projectID);

        if($_POST)
        {
            $this->dao->delete()->from(TABLE_APPROVALFLOWOBJECT)->where('root')->eq($projectID)->exec();
            foreach($this->post->object as $id => $object)
            {
                $data = new stdclass();
                $data->root       = $projectID;
                $data->flow       = $this->post->flow[$id];
                $data->objectType = $object;

                if(!$data->flow) continue;

                $this->dao->insert(TABLE_APPROVALFLOWOBJECT)->data($data)->exec();
            }

            return print($this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('approval', "project=$projectID"))));
        }

        $this->view->title        = $this->lang->project->approval;
        $this->view->flows        = $this->loadModel('approvalflow')->getPairs('noworkflow');
        $this->view->objectFlow   = $this->dao->select('objectType, flow')->from(TABLE_APPROVALFLOWOBJECT)->where('root')->eq($projectID)->fetchPairs();
        $this->view->projectID    = $projectID;
        $this->view->objectList   = $this->lang->baseline->objectList;
        $this->view->simpleFlowID = $this->dao->select('id')->from(TABLE_APPROVALFLOW)->where('code')->eq('simple')->fetch('id');
        $this->display();
    }
}
