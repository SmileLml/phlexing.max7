<?php
class zentaomaxBuild extends buildModel
{
    /**
     * 通过条件获取版本id:name的键值对。
     * Get build pairs by condition.
     *
     * @param  array|int  $productIdList
     * @param  string|int $branch
     * @param  string     $params       noempty|notrunk|noterminate|withbranch|hasproject|noDeleted|singled|noreleased|releasedtag, can be a set of them
     * @param  int        $objectID
     * @param  string     $objectType
     * @param  string     $buildIdList
     * @param  bool       $replace
     * @access public
     * @return array
     * @param int $system
     */
    public function getBuildPairs($productIdList, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true, $system = 0)
    {
        $builds = parent::getBuildPairs($productIdList, $branch, $params, $objectID, $objectType, $buildIdList, $replace, $system);

        /* Get other stage builds in same project. */
        $otherStageBuilds = array();
        $execution        = $this->dao->select('id,project,type')->from(TABLE_EXECUTION)->where('id')->eq($objectID)->fetch();
        if($execution and $execution->type == 'stage')
        {
            $otherStages = $this->dao->select('id')->from(TABLE_EXECUTION)
                ->where('project')->eq($execution->project)
                ->andWhere('deleted')->eq(0)
                ->andWhere('id')->ne((int)$objectID)
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->fetchPairs('id');

            $executionBuilds = $this->dao->select('t1.id, t1.name, t1.execution, t2.status AS executionStatus, t3.id AS releaseID, t3.status AS releaseStatus, t4.name AS branchName')
                ->from(TABLE_BUILD)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
                ->leftJoin(TABLE_RELEASERELATED)->alias('t5')->on("t1.id = t5.objectID AND t5.objectType = 'build'")
                ->leftJoin(TABLE_RELEASE)->alias('t3')->on('t5.release = t3.id')
                ->leftJoin(TABLE_BRANCH)->alias('t4')->on('FIND_IN_SET(t4.id, t1.branch)')
                ->where('t2.id')->in($otherStages)
                ->beginIF($productIdList)->andWhere('t1.product')->in($productIdList)->fi()
                ->beginIF($branch)->andWhere('t1.branch')->in("0,$branch")->fi()
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy('t1.date DESC, t1.id DESC')
                ->fetchAll('id');

            /* Set builds and filter terminate releases. */
            foreach($executionBuilds as $buildID => $build)
            {
                if(empty($build->releaseID) and (strpos($params, 'nodone') !== false) and ($build->executionStatus === 'done')) continue;
                if((strpos($params, 'noterminate') !== false) and ($build->releaseStatus === 'terminate')) continue;
                if(isset($builds[$buildID])) continue;
                $otherStageBuilds[$buildID] = $build->name;
            }
            if(empty($otherStageBuilds)) return $builds;

            /* if the build has been released, replace build name with release name. */
            $releases = $this->dao->select('t1.build, t1.name')->from(TABLE_RELEASE)->alias('t1')
                ->leftJoin(TABLE_RELEASERELATED)->alias('t3')->on("t1.id = t3.release AND t3.objectType = 'build'")
                ->leftJoin(TABLE_BUILD)->alias('t2')->on('t3.objectID = t2.id')
                ->where('t2.id')->in(array_keys($otherStageBuilds))
                ->beginIF($branch)->andWhere('t1.branch')->in("0,$branch")->fi()
                ->andWhere('t1.deleted')->eq(0)
                ->fetchPairs();
            foreach($releases as $buildIdList => $releaseName)
            {
                foreach(explode(',', trim($buildIdList, ',')) as $buildID)
                {
                    if(empty($buildID)) continue;
                    if(isset($builds[$buildID])) continue;
                    $otherStageBuilds[$buildID] = $releaseName;
                }
            }
        }

        return arrayUnion($builds, $otherStageBuilds);
    }
}
