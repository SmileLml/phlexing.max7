<?php
class zentaobizGroup extends groupModel
{
    /**
     * @return int|false
     * @param object $group
     */
    public function create($group)
    {
        if(!empty($this->app->user->feedback) or $this->cookie->feedbackView)
        {
            $_POST['developer'] = 0;
        }

        return parent::create($group);
    }

    /**
     * @param int $projectID
     */
    public function getPairs($projectID = 0)
    {
        if(!empty($this->app->user->feedback) or $this->cookie->feedbackView)
        {
            return $this->dao->select('id, name')->from(TABLE_GROUP)
                ->where('developer')->eq('0')
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('project')->eq($projectID)
                ->fetchPairs();
        }

        return $this->dao->select('id, name')->from(TABLE_GROUP)
            ->where('developer')->eq('1')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('project')->eq($projectID)
            ->fetchPairs();
    }

    public function checkMenuModule($menu, $moduleName)
    {
        $flows = $this->dao->select('module, app')->from(TABLE_WORKFLOW)->where('buildin')->eq('0')->andWhere('app')->eq($menu)->andWhere('vision')->eq($this->config->vision)->andWhere('status')->eq('normal')->andWhere('type')->eq('flow')->orderBy('navigator_asc')->fetchPairs();
        if(isset($flows[$moduleName])) return true;
        return parent::checkMenuModule($menu, $moduleName);
    }

    public function getPrivManagerPairs($type, $parent = '')
    {
        $parentType       = $type == 'package' ? 'module' : 'view';
        $flowManagerPairs = array();
        $pairs            = array();
        if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and in_array($parentType, array('view', 'module')))
        {
            $flows = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('buildin')->eq('0')->andWhere('app')->eq($parent)->andWhere('vision')->eq($this->config->vision)->andWhere('status')->eq('normal')->andWhere('type')->eq('flow')->orderBy('navigator_asc')->fetchAll('id');
            foreach($flows as $flow) $pairs[$flow->module] = $flow->name;
        }

        if(!isset($pairs[$parent]))
        {
            $systemPairs = parent::getPrivManagerPairs($type, $parent);
            if(is_array($pairs)) $pairs = arrayUnion($systemPairs, $pairs);
        }

        return $pairs;
    }
}
