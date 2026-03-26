<?php
class zentaobizTree extends treeModel
{
    /**
     * delete module.
     *
     * @param mixed $moduleID
     * @param mixed $null
     * @access public
     * @return void
     */
    public function delete($moduleID, $null = null)
    {
        if(!empty($this->app->user->feedback) or $this->cookie->feedbackView)
        {
            $module = $this->getById($moduleID);
            if($module->type != 'doc') return false;
        }
        return parent::delete($moduleID);
    }

    /**
     * Get feedback tree menu.
     *
     * @param  string $userFunc
     *
     * @access public
     * @return string
     */
    public function getFeedbackTreeMenu($userFunc = '')
    {
        $menu = array();

        /* Get module according to product. */
        $products = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');

        $syncConfig = json_decode($this->config->global->syncProduct, true);
        $syncConfig = isset($syncConfig['feedback']) ? $syncConfig['feedback'] : array();
        $productNum = count($products);
        $productID  = $this->session->feedbackProduct;
        if($productID && isset($products[$productID])) $products = array($productID => $products[$productID]);

        /* Create module tree.*/
        foreach($products as $id => $product)
        {
            $feedbackProductLink = helper::createLink('feedback', $this->config->vision == 'lite' ? 'browse' : 'admin', "browseType=byProduct&param=$id");
            if($productNum >= 1)
            {
                $menuItem = new stdclass();
                $menuItem->id     = "product-{$id}";
                $menuItem->parent = 0;
                $menuItem->name   = $product;
                $menuItem->url    = $feedbackProductLink;
                $menu[] = $menuItem;
            }
            $type = isset($syncConfig[$id]) ? 'story,feedback' : 'feedback';

            /* Append module to tree menu. */
            if($productID !== 'all')
            {
                $treeMenu = array();
                $query = $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('root')->eq($id)
                    ->andWhere('type')->in($type)
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->dbh->query($query);
                while($module = $stmt->fetch())
                {
                    /* If is merged add story module.*/
                    if($module->type == 'story' and $module->grade > $syncConfig[$id]) continue;

                    /* If not manage, ignore unused modules. */
                    $treeMenu = $this->buildTree($module, 'feedback', '0', $userFunc, '');
                    if($productNum >= 1 && $module->parent == 0) $treeMenu->parent = "product-{$module->root}";
                    $menu[] = $treeMenu;
                }
            }
        }

        return $menu;
    }

    /**
     * Get ticket tree menu.
     *
     * @param  string $userFunc
     *
     * @access public
     * @return string
     */
    public function getTicketTreeMenu($userFunc = '')
    {
        $menu = array();

        /* Get module according to product. */
        $products = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');

        $syncConfig = json_decode($this->config->global->syncProduct, true);
        $syncConfig = isset($syncConfig['ticket']) ? $syncConfig['ticket'] : array();
        $productNum = count($products);
        $productID  = $this->session->ticketProduct;
        if($productID && isset($products[$productID])) $products = array($productID => $products[$productID]);

        /* Create module tree.*/
        foreach($products as $id => $product)
        {
            $ticketProductLink = helper::createLink('ticket', 'browse', "browseType=byProduct&param=$id");
            if($productNum >= 1)
            {
                $menuItem = new stdclass();
                $menuItem->id     = "product-{$id}";
                $menuItem->parent = 0;
                $menuItem->name   = $product;
                $menuItem->url    = $ticketProductLink;
                $menu[] = $menuItem;
            }
            $type = isset($syncConfig[$id]) ? 'story,ticket' : 'ticket';

            /* Append module to tree menu. */
            if($productID !== 'all')
            {
                $treeMenu = array();
                $query = $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('root')->eq($id)
                    ->andWhere('type')->in($type)
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->dbh->query($query);
                while($module = $stmt->fetch())
                {
                    /* If is merged add story module.*/
                    if($module->type == 'story' and $module->grade > $syncConfig[$id]) continue;

                    /* If not manage, ignore unused modules. */
                    $treeMenu = $this->buildTree($module, 'ticket', '0', $userFunc, '');
                    if($productNum >= 1 && $module->parent == 0) $treeMenu->parent = "product-{$module->root}";
                    $menu[] = $treeMenu;
                }
            }
        }

        return $menu;
    }

    /**
     * Get group tree.
     *
     * @param  int    $dimensionID
     * @param  string $type        chart|report|dashboard|dataview
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return string
     */
    public function getGroupTree($dimensionID = 0, $type = 'chart', $orderBy = 'id_desc', $pager = null)
    {
        $tab    = $this->app->tab;
        $menu   = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-group'>";

        /* tree menu. */
        $query = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq($dimensionID)
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->get();

        $treeMenu = array();
        if($type == 'dataview')
        {
            $stmt           = $this->dbh->query($query);
            $dataviewGroups = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('deleted')->eq(0)->fetchGroup('group');

            while($module = $stmt->fetch())
            {
                $data = new stdclass();
                $data->key  = $module->id;
                $data->text = $module->name;
                $data->url  = '';

                $dataviews = isset($dataviewGroups[$module->id]) ? $dataviewGroups[$module->id] : array();
                foreach($dataviews as $dataview)
                {
                    if(!isset($data->items)) $data->items = array();
                    $dataviewData = new stdclass();
                    $dataviewData->key  = $dataview->code;
                    $dataviewData->text = $dataview->name;
                    $dataviewData->url  = helper::createLink('dataview', 'browse', "type=view&table={$dataview->id}");
                    $data->items[] = $dataviewData;
                }

                $treeMenu[$module->id] = $data;
            }

            $stmt = $this->dbh->query($query);
            while($module = $stmt->fetch())
            {
                if(isset($treeMenu[$module->parent]))
                {
                    $parentData = $treeMenu[$module->parent];

                    if(!isset($parentData->items)) $parentData->items = array();
                    $parentData->items[] = $treeMenu[$module->id];

                    unset($treeMenu[$module->id]);
                }
            }

            return array_values($treeMenu);
        }

        $stmt = $this->dbh->query($query);
        while($module = $stmt->fetch())
        {
            $methodList = array('report' => 'browseReport', 'chart' => 'browse', 'dashboard' => 'browse', 'pivot' => 'browse');
            $linkHtml   = html::a(helper::createLink($type, $methodList[$type], "dimensionID=$dimensionID&group={$module->id}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
            $title      = "title='{$module->name}'";

            if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
            {
                if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
                $treeMenu[$module->parent] .= "<li class='closed' $title>$linkHtml";
                $treeMenu[$module->parent] .= "<ul>" . $treeMenu[$module->id] . "</ul>\n";
            }
            else
            {
                if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = "";
                $treeMenu[$module->parent] .= "<li $title>$linkHtml\n";
            }
            $treeMenu[$module->parent] .= "</li>\n";

        }
        $menu .= isset($treeMenu[0]) ? $treeMenu[0] : '';

        $menu .= '</ul>';
        return $menu;
    }

    /**
     * Get group tree menu.
     *
     * @param  int    $dimensionID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getGroupTreeMenu($dimensionID = 0, $type = 'chart', $orderBy = 'id_desc', $pager = null)
    {
        /* tree menu. */
        $menus = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq($dimensionID)
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->fetchAll();

        $methodList = array('report' => 'browseReport', 'chart' => 'browse', 'dashboard' => 'browse', 'pivot' => 'browse');

        foreach($menus as $menu)
        {
            $menu->url = helper::createLink($type, $methodList[$type], "dimensionID=$dimensionID&group={$menu->id}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}");
        }

        return $menus;
    }

    /**
     * Get full chart group tree.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getGroupStructure($dimensionID = 0, $groupID = 0, $type = 'chart')
    {
        $query = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq($dimensionID)
            ->beginIF(!empty($groupID))->andWhere('path')->like("%,$dimensionID,%")->fi()
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`')
            ->get();
        $stmt = $this->dbh->query($query);

        while($module = $stmt->fetch())
        {
            $module->url = helper::createLink('tree', 'browsegroup', "dimensionID={$dimensionID}&group={$module->id}&type={$type}");
            if(isset($parent[$module->id]))
            {
                $module->children = $parent[$module->id]->children;
                unset($parent[$module->id]);
            }
            if(!isset($parent[$module->parent])) $parent[$module->parent] = new stdclass();
            $parent[$module->parent]->children[] = $module;
        }

        $tree = array();
        foreach($parent as $module)
        {
            foreach($module->children as $children)
            {
                if($children->parent != 0) continue;
                $tree[] = $children;
            }
        }
        return $tree;
    }

    /**
     * Get group pairs.
     *
     * @param  int    $dimensionID
     * @param  int    $parentGroup
     * @param  int    $grade
     * @param  string $type
     * @access public
     * @return array
     */
    public function getGroupPairs($dimensionID = 0, $parentGroup = 0, $grade = 2, $type = 'chart')
    {
        $groups = $this->dao->select('id,name,grade,parent')->from(TABLE_MODULE)
            ->where('root')->eq($dimensionID)
            ->beginIF(!empty($parentGroup))->andWhere('root')->eq($dimensionID)->fi()
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy('order')
            ->fetchAll();


        $nameMap = array();
        foreach($groups as $group) $nameMap[$group->id] = $group;

        $groupPairs = array();
        foreach($groups as $group)
        {
            if($grade == 1 && $group->grade > 1) continue; // 如果只要一层的，排除掉非1层的
            if($grade >= 2 && $group->grade == 1) continue; // 如果大于等于二，那么排除掉第一层的

            $fullName = $this->buildGroupFullName($group, $nameMap);
            $groupPairs[$group->id] = $fullName;
        }

        return $groupPairs;
    }

    /**
     * Get group full name.
     *
     * @param  object $item
     * @param  array  $nameMap
     * @access public
     * @return string
     */
    public function buildGroupFullName($item, $nameMap)
    {
        if ($item->parent != 0 && isset($nameMap[$item->parent]))
        {
            $parentFullName = $this->buildGroupFullName($nameMap[$item->parent], $nameMap);
            return $parentFullName . '/' . $item->name;
        }
        else
        {
            return $item->name;
        }
    }

    /**
     * Get practice tree menu.
     *
     * @param  string $userFunc
     * @param  string $type     browse|view
     * @access public
     * @return string
     */
    public function getPracticeTreeMenu($userFunc = '', $type = 'browse')
    {
        $practices = $type == 'view' ? $this->dao->select('module,id,title')->from(TABLE_PRACTICE)->fetchGroup('module') : array();
        $menu      = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-ticket'>";

        $tree = '';
        $treeMenu = array();
        $query = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('type')->eq('practice')
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade_desc, id_asc')
            ->get();
        $stmt = $this->dbh->query($query);
        while($module = $stmt->fetch())
        {
            $linkHtml = $userFunc ? call_user_func($userFunc, 'practice', $module) : "<a id='module{$module->id}' title='{$module->name}' >{$module->name}</a>";

            if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
            {
                if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
                $treeMenu[$module->parent] .= "<li class='closed'>{$linkHtml}";
                $treeMenu[$module->parent] .= "<ul>" . $treeMenu[$module->id] . "</ul>\n";
            }
            else
            {
                if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = "";

                if(!empty($practices) && isset($practices[$module->id]))
                {
                    $treeMenu[$module->parent] .= "<li>{$linkHtml}";
                    $treeMenu[$module->parent] .= "<ul>\n";

                    foreach($practices[$module->id] as $practice)
                    {
                        $practiceLink = html::a(helper::createLink('traincourse', 'practiceview', "id={$practice->id}"), $practice->title, '_self', "id='practice{$practice->id}' title='{$practice->title}'");
                        $treeMenu[$module->parent] .= "<li style='display: flex;'><i class='icon icon-file-text-alt' style='padding-top: 5px;'></i>{$practiceLink}</li>\n";
                    }
                    $treeMenu[$module->parent] .= "</ul>\n";
                }
                else
                {
                    $treeMenu[$module->parent] .= "<li>{$linkHtml}\n";
                }
            }
            $treeMenu[$module->parent] .= "</li>\n";
        }

        $tree .= isset($treeMenu[0]) ? $treeMenu[0] : '';
        $menu .= $tree;
        $menu .= '</ul>';
        return $menu;
    }
}
