<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * Create story from feedback.
     *
     * @param  int    $productID
     * @param string $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $objectID
     * @param  int    $bugID
     * @param  int    $planID
     * @param  int    $todoID
     * @param  string $extra
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function create($productID = 0, $branch = '', $moduleID = 0, $storyID = 0, $objectID = 0, $bugID = 0, $planID = 0, $todoID = 0, $extra = '', $storyType = 'story')
    {
        if($extra)
        {
            $extras = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extras, $params);
            foreach($params as $varName => $varValue) $$varName = $varValue;
        }

        if(!empty($fromType))
        {
            /* Set feedback menu. */
            $this->lang->feedback->menu->browse['subModule'] = 'story';
            $this->lang->feedback->menu->ticket['subModule'] = 'story';

            /* Get information and history of from object. */
            $fromObject = $this->loadModel($fromType)->getById($fromID);
            $this->view->fromType   = $fromType;
            $this->view->fromObject = $fromObject;
        }

        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $source      = isset($source) ? $source : '';
            $reportPairs = $source == 'researchreport' ? $this->loadModel('researchreport')->getPairs() : array();

            $this->view->source      = $source;
            $this->view->reportPairs = $reportPairs;

            $extra = trim($extra . ",source={source}", ',');
            $this->view->loadUrl = $this->createLink($storyType, 'create', "productID=$productID&branch=$branch&moduleID=$moduleID&story=$storyID&objectID=$objectID&bugID=$bugID&planID=$planID&todoID=$todoID&extra=$extra&storyType=$storyType");
        }

        $product = $this->loadModel('product')->fetchByID($productID);
        if(!empty($product->shadow))
        {
            $project  = $this->loadModel('project')->getByShadowProduct($productID);
            $objectID = $project->id;
            if(empty($project->multiple)) $objectID = $this->loadModel('execution')->getNoMultipleID($project->id);
        }

        return parent::create($productID, $branch, $moduleID, $storyID, $objectID, $bugID, $planID, $todoID, $extra, $storyType);
    }
}
