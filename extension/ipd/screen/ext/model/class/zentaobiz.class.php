<?php
class zentaobizScreen extends screenModel
{
    /**
     * Get screen list.
     *
     * @param  int $dimensionID
     * @access public
     * @return array
     */
    public function getList($dimensionID)
    {
        $canDesign = (common::hasPriv('screen', 'create') or common::hasPriv('screen', 'edit') or common::hasPriv('screen', 'design') or common::hasPriv('screen', 'delete'));
        $hasUsageReport  = $this->config->edition !== 'open';
        $viewableObjects = $this->loadModel('bi')->getViewableObject('screen');

        return $this->dao->select('*')->from(TABLE_SCREEN)
            ->where('dimension')->eq($dimensionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$canDesign)->andWhere('status')->eq('published')->fi()
            ->beginIF(!$hasUsageReport)->andWhere('id')->ne(1001)->fi()
            ->andWhere('id')->in($viewableObjects)
            ->fetchAll('id', false);
    }

    /**
     * Get dimension pairs.
     *
     * @access public
     * @return void
     */
    public function getDimensionPairs()
    {
        return $this->dao->select("id as value, name as label")->from(TABLE_DIMENSION)->where('deleted')->eq('0')->fetchAll();
    }

    /**
     * Get chart tree.
     *
     * @param  array    $dimensions
     * @access public
     * @return void
     */
    public function getTreeData($dimensions)
    {
        $treeData = array();
        foreach($dimensions as $dimension)
        {
            $treeData['chart'][$dimension->value] = $this->getChartTreeData($dimension->value, 'chart');
            $treeData['pivot'][$dimension->value] = $this->getChartTreeData($dimension->value, 'pivot');
        }
        $treeData['metric'] = $this->getMetricTreeData();

        $treeData = $this->usortTreeData($treeData);
        $treeData = $this->computeChartsNumber($treeData);
        return $treeData;
    }

    /**
     * Compute number of charts under the top tree.
     *
     * @param  array  $treeData
     * @access public
     * @return array
     */
    public function computeChartsNumber($treeData)
    {
        foreach($treeData as $type => $dimensionGroup)
        {
            foreach($dimensionGroup as $dimension => $trees)
            {
                foreach($trees as $parentTree)
                {
                    $parentTree->count = 0;
                    foreach($parentTree->child as $childTree)
                    {
                        if($childTree->type != 'chapter') $parentTree->count ++;
                        if($childTree->type == 'chapter') $parentTree->count += count($childTree->child);
                    }
                }
            }
        }
        return $treeData;
    }

    /**
     * Usort treeData biModule tree.
     *
     * @param  array  $treeData e.g: array('chart' => array(1 => array(obj, obj), 2 => array(obj, obj)), 'pivot' => array(1 => array(obj, obj), 2 => array(obj, obj)));
     * @access public
     * @return array
     */
    public function usortTreeData($treeData)
    {
        function compareByID($a, $b) { return ($a->id < $b->id) ? -1 : 1; }

        foreach(array('chart', 'pivot') as $biModule)
        {
            foreach($treeData[$biModule] as $dimensionID => $tree)
            {
                usort($treeData[$biModule][$dimensionID], 'compareByID');
                foreach($treeData[$biModule][$dimensionID] as $moduleID => $module)
                {
                    if(isset($module->child) and $module->child)
                    {
                        usort($treeData[$biModule][$dimensionID][$moduleID]->child, 'compareByID');
                    }
                }
            }
        }

        return $treeData;
    }

    /**
     * Get chart tree data.
     *
     * @param  int    $dimensionID
     * @param  string $type
     * @param  string $parent
     * @access public
     * @return void
     */
    public function getChartTreeData($dimensionID, $type, $parent = '')
    {
        $this->loadModel('pivot');
        $this->loadModel('bi');
        static $viewablePivots = array();
        static $viewableCharts = array();
        static $initViewable   = false;
        if(!$initViewable)
        {
            $viewablePivots = $this->bi->getViewableObject('pivot');
            $viewableCharts = $this->bi->getViewableObject('chart');
        }

        // Get tree data
        $treeNodes = $this->dao->select("id, name as title, path, 'chapter' as type, `parent`, `grade`")->from(TABLE_MODULE)
            ->where('root')->eq($dimensionID)
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq('0')
            ->orderBy('grade')
            ->fetchAll();

        $table           = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
        $viewableObjects = $type == 'chart' ? $viewableCharts : $viewablePivots;
        if($type == 'chart')
        {
            $chartData = $this->dao->select('*')->from($table)
                ->where('dimension')->eq($dimensionID)
                ->andWhere('stage')->eq('published')
                ->andWhere('builtin')->eq('0')
                ->andWhere('deleted')->eq('0')
                ->andWhere('id')->in($viewableObjects)
                ->orderBy('id_desc')
                ->fetchAll('', false);
        }
        else
        {
            $chartData = $this->dao->select('t1.id,t1.dimension,t1.group,t1.builtin, t2.*')->from($table)->alias('t1')
                ->leftJoin(TABLE_PIVOTSPEC)->alias('t2')->on('t1.id = t2.pivot and t1.version = t2.version')
                ->where('t1.dimension')->eq($dimensionID)
                ->andWhere('t1.stage')->eq('published')
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t1.id')->in($viewableObjects)
                ->orderBy('id_desc')
                ->fetchAll('', false);
        }

        if(empty($treeNodes)) return array();

        // Build tree data
        $grades = array();
        foreach($treeNodes as $node)
        {
            if(!isset($grades[$node->grade - 1])) $grades[$node->grade - 1] = array();
            $grades[$node->grade - 1][] = $node;

            $child = array();
            foreach($chartData as $chart)
            {
                $groups = explode(',', $chart->group);
                if(in_array($node->id, $groups))
                {
                    if($type == 'pivot' and $chart)
                    {
                        $chart = clone($chart);
                        $chart = $this->loadModel('pivot')->processPivot($chart, true);
                        $chart->settings = json_encode($chart->settings);
                    }
                    list($component, $_) = $this->initComponent($chart, $type);
                    $child[] = $component;
                }
            }
            $node->child = $child;
        }

        $leafs = array_pop($grades);
        foreach($leafs as $leafID => $leaf)
        {
            if($leaf->grade == 2 and empty($leaf->child)) unset($leafs[$leafID]);
        }

        $root  = array();

        while(!empty($grades))
        {
            $root = array_pop($grades);
            foreach($root as $node)
            {
                foreach($leafs as $leaf)
                {
                    if($leaf->parent == $node->id) $node->child[] = $leaf;
                }
            }

            $leafs = $root;
        }

        return $root;
    }

    /**
     * Get tree data of metrics.
     *
     * @access public
     * @return array
     */
    public function getMetricTreeData()
    {
        $this->app->loadConfig('metric');
        $this->app->loadLang('metric');

        $metricList = $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq(0)
            ->andWhere('stage')->eq('released')
            ->orderBy('id_desc')
            ->fetchAll('', false);

        $objectList  = array_keys($this->lang->metric->objectList);
        $purposeList = array_keys($this->lang->metric->purposeList);
        usort($metricList, function($a, $b) use ($objectList, $purposeList)
        {
            $objectIndexA = array_search($a->object, $objectList);
            $objectIndexB = array_search($b->object, $objectList);
            if($objectIndexA !== $objectIndexB) return $objectIndexA - $objectIndexB;

            $purposeIndexA = array_search($a->purpose, $purposeList);
            $purposeIndexB = array_search($b->purpose, $purposeList);
            return $purposeIndexA - $purposeIndexB;
        });

        $treeData = array();
        foreach($metricList as $metric)
        {
            $scope   = $metric->scope;
            $object  = $metric->object;
            $purpose = $metric->purpose;

            if(in_array($object, array('risk', 'issue')) && $this->config->edition == 'biz') continue;

            if(!isset($treeData[$scope])) $treeData[$scope] = array();

            if(!isset($treeData[$scope][$object]))
            {
                $objectTree = new stdclass();
                $objectTree->id    = uniqid();
                $objectTree->title = isset($this->lang->metric->objectList[$object]) ? $this->lang->metric->objectList[$object] : $object;
                $objectTree->type  = 'chapter';
                $objectTree->grade = 1;
                $objectTree->child = array();

                $treeData[$scope][$object] = $objectTree;
            }

            $objectChild = $treeData[$scope][$object]->child;
            if(!isset($objectChild[$purpose]))
            {
                $purposeTree = new stdclass();
                $purposeTree->id    = uniqid();
                $purposeTree->title = isset($this->lang->metric->purposeList[$purpose]) ? $this->lang->metric->purposeList[$purpose] : $purpose;
                $purposeTree->type  = 'chapter';
                $purposeTree->grade = 2;
                $purposeTree->child = array();

                $treeData[$scope][$object]->child[$purpose] = $purposeTree;
            }

            $metricConfig = new stdclass();
            $metricConfig->package      = 'Metrics';
            $metricConfig->category     = 'Metrics';
            $metricConfig->categoryName = $this->lang->metric->common;
            $metricConfig->chartFrame   = 'common';
            $metricConfig->chartKey     = 'VMetrics';
            $metricConfig->conKey       = 'VCMetrics';
            $metricConfig->sourceID     = $metric->id;
            $metricConfig->key          = 'Metrics';
            $metricConfig->title        = $metric->name;

            $metricNode = new stdclass();
            $metricNode->id          = uniqid();
            $metricNode->sourceID    = $metric->id;
            $metricNode->title       = $metric->name;
            $metricNode->type        = 'metric';
            $metricNode->chartConfig = $metricConfig;

            $treeData[$scope][$object]->child[$purpose]->child[] = $metricNode;
        }

        return $treeData;
    }

    /**
     * Get tree select options.
     *
     * @access public
     * @return void
     */
    public function getTreeSelectOptions()
    {
        $fieldConfig = (array)$this->config->screen->fieldConfig;

        $treeOptions = array();
        foreach($fieldConfig as $table => $tableInfo)
        {
            $tableName = $tableInfo->name;
            $tableFields = $tableInfo->fields;

            $children = array();
            foreach($tableFields as $field => $fieldName)
            {
                $children[] = array('label' => $fieldName, 'key' => "$table.$field");
            }
            $treeOptions[] = array('label' => $tableName, 'key' => $table, 'disabled' => true, 'children' => $children);
        }

        return $treeOptions;
    }

    /**
     * Create a screen.
     *
     * @param  int    $dimensionID
     * @access public
     * @return void
     */
    public function create($dimensionID)
    {
        $screen = fixer::input('post')
            ->add('dimension', $dimensionID)
            ->add('status', 'draft')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactList')
            ->get();

        $this->dao->insert(TABLE_SCREEN)->data($screen)
            ->autoCheck()
            ->batchCheck($this->config->screen->create->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * Update a screen.
     *
     * @param  int    $screenID
     * @access public
     * @return void
     */
    public function update($screenID)
    {
        $screen = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactList')
            ->get();

        $this->dao->update(TABLE_SCREEN)->data($screen)
            ->autoCheck()
            ->batchCheck($this->config->screen->edit->requiredFields, 'notempty')
            ->where('id')->eq($screenID)
            ->exec();

        if(dao::isError()) return false;

        return true;
    }

    /**
     * Publish a screen.
     *
     * @param  int    $screenID
     * @access public
     * @return void
     */
    public function publish($screenID)
    {
        $screen = fixer::input('post')
            ->skipSpecial('scheme')
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactListMenu')
            ->get();

        $data = new stdclass();
        $data->scheme    = $screen->scheme;
        $data->status    = $screen->status;
        if(isset($screen->acl) && isset($screen->whitelist))
        {
            $data->acl       = $screen->acl;
            $data->whitelist = $screen->acl == 'private' ? $screen->whitelist : '';
        }
        if($data->status == 'published')
        {
            $data->name = $screen->name;
            $data->desc = $screen->desc;
        }

        $this->dao->update(TABLE_SCREEN)->data($data)
            ->autoCheck()
            ->batchCheck($this->config->screen->publish->requiredFields, 'notempty')
            ->where('id')->eq($screenID)
            ->exec();

        if(dao::isError()) return false;

        return true;
    }

    /**
     * Save thumbnail.
     *
     * @param  int    $screenID
     * @access public
     * @return bool
     */
    public function saveThumbnail($screenID)
    {
        if(!isset($_FILES['thumbnail'])) return false;

        $thumbnail = $_FILES['thumbnail'];
        $thumbnail['type'] = 'png';
        $_POST['size'] = $thumbnail['size'];
        $file = $this->loadModel('file')->getUpload('thumbnail', $_FILES['thumbnail']['name']);
        if(is_array($file) && !empty($file)) $file = current($file);

        $saveFile = array();
        $saveFile['extension'] = $file['extension'];
        $saveFile['title']     = $file['title'];
        $saveFile['pathname']  = $file['pathname'];
        $saveFile['size']      = $file['size'];
        $saveFile['tmpname']   = $file['tmpname'];

        $this->file->deleteByObject('screen', $screenID);
        $this->file->saveAFile($saveFile, 'screen', $screenID);

        return true;
    }
}
