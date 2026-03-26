<?php
helper::import('../../control.php');
class myRepo extends repo
{
    /**
     * 代码对比。
     * Show diff.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $entry
     * @param  string $oldRevision
     * @param  string $newRevision
     * @param  int    $showBug
     * @param  string $encoding
     * @param  int    $isBranchOrTag
     * @access public
     * @return void
     */
    public function diff($repoID = 0, $objectID = 0,  $entry = '', $oldRevision = '', $newRevision = '', $showBug = 0, $encoding = '', $isBranchOrTag = 0)
    {
        $this->repoZen->setBackSession('diff', true);

        $this->view->isBranchOrTag = $isBranchOrTag;
        parent::diff($repoID, $objectID, $entry, $oldRevision, $newRevision, $showBug, $encoding, $isBranchOrTag);
    }
}
