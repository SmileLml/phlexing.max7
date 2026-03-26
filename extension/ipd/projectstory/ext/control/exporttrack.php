<?php
class myProjectstory extends projectstory
{
    /**
     * 导出矩阵。
     * Export track.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @param  string $browseType
     * @param  int    $param
     * @param  string $storyType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function exportTrack($projectID = 0, $productID = 0, $branch = '', $browseType = 'allstory', $param = 0, $storyType = '', $orderBy = 'id_desc')
    {
        $project = $this->loadModel('project')->getByID($projectID);
        $this->session->set('hasProduct', $project->hasProduct);
        $this->config->project->showGrades = null;

        echo $this->fetch('product', 'exporttrack', "productID=$productID&branch=$branch&projectID=$projectID&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy");
    }

}
