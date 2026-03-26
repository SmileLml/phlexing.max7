<?php
/**
 * @param string|int $shadow
 * @param int $programID
 * @param string $status
 * @param int $limit
 * @param int $line
 * @param string $fields
 */
public function getList($programID = 0, $status = 'all', $limit = 0, $line = 0, $shadow = 0, $fields = '*')
{
    $products = $this->dao->select("t1.$fields")->from(TABLE_PRODUCT)->alias('t1')
        ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
        ->where('t1.deleted')->eq(0)
        ->beginIF($shadow !== 'all')->andWhere('t1.shadow')->eq((int)$shadow)->fi()
        ->beginIF($programID)->andWhere('t1.program')->eq($programID)->fi()
        ->beginIF($line > 0)->andWhere('t1.line')->eq($line)->fi()
        ->beginIF(!$this->app->user->admin and strpos($status, 'feedback') === false)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
        ->beginIF(strpos($status, 'noclosed') === false && !in_array($status, array('all', 'mine', 'involved', 'review', 'feedback'), true))->andWhere('t1.status')->in($status)->fi()
        ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('t1.status')->ne('closed')->fi()
        ->beginIF(strpos($status, 'feedback') !== false)->andWhere("FIND_IN_SET('rnd', t1.vision)")->fi()
        ->beginIF($status == 'mine')->andWhere('t1.PO')->eq($this->app->user->account)->fi()
        ->filterTpl('skip')
        ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
        ->beginIF($limit > 0)->limit($limit)->fi()
        ->fetchAll('id');

    return $products;
}

/**
 * 获取产品统计信息。
 * Get product stats.
 *
 * @param  array       $productIdList
 * @param  string      $orderBy order_asc|program_asc
 * @param  object|null $pager
 * @param  string      $storyType requirement|story
 * @param  int         $programID
 * @access public
 * @return array
 */
public function getStats($productIdList, $orderBy = 'order_asc', $pager = null, $storyType = 'story', $programID = 0)
{
    if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProductStats();

    if(empty($productIdList)) return array();

    if($orderBy == 'program_asc')
    {
        $products = $this->dao->select('t1.id as id, t1.name, t1.program, t1.line, t1.PO, t1.status, t1.type, t1.PMT, 0 as draftStories, 0 as activeStories, 0 as launchedStories, 0 as developingStories, 0 as waitedRoadmaps, 0 as launchedRoadmaps, t1.plans, t1.releases, t1.unresolvedBugs')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.id')->in($productIdList)
            ->filterTpl('skip')
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->page($pager)
            ->fetchAll('id');
    }
    else
    {
        $products = $this->dao->select('id, name, program, line, status, type, PO, PMT, 0 as draftStories, 0 as activeStories, 0 as launchedStories, 0 as developingStories, 0 as waitedRoadmaps, 0 as launchedRoadmaps, plans, releases, unresolvedBugs')->from(TABLE_PRODUCT)
            ->where('id')->in($productIdList)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /* Recalculate productKeys after paging. */
    $productKeys = array_keys($products);

    $linePairs = $this->getLinePairs();
    foreach($products as $product) $product->lineName = zget($linePairs, $product->line, '');

    $this->app->loadLang('project');
    if(empty($programID))
    {
        $programKeys = array(0 => 0);
        foreach($products as $product) $programKeys[] = $product->program;
        $programs = $this->dao->select('id,name,PM')->from(TABLE_PROGRAM)
            ->where('id')->in(array_unique($programKeys))
            ->fetchAll('id');

        foreach($products as $product)
        {
            $product->programName = (!empty($product->line) && empty($product->program)) ? $this->lang->project->future : (isset($programs[$product->program]) ? $programs[$product->program]->name : '');
            $product->programPM   = isset($programs[$product->program]) ? $programs[$product->program]->PM : '';
        }
    }

    $stories = $this->dao->select('id,product,status')->from(TABLE_STORY)
        ->where('deleted')->eq(0)
        ->andWhere('status')->in('draft,active,launched,developing')
        ->andWhere('type')->eq('requirement')
        ->andWhere('product')->in($productKeys)
        ->fetchAll('id');

    foreach($stories as $story)
    {
        if($story->status == 'draft')
        {
            $products[$story->product]->draftStories++;
        }
        else if($story->status == 'active')
        {
            $products[$story->product]->activeStories++;
        }
        else if($story->status == 'launched')
        {
            $products[$story->product]->launchedStories++;
        }
        else if($story->status == 'developing')
        {
            $products[$story->product]->developingStories++;
        }
    }

    $roadmaps = $this->dao->select('id, product, status')->from(TABLE_ROADMAP)
        ->where('deleted')->eq(0)
        ->andWhere('status')->in('wait,launched')
        ->andWhere('product')->in($productKeys)
        ->fetchAll('id');
    foreach($roadmaps as $roadmap)
    {
        if($roadmap->status == 'wait')
        {
            $products[$roadmap->product]->waitedRoadmaps++;
        }
        else if($roadmap->status == 'launched')
        {
            $products[$roadmap->product]->launchedRoadmaps++;
        }
    }

    return $products;
}

/**
 * Get product by pool.
 *
 * @param  int      $poolID
 * @param  string   $status
 * @param  string   $append
 * @param  bool     $queryAll
 * @access public
 * @return array
 */
public function getProductByPool($poolID, $status = '', $append = '', $queryAll = false)
{
    $pool = $this->loadModel('demandpool')->getById($poolID);
    $productIdList = !empty($pool->products) ? trim(trim($pool->products, ',') . ',' . trim($append, ','), ',') : '';

    return $this->dao->select('*')->from(TABLE_PRODUCT)
        ->where('deleted')->eq(0)
        ->andWhere('shadow')->eq(0)
        ->beginIF($productIdList)->andWhere('id')->in($productIdList)->fi()
        ->beginIF(!$queryAll && !$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
        ->beginIF($status)->andWhere('status')->in($status)->fi()
        ->fetchPairs('id', 'name');
}

/**
 * Build operate menu.
 *
 * @param  object $product
 * @param  string $type
 * @access public
 * @return string
 */
public function buildOrOperateMenu($product, $type = 'view')
{
    $menu   = '';
    $params = "product=$product->id";

    if($type == 'view')
    {
        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('product', $product, $type, 'direct');
        $menu .= "<div class='divider'></div>";

        if($product->status != 'closed') $menu .= $this->buildMenu('product', 'close', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
        if($product->status == 'closed') $menu .= $this->buildMenu('product', 'activate', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
        $menu .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('product', 'edit', $params, $product, $type);
    }
    elseif($type == 'browse')
    {
        $menu .= $this->buildMenu('product', 'edit', $params, $product, $type);
        if($product->status != 'closed') $menu .= $this->buildMenu('product', 'close', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
        if($product->status == 'closed') $menu .= $this->buildMenu('product', 'activate', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
    }

    if($type != 'browse') $menu .= $this->buildMenu('product', 'delete', $params, $product, $type, 'trash', 'hiddenwin');

    return $menu;
}
