<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * @param int $projectID
     * @param string $from
     * @param int $programID
     * @param string $extra
     */
    public function edit($projectID, $from = '', $programID = 0, $extra = '')
    {
        if($this->config->edition == 'ipd') $this->loadModel('roadmap');

        $this->view->charters = $this->loadModel('charter')->getPairs('launched', 'completionDoing,cancelDoing');

        $project = $this->project->getById($projectID);
        if($project->model == 'ipd')
        {
            $this->config->project->edit->requiredFields .= ',category';
            $this->config->project->form->edit['category']['required'] = true;
        }

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $charterID    = isset($output['charter']) ? $output['charter'] : $project->charter;
        $disableModel = $this->project->checkCanChangeModel($projectID, $project->model) ? '' : 'disabled';

        if($charterID)
        {
            $charter = $this->charter->fetchById($charterID);

            /* 获取关联到这个项目中的立项的产品和路标或计划。 */
            $projectBranches = $this->project->getBranchesByProject($projectID);
            $charterGroups   = $this->charter->getGroupDataByID($charterID);
            $objectType      = isset($output['charter']) ? $charter->type : $project->linkType;

            /* 获取项目关联的路标或计划 */
            $projectObjects = '';
            foreach($projectBranches as $productID => $branches)
            {
                foreach($branches as $linkedObjects) $projectObjects .= trim($linkedObjects->$objectType, ',') . ',';
            }

            $table = $objectType == 'roadmap' ? TABLE_ROADMAP : TABLE_PRODUCTPLAN;
            $linkedGroups = $this->dao->select('*')->from($table)
                ->where('id')->in(trim($projectObjects, ','))
                ->andWhere('deleted')->eq(0)
                ->fetchGroup('product', 'id');

            /* 立项规划方式变了 */
            if(!isset($output['charter']) && $charterID == $project->charter && $charter->type != $project->linkType)
            {
                /* 项目有数据，禁止修改。 */
                if($disableModel)
                {
                    $charterGroups = $linkedGroups;

                    foreach(array_keys($projectBranches) as $productID)
                    {
                        if(!isset($charterGroups[$productID])) $charterGroups[$productID] = array();
                    }
                }
                /* 如果没有数据，那就用立项关联的数据，将项目已关联的重置。 */
                else
                {
                    $objectType = $charter->type;
                }
            }

            /* 立项规划方式没变 */
            if(!isset($output['charter']) && $charterID == $project->charter && $charter->type == $project->linkType)
            {
                $mergedGroups   = array();
                $mergedProducts = array_merge(array_keys($projectBranches), array_keys($charterGroups));
                $mergedProducts = array_unique($mergedProducts);
                foreach($mergedProducts as $productID)
                {
                    if(isset($charterGroups[$productID]) && !isset($linkedGroups[$productID]))  $mergedGroups[$productID] = $charterGroups[$productID];
                    if(isset($linkedGroups[$productID])  && !isset($charterGroups[$productID])) $mergedGroups[$productID] = $linkedGroups[$productID];
                    if(isset($charterGroups[$productID]) && isset($linkedGroups[$productID]))   $mergedGroups[$productID] = arrayUnion($charterGroups[$productID], $linkedGroups[$productID]);
                }

                foreach(array_keys($projectBranches) as $productID)
                {
                    if(!isset($mergedGroups[$productID])) $mergedGroups[$productID] = array();
                }

                $charterGroups = $mergedGroups;
            }

            $allCharterProducts = $this->loadModel('product')->getByIdList(array_keys($charterGroups));               // 获取立项所有产品信息
            $charterProducts    = array_intersect_key($allCharterProducts, array_flip(array_keys($projectBranches))); // 获取项目已关联的产品信息
            $charterProducts    = $charterProducts ? $charterProducts : $allCharterProducts;                          // 如果没有交集，就取所有
            $productBranches    = $this->loadModel('branch')->getByProducts(array_keys($charterProducts));            // 获取产品的分支
            $charterBranches    = array();

            /* 构造立项产品的已关联的分支和路标。 */
            $objects    = $objectType == 'plan' ? 'plans' : 'roadmaps';
            $titleField = $objectType == 'plan' ? 'title' : 'name';
            foreach($charterProducts as $product)
            {
                $charterBranches[$product->id] = $product->type == 'normal' ? array() : array_column($charterGroups[$product->id], 'branch');

                $product->branches = $charterBranches[$product->id];

                if(!empty($product->branches))
                {
                    $objectList = '';
                    if(isset($projectBranches[$product->id]))
                    {
                        foreach($projectBranches[$product->id] as $branchGroups)
                        {
                            $objectList       .= trim($branchGroups->$objectType, ',') . ',';
                            $product->$objects = array_unique(explode(',', trim($objectList, ',')));
                        }
                    }
                }
                else
                {
                    $product->$objects = isset($projectBranches[$product->id][0]) ? explode(',', trim($projectBranches[$product->id][0]->$objectType, ',')) : array();
                }
            }

            /* 构造立项的分支和路标/计划下拉。 */
            $productObjects  = array();
            $productRoadmaps = array();
            $productPlans    = array();
            $branchPairs     = array();
            foreach($charterGroups as $productID => $linkObjects)
            {
                foreach($linkObjects as $linkObject)
                {
                    if($allCharterProducts[$productID]->type == 'normal') $productObjects[$productID][$linkObject->id] = $linkObject->$titleField . ' [' . $linkObject->begin . '~' . $linkObject->end . ']';
                    else $productObjects[$productID][$linkObject->branch][$linkObject->id] = $linkObject->$titleField . ' [' . $linkObject->begin . '~' . $linkObject->end . ']';
                }

                $branchList = !empty($charterProducts[$productID]->branches) ? array_flip($charterProducts[$productID]->branches) : array();
                if(isset($productBranches[$productID])) $branchPairs[$productID] = array_intersect_key($productBranches[$productID], $branchList);
            }

            if($objectType == 'plan')    $productPlans    = $productObjects;
            if($objectType == 'roadmap') $productRoadmaps = $productObjects;
        }

        $objectType = isset($objectType) ? $objectType : $project->linkType;
        if(empty($charterID)) $objectType = 'plan';

        $this->view->charter             = $charterID;
        $this->view->charterProductPairs = isset($charterProducts) ? array_column($allCharterProducts, 'name', 'id') : array();
        $this->view->charterProducts     = isset($charterProducts) ? $charterProducts : array();
        $this->view->charterPlans        = isset($productPlans)    ? $productPlans    : array();
        $this->view->productRoadmaps     = isset($productRoadmaps) ? $productRoadmaps : array();
        $this->view->branchPairs         = isset($branchPairs)     ? $branchPairs     : array();
        $this->view->linkType            = $objectType;
        $this->view->output              = $output;

        if($_POST)
        {
            /* 将通过项目-设置-产品 添加的关联产品关联到项目中。*/
            /* Add extra products. */
            $oldProject        = $this->project->getById($projectID);
            $projectBranches   = $this->project->getBranchesByProject($projectID);
            $charterGroups     = $this->charter->getGroupDataByID($oldProject->charter);
            $extraProducts     = $charterGroups ? array_diff(array_keys($projectBranches), array_keys($charterGroups)) : array();
            $_POST['products'] = isset($_POST['products']) ? array_merge($_POST['products'], $extraProducts) : $extraProducts;
        }

        parent::edit($projectID, $from, $programID);
    }
}
