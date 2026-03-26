<?php
/**
 * Get libs by object.
 *
 * @param  string $type
 * @param  int    $objectID
 * @param  int    $appendLib
 * @access public
 * @return array
 * @param int $limit
 */
public function getLibsByObject($type, $objectID = 0, $appendLib = 0, $limit = 0)
{
    if($type == 'custom' or $type == 'mine')
    {
        $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('type')->eq($type)
            ->andWhere('parent')->eq($objectID)
            ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
            ->beginIF($type == 'mine')->andWhere('addedBy')->eq($this->app->user->account)->fi()
            ->orderBy('`order` asc, id_asc')
            ->limit($limit)
            ->fetchAll('id');
    }
    elseif($type != 'product' and $type != 'project' and $type != 'execution')
    {
        return array();
    }
    else
    {
        $executionIDList = array();
        if($type == 'project') $executionIDList = $this->loadModel('execution')->getPairs($objectID, 'all', 'multiple,leaf');

        $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->beginIF($type != 'product' or $this->config->vision != 'or')->andWhere('vision')->eq($this->config->vision)->fi()
            ->andWhere($type)->eq($objectID)
            ->beginIF($type == 'project')->andWhere('type')->in('api,project')->fi()
            ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
            ->orderBy('`order` asc, id_asc')
            ->limit($limit)
            ->fetchAll('id');
        if($executionIDList)
        {
            $objectLibs += $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('execution')->in(array_keys($executionIDList))
                ->andWhere('type')->eq('execution')
                ->beginIF(!empty($appendLib))->orWhere('id')->eq($appendLib)->fi()
                ->orderBy('`order` asc, id_asc')
                ->limit($limit)
                ->fetchAll('id');
        }
    }

    /*
    if($type == 'product')
    {
        $hasProject = $this->dao->select('DISTINCT t1.product, count(t1.project) as projectCount')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->eq($objectID)
            ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t2.status')->notin('done,closed')->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('product')
            ->fetchPairs('product', 'projectCount');
    }
     */

    $libs = array();
    foreach($objectLibs as $lib)
    {
        if($this->checkPrivLib($lib)) $libs[$lib->id] = $lib;
    }

    $itemCounts = $this->statLibCounts(array_keys($libs));
    foreach($libs as $libID => $lib) $libs[$libID]->allCount = $itemCounts[$libID];

    return $libs;
}
