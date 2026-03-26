<?php
helper::importControl('repo');;
class myRepo extends repo
{
    /**
     * Ajax get committer.
     *
     * @param  int    $repoID
     * @param  string $entry
     * @param  int    $revision
     * @param  int    $line
     * @access public
     * @return void
     */
    public function ajaxGetCommitter($repoID, $entry, $revision, $line)
    {
        if($this->get->repoPath) $entry = $this->get->repoPath;
        $repo  = $this->repo->getRepoByID($repoID);
        $entry = $this->repo->decodePath($entry);

        $this->scm->setEngine($repo);
        $blames   = $this->scm->blame($entry, $revision);
        $committer = '';
        while($line > 0)
        {
            if(isset($blames[$line]['committer']))
            {
                $committer = $blames[$line]['committer'];
                break;
            }
            $line--;
        }
        die($committer);
    }
}
