<?php
class myDesign extends design
{
    public function submit($productID = 0, $type = '')
    {
        $projectID = !empty($product->project) ? $product->project : $this->session->project;
        $project   = $this->loadModel('project')->getByID($projectID);
        $typeList  = (!empty($project) and $project->model == 'waterfall') ? $this->lang->design->typeList : $this->lang->design->plusTypeList;

        if($_POST)
        {
            $reviewRange = $this->post->range;
            $object      = $this->post->object;
            $product     = $this->loadModel('product')->getByID($productID);
            $checkedItem = $reviewRange == 'all' ? '' : $this->cookie->checkedItem;
            unset($_GET['onlybody']);

            return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => $this->createLink('review', 'create', "project={$projectID}&object=$object&productID=$productID&reviewRange=$reviewRange&checkedItem={$checkedItem}")));
        }

        $this->view->typeList = $typeList;
        $this->view->type     = strtoupper($type);
        $this->display();
    }
}
