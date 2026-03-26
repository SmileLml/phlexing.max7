<?php

use function PHPSTORM_META\elementType;

class ipdStoryTao extends storyTao
{
    /**
     * 根据需求类型，构建看板列数据。
     * Build cols data by storyType.
     *
     * @param  string  $storyType   demand|epic|requirement|story
     * @access public
     * @return array
     */
    public function buildTrackCols($storyType)
    {
        $storyGrade = $this->getGradeGroup();

        $cols = array();
        $cols['demand'] = $this->buildTrackCol('demand', $this->lang->demand->common, '-1');
        if(isset($this->lang->story->typeList['epic']))  $cols['epic']        = $this->buildTrackCol('epic',        $this->lang->ERCommon, empty($storyGrade['epic'])        ? 0 : -1);
        if($storyType != 'story')                        $cols['requirement'] = $this->buildTrackCol('requirement', $this->lang->URCommon, empty($storyGrade['requirement']) ? 0 : -1);

        $cols['story']     = $this->buildTrackCol('story',     $this->lang->storyCommon, empty($storyGrade['story']) ? 0 : -1);
        $cols['project']   = $this->buildTrackCol('project',   $this->lang->story->project);
        $cols['execution'] = $this->buildTrackCol('execution', $this->lang->story->execution);
        $cols['design']    = $this->buildTrackCol('design',    $this->lang->story->design);
        $cols['commit']    = $this->buildTrackCol('commit',    $this->lang->story->repoCommit);
        $cols['task']      = $this->buildTrackCol('task',      $this->lang->story->tasks);
        $cols['bug']       = $this->buildTrackCol('bug',       $this->lang->story->bugs);
        $cols['case']      = $this->buildTrackCol('case',      $this->lang->story->cases);

        foreach($storyGrade as $type => $grades)
        {
            if(!isset($cols[$type])) continue;
            foreach($grades as $grade) $cols["{$type}_{$grade->grade}"] = $this->buildTrackCol("{$type}_{$grade->grade}", $grade->name, $type);
        }

        $cols['demand_1'] = $this->buildTrackCol('demand_1', $this->lang->demand->gradeName->OR1, 'demand');
        $cols['demand_2'] = $this->buildTrackCol('demand_2', $this->lang->demand->gradeName->OR2, 'demand');

        return array_values($cols);
    }

    /**
     * 构建看板项数据。
     * Build items data by storyType.
     *
     * @param  array  $allStories
     * @param  array  $leafNodes
     * @param  string $storyType    epic|requirement|story
     * @param  array  $demandGroup
     * @access public
     * @return array
     */
    public function buildTrackItems($allStories, $leafNodes, $storyType, $demandGroup = array())
    {
        $storyIdList  = array_keys($leafNodes);
        $projectGroup = $this->getProjectsForTrack($storyIdList);
        $designGroup  = $this->getDesignsForTrack($storyIdList);

        $projects   = zget($projectGroup, 'project', array());
        $executions = zget($projectGroup, 'execution', array());
        $designs    = zget($designGroup, 'design', array());
        $commits    = zget($designGroup, 'commit', array());
        $tasks      = $this->getTasksForTrack($storyIdList);
        $cases      = $this->dao->select('id,project,pri,status,color,title,story,lastRunner,lastRunResult')->from(TABLE_CASE)->where('story')->in($storyIdList)->andWhere('deleted')->eq(0)->orderBy('project')->fetchGroup('story', 'id');
        $bugs       = $this->dao->select('id,project,pri,status,color,title,story,assignedTo,severity')->from(TABLE_BUG)->where('story')->in($storyIdList)->andWhere('deleted')->eq(0)->orderBy('project')->fetchGroup('story', 'id');
        $storyGrade = $this->getGradeGroup();

        $items = array();

        foreach($demandGroup as $demandID => $demandInfo)
        {
            $laneName = "lane_$demandID";

            foreach(array_keys($demandInfo['stories']) as $storyID)
            {
                if(!isset($allStories[$storyID])) continue;

                $story = clone $allStories[$storyID];

                if($storyType == 'requirement' && $story->type == 'epic') continue;
                if($storyType == 'story' && ($story->type == 'requirement' || $story->type == 'epic')) continue;
                if(!isset($storyGrade[$story->type][$story->grade])) continue;

                $colName = "{$story->type}_{$story->grade}";
                $story->storyType = $story->type;
                unset($story->type);

                $items[$laneName][$colName][] = $story;

                if(in_array($storyID, $storyIdList))
                {
                    $items[$laneName]['project']   = array_values(zget($projects,   $storyID, array()));
                    $items[$laneName]['execution'] = array_values(zget($executions, $storyID, array()));
                    $items[$laneName]['design']    = array_values(zget($designs,    $storyID, array()));
                    $items[$laneName]['commit']    = array_values(zget($commits,    $storyID, array()));
                    $items[$laneName]['task']      = array_values(zget($tasks,      $storyID, array()));
                    $items[$laneName]['bug']       = array_values(zget($bugs,       $storyID, array()));
                    $items[$laneName]['case']      = array_values(zget($cases,      $storyID, array()));
                }
            }

            $demand = $demandGroup[$demandID]['demand'];
            if($demand->parent <= 0)
            {
                $items[$laneName]['demand_1'][] = $demand;
            }
            else
            {
                $items[$laneName]['demand_2'][] = $demand;
                $items[$laneName]['demand_1'][] = $demandGroup[$demand->parent]['demand'];
            }
        }

        return $items;
    }

    /**
     * 根据叶子结点数据，构建看板泳道数据。
     * Build lanes data by leaf node.
     *
     * @param  array    $leafNodes
     * @param  string   $storyType  demand|epic|requirement|story
     * @param  array    $demands
     * @access public
     * @return array
     */
    public function buildTrackLanes($leafNodes, $storyType, $demands = array())
    {
        $lanes = array();
        foreach(array_keys($demands) as $demandID)
        {
            if($demands[$demandID]['demand']->parent >= 0) $lanes[] = array('name' => "lane_{$demandID}", 'title' => '');

            if($demands[$demandID]['demand']->parent == '-1')
            {
                $children = $demands[$demandID]['demand']->childDemands;

                $includeChildren = false;
                foreach(explode(',', $children) as $childDemandID) if(isset($demands[$childDemandID])) $includeChildren = true;

                if(!$includeChildren) $lanes[] = array('name' => "lane_{$demandID}", 'title' => '');
            }
        }
        return $lanes;
    }
}
