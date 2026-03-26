<?php
helper::importControl('repo');
class myRepo extends repo
{
    /**
     * 获取代码详情的编辑器内容。
     * Get editor content by ajax.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $revision
     * @param  int    $showBug
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function ajaxGetEditorContent($repoID, $objectID = 0, $entry = '', $revision = 'HEAD', $showBug = 0, $encoding = '')
    {
        $this->app->loadConfig('misc');
        $this->view->canReview = $this->loadModel('common')->checkExtLicense('devops', zget($this->config->misc, 'featureLimit', ''));
        parent::ajaxGetEditorContent($repoID, $objectID, $entry, $revision, (int)$showBug, $encoding);
    }
}
