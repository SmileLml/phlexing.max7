<?php
helper::importControl('story');
class mystory extends story
{
    /**
     * @param int $productID
     * @param string $branch
     * @param string $storyType
     */
    public function exportTemplate($productID, $branch = '0', $storyType = 'story')
    {
        $this->loadModel('transfer');
        $this->loadModel('file');

        if(isset($_POST['storyType'])) $storyType = $_POST['storyType'];

        if($storyType != 'story') $this->story->replaceURLang($storyType);

        /* Append extend fields. */
        $extendFields = $this->loadModel('flow')->getExtendFields($storyType, 'exportTemplate');
        $extendCols   = $this->loadModel('flow')->buildDtableCols($extendFields);
        $this->config->story->dtable->fieldList = array_merge($this->config->story->dtable->fieldList, $extendCols);
        $this->config->story->templateFields   .= ',' . implode(',', array_keys($extendCols));

        /* If or vision, unset plan field. */
        if($this->config->vision == 'or') $this->config->story->templateFields = str_replace(',plan,', ',', $this->config->story->templateFields);

        /* If edition is not ipd, and storyType is not story, unset level field. */
        if($this->config->edition != 'ipd')
        {
            if($storyType != 'story')
            {
                $this->config->story->templateFields = str_replace(',level,', ',', $this->config->story->templateFields);
                $this->lang->excel->help->$storyType = $this->lang->story->noLevelNotice;
                unset($this->config->excel->tipsHeight->$storyType);
            }
            else
            {
                $this->lang->excel->help->story        .= "\n" . $this->lang->story->twoLevelNotice;
                $this->config->excel->tipsHeight->story = 45;
            }
        }

        if($_POST)
        {
            $product = $this->loadModel('product')->getById($productID);
            if($product->type == 'normal')  $this->config->story->templateFields = str_replace('branch,', '', $this->config->story->templateFields);
            if($product->shadow)            $this->config->story->templateFields = str_replace(array('product,', 'branch,'), array('', ''), $this->config->story->templateFields);
            if(in_array($storyType, array('epic', 'requirement'))) $this->config->$storyType->templateFields = $this->config->story->templateFields;

            $this->config->story->dtable->fieldList['module']['dataSource']   = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => ['rootID' => (int)$productID, 'type' => 'story', 'startModule' => 0, 'branch' => 'all']);
            $this->config->story->dtable->fieldList['reviewer']['control']    = 'multiple';
            $this->config->story->dtable->fieldList['reviewer']['dataSource'] = array('module' => 'story', 'method' => 'getProductReviewers', 'params' => array('productID' => (int)$productID));
            $this->config->story->dtable->fieldList['plan']['dataSource']['params'] = array('productIdList' => (int)$productID, 'branch' => 'all', 'param' => '', 'skipParent' => true);
            $this->session->set('storyTransferParams', array('productID' => $productID, 'branch' => $branch));

            $this->post->set('product', $product->name);
            $this->fetch('transfer', 'exportTemplate', "model=$storyType&params=productID=". $productID);
        }

        if($this->app->tab == 'project')
        {
            $project   = $this->loadModel('project')->fetchByID($this->session->project);
            $storyType = $project->storyType;
        }

        if($storyType)
        {
            foreach($this->lang->story->typeList as $key => $value)
            {
                if(strpos(",$storyType,", ",$key,") === false) unset($this->lang->story->typeList[$key]);
            }
        }

        $this->view->isProjectStory = $this->app->tab == 'project';
        $this->view->typeList       = $this->lang->story->typeList;
        $this->display();
    }
}
