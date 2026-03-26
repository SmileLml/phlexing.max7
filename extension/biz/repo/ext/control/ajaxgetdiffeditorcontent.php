<?php
helper::importControl('repo');
class myRepo extends repo
{
    /**
     * 获取代码对比编辑器内容。
     * Get diff editor content by ajax.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $oldRevision
     * @param  string $newRevision
     * @param  int    $showBug     // Used for biz.
     * @param  string $encoding
     * @access public
     * @return void
     */
    public function ajaxGetDiffEditorContent($repoID, $objectID = 0, $entry = '', $oldRevision = '', $newRevision = '', $showBug = 0, $encoding = '')
    {
        $this->app->loadConfig('misc');
        $this->view->canReview = $this->loadModel('common')->checkExtLicense('devops', zget($this->config->misc, 'featureLimit', ''));
        parent::ajaxGetDiffEditorContent($repoID, $objectID, $entry, $oldRevision, $newRevision, $showBug, $encoding);
    }
}
