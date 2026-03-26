<?php
class excelTicket extends ticketModel
{
    /**
     * Set export list value
     *
     * @access public
     * @return void
     */
    public function setListValue()
    {
        $modulesProductMap = $this->loadModel('feedback')->getModuleList('ticket');

        $modules    = array();
        $moduleList = array();
        /* Group by module for cascade. */
        foreach($modulesProductMap as $productID => $module)
        {
            if(empty($module)) continue;
            foreach($module as $moduleID => $moduleName)
            {
                $moduleList[$productID][$moduleID] = $moduleName . "(#$moduleID)";
                $modules[$moduleID] = $moduleName . "(#$moduleID)";
            }
        }

        $this->post->set('moduleList', ($this->post->fileType == 'xlsx' and $moduleList) ? $moduleList : $modules);

        list($buildList, $allBuildPairs) = $this->getOpenedBuilds();
        $this->post->set('openedBuildList', ($this->post->fileType == 'xlsx' and $buildList) ? $buildList : $allBuildPairs);
    }

    /**
     * Get openedBuild data for export
     *
     * @param  bool    $withID
     * @access public
     * @return array
     */
    public function getOpenedBuilds($withID = true)
    {
        $buildsMap = array();
        $products  = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');
        $sysBuilds = array('trunk' => $this->lang->trunk . ($withID ? "(#trunk)" : ''));
        $shadows   = $this->dao->select('shadow')->from(TABLE_RELEASE)->fetchPairs();
        $allBuilds = $this->loadModel('build')->fetchBuilds(0, '', 0, '', $shadows);
        foreach($allBuilds as $build)
        {
            if($withID) $build->name = $build->name . "(#$build->id)";
            $buildsMap[$build->product][$build->id] = $build;
        }

        $buildList     = array();
        $allBuildPairs = $sysBuilds;
        foreach($products as $productID => $productName)
        {
            $productBuilds = zget($buildsMap, $productID, array());
            if(empty($productBuilds))
            {
                $buildList[$productID] = $sysBuilds;
                continue;
            }

            $builds     = array();
            $buildPairs = array();
            list($builds, $excludedReleaseIdList) = $this->build->setBuildDateGroup($productBuilds, '', '');

            $releases = $this->build->getRelatedReleases($productID, '', $shadows);
            $builds   = $this->build->replaceNameWithRelease($productBuilds, $builds, $releases, '', '', $excludedReleaseIdList);

            krsort($builds);
            foreach($builds as $childBuilds) $buildPairs = arrayUnion($buildPairs, $childBuilds);

            $buildList[$productID] = arrayUnion($sysBuilds,     $buildPairs);
            $allBuildPairs         = arrayUnion($allBuildPairs, $buildPairs);
        }

        return array($buildList, $allBuildPairs);
    }
}
