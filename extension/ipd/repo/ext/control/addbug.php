<?php
helper::importControl('repo');;
class myRepo extends repo
{
    /**
     * addBug
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function addBug($repoID)
    {
        $this->loadModel('common')->checkPriv();

        if(!empty($_POST))
        {
            global $config;
            $file  = $this->post->file;
            $v1    = $this->post->fromReversion;
            $v2    = $this->post->revision;
            $begin = $this->post->begin;
            $end   = $this->post->end;
            $v1    = strpos($v1, '^') !== false ? substr($v1, 0, -1) : $v1;
            $v2    = strpos($v2, '^') !== false ? substr($v2, 0, -1) : $v2;
            $bug   = form::data($config->repo->form->addBug)
                ->setIF(!$this->post->entry, 'entry', $file)
                ->add('openedBy', $this->app->user->account)
                ->add('repo', $repoID)
                ->add('lines', $begin . ',' . $end)
                ->add('v1', $v1)
                ->add('v2', $v2)
                ->remove('begin,end,uid,fromReversion,revision,file')
                ->get();
            $bug = $this->loadModel('file')->processImgURL($bug, 'steps',(string)$this->post->uid);

            $result = $this->repo->saveBug($repoID, $bug);
            if($result['result'] === 'fail')
            {
                $result['message'] = $result['message'];
                return $this->send($result);
            }

            $bugID      = $result['id'];
            $repo       = $this->repo->getById($repoID);
            $file       = $this->repo->decodePath($file);
            $entry      = $repo->name . '/' . $file;
            $location   = sprintf($this->lang->repo->reviewLocation, $entry, $repo->SCM != 'Subversion' ? substr($v2, 0, 10) : $v2, $begin, $end);
            $changeFile = $this->repo->encodePath("{$file}#{$begin},{$end}");
            if(empty($v1))
            {
                $revision = $repo->SCM != 'Subversion' ? substr($v2, 0, 10) : $v2;
                $link = $this->repo->createLink('view', "repoID=$repoID&objectID=0&entry={$changeFile}&revision=$v2&showBug=1", '', true) . "#L{$begin}";
            }
            else
            {
                $revision  = $repo->SCM != 'Subversion' ? substr($v1, 0, 10) : $v1;
                $revision .= ' : ';
                $revision .= $repo->SCM != 'Subversion' ? substr($v2, 0, 10) : $v2;
                $link = $this->repo->createLink('diff', "repoID=$repoID&objectID=0&entry={$changeFile}&oldRevision=$v1&newRevision=$v2&showBug=1", '', true) . "#L{$begin}";
            }

            /* search commit. */
            $commitID = empty($v2) ? $v1 : $v2;
            $this->app->loadClass('pager', true);
            $pager = new pager(0, 1, 1);
            $pager->recPerPage = 1;

            if(in_array($repo->SCM, $this->config->repo->notSyncSCM))
            {
                $query = new stdclass();
                $query->commit = $commitID;
            }
            else
            {
                $query = "t1.revision = '{$commitID}'";
            }

            $commits = $this->repo->getCommits($repo, '', '', 'dir', $pager, '', '', $query);
            if(!empty($commits[0]))
            {
                $commit = $commits[0];
                $historyLog = new stdclass();
                if(!empty($commit->author->identity->name))
                {
                    $historyLog->committer = $commit->author->identity->name;
                }else if(!empty($commit->committer_name))
                {
                    $historyLog->committer = $commit->committer_name;
                }else
                {
                    $historyLog->committer = '';
                }
                /* Record code commit relationship. */
                $historyLog->revision = $commit->revision;
                $historyLog->comment  = $commit->message;
                $historyLog->time     = date("Y-m-d H:i:s", strtotime($commit->time));
                $this->repo->saveCommit($repo->id, array('commits' => [$historyLog]), 0);
                $revisions = $this->dao->select('id')->from(TABLE_REPOHISTORY)
                    ->where('revision')->in($commit->revision)
                    ->andWhere('repo')->eq($repoID)
                    ->fetchPairs('id');
                $this->loadModel('bug')->updateLinkedCommits((int)$bugID, $repoID, $revisions);
            }
            $actionID = $this->loadModel('action')->create('bug', $bugID, 'repoCreated', '', html::a($link, $location, '', "class='iframe'"));
            $this->loadModel('mail')->sendmail($bugID, $actionID);

            return $this->send($result);
        }
    }
}
