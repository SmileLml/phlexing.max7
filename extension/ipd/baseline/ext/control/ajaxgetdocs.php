<?php
helper::importControl('baseline');
class myBaseline extends baseline
{
    public function ajaxGetDocs($template = '', $from = 'review', $projectID = 0, $contentType = '', $viewType = 'html')
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if($project->model == 'ipd')
        {
            $templates = $this->dao->select('id, title')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->andWhere('project')->eq($projectID)
                ->andWhere('status')->eq('normal')
                ->andWhere('(acl')->eq('open')
                ->orWhere("FIND_IN_SET('{$this->app->user->account}', users)")
                ->orWhere("addedBy")->eq($this->app->user->account)
                ->markRight(1)
                ->orderBy('id desc')
                ->fetchPairs();

            return print(html::select('doc[]', array(0 => '') + $templates, '', "class='form-control chosen' multiple"));
        }

        return parent::ajaxGetDocs($template, $from, $projectID, $contentType, $viewType);
    }
}
