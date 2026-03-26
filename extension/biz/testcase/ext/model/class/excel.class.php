<?php
class excelTestcase extends testcaseModel
{
    public function setListValue($productID, $branch = 0)
    {
        $product    = $this->loadModel('product')->getByID($productID);
        $branches   = $this->loadModel('branch')->getPairs($productID);
        $typeList   = $this->lang->testcase->typeList;
        $priList    = $this->lang->testcase->priList;
        $stageList  = $this->lang->testcase->stageList;
        $statusList = $this->lang->testcase->statusList;

        $this->loadModel('tree');
        $modules = $product->type == 'normal' ? $this->tree->getOptionMenu($productID, 'case', 0, 0) : array();
        foreach($branches as $branchID => $branchName)
        {
            $branches[$branchID] = $branchName . "(#$branchID)";
            $modules = arrayUnion($modules, $this->tree->getOptionMenu($productID, 'case', 0, $branchID));
        }

        if(isset($_POST['stories']))
        {
            $stories = $this->loadModel('story')->getByList($this->post->stories);
        }
        else
        {
            $stories = $this->loadModel('story')->getProductStories($productID, $branch, 0, 'all', 'story', 'id_desc', false, false);
        }

        unset($typeList['']);
        unset($stageList['']);
        $storyList = array();
        foreach($modules as $id => $module) $modules[$id] .= "(#$id)";
        /* Group by story module for cascade. */
        foreach($stories as $id => $story)
        {
            $storyList[$story->module][$id] = "$story->title(#$story->id)";
            $stories[$id] = "$story->title(#$story->id)";
        }

        /* Unset useless module in select story. */
        if(isset($_POST['stories']))
        {
            foreach($modules as $moduleID => $moduleName)
            {
                if(!isset($storyList[$moduleID])) unset($modules[$moduleID]);
            }
        }

        foreach($branches as $id => $branchName)
        {
            if($branch and $id != $branch)
            {
                unset($branches[$id]);
                continue;
            }

            $branches[$id] .= "(#$id)";
        }

        if($product->type != 'normal')
        {
            $this->config->testcase->export->listFields[] = 'branch';

            $branches = $this->loadModel('branch')->getPairs($product->id, 'active');
            foreach($branches as $id => $branch) $branches[$id] .= "(#$id)";

            $this->post->set('branchList', array_values($branches));
        }

        if($this->config->edition != 'open') $this->loadModel('workflowfield')->setFlowListValue('testcase');

        $this->post->set('moduleList', $modules);
        $this->post->set('storyList',  ($this->post->fileType == 'xlsx' and $storyList) ? $storyList : $stories);
        $this->post->set('typeList',   $typeList);
        $this->post->set('priList',    $priList);
        $this->post->set('stageList',  $stageList);
        $this->post->set('statusList', $statusList);
        $this->post->set('listStyle',  $this->config->testcase->export->listFields);
        $this->post->set('extraNum',   0);
        $this->post->set('product',    $product->name);
        if(!empty($storyList)) $this->post->set('cascade', array('story' => 'module'));
        if($product->type != 'normal') $this->post->set('branchList', $branches);
    }
}
