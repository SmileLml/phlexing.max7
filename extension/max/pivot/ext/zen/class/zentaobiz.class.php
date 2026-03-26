<?php
class zentaobizPivotZen extends pivotZen
{
    /**
     * 处理透视表操作权限
     * Init actions.
     *
     * @param  array $pivots
     * @access public
     * @return array
     */
    public function initAction($pivots)
    {
        foreach($pivots as $pivot)
        {
            if(isset($pivot->used) && $pivot->used)
            {
                $pivot->actions[1]['data-confirm'] = array('message' => $this->lang->pivot->confirm->design, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
                $pivot->actions[2]['data-confirm']['message'] = $this->lang->pivot->confirm->design;
            }
            if(!isset($pivot->type) || $pivot->type != 'table') continue;

            $actions = array();
            foreach($pivot->actions as $action)
            {
                $action['disabled'] = true;
                $actions[] = $action;
            }
            $pivot->actions = $actions;
        }
        return $pivots;
    }

    /**
     * 判断当前是否为发布操作。
     * Get is publish action or not.
     *
     * @param  array    $post
     * @access public
     * @return bool
     */
    public function isPublishAction($post)
    {
        return $this->getDesignAction($post) == 'publish';
    }

    /**
     * 判断当前是否为初始进入设计操作。
     * Get is design action or not.
     *
     * @param  array    $post
     * @access public
     * @return bool
     */
    public function isEnterDesignAction($post)
    {
        return $this->getDesignAction($post) == 'enterDesign';
    }

    /**
     * 根据透视表编号获取保存的下钻配置。
     * Get drills by pivot id.
     *
     * @param  int    $pivotID
     * @access public
     * @return array
     * @param string $version
     * @param string $status
     */
    public function getDrillsByPivotID($pivotID, $version, $status = 'published')
    {
        $drills = $this->dao->select('*')->from(TABLE_PIVOTDRILL)
            ->where('pivot')->eq($pivotID)
            ->andWhere('version')->eq($version)
            ->andWhere('status')->eq($status)
            ->beginIF($status == 'design')->andWhere('account')->eq($this->app->user->account)->fi()
            ->fetchAll('', false);

        foreach($drills as $index => $drill)
        {
            $drill->condition = json_decode($drill->condition, true);
            $drills[$index] = $drill;
        }

        return $drills;
    }

    /**
     * 根据透视表编号获取最新的filters。
     * Get filters by pivot id.
     *
     * @param  int    $pivotID
     * @access public
     * @return array
     * @param string $version
     * @param string $status
     */
    public function getFiltersByPivotID($pivotID, $version, $status = 'published')
    {
        $filters = $this->dao->select('filters')->from(TABLE_PIVOTSPEC)->where('pivot')->eq($pivotID)->andWhere('version')->eq($version)->fetch('filters');

        if(!$filters) return array();
        $filters = json_decode($filters, true);
        return $this->pivot->processFilters($filters, $status);
    }

    /**
     * 获取当前设计请求的动作。
     * Get design action.
     *
     * @access public
     * @return string
     */
    public function getDesignAction()
    {
        if(empty($_POST)) return 'enterDesign';

        if(!isset($_POST['action'])) return 'publish';

        return $_POST['action'];
    }

    /**
     * 获取缓存文件路径。
     * Get cache file path.
     *
     * @param  int $id
     * @access public
     * @return string
     */
    public function getCacheFilePath($id)
    {
        $root = $this->app->getTmpRoot() . 'bi' . DS;
        $user = $this->app->user->account;

        return $root . "{$id}_{$user}.json";
    }

    /**
     * 获取缓存内容。
     * Get cache.
     *
     * @param  int $id
     * @access public
     * @return object
     */
    public function getCache($id)
    {
        $filename = $this->getCacheFilePath($id);
        if(!$this->cacheExist($id)) return false;

        $content  = file_get_contents($filename);
        return json_decode($content);
    }

    /**
     * 设置缓存内容。
     * Set cache.
     *
     * @access public
     * @return void
     */
    public function setCache()
    {
        $this->setCacheRoot();
        $id = $this->pivotState->id;
        $filename = $this->getCacheFilePath($id);
        file_put_contents($filename, json_encode($this->pivotState, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 清空缓存内容。
     * Clear cache.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function clearCache($id)
    {
        $filename = $this->getCacheFilePath($id);
        if(!$this->cacheExist($id)) return false;
        unlink($filename);
    }

    /**
     * 设置缓存路径。
     * Set Cache root.
     *
     * @access public
     * @return void
     */
    public function setCacheRoot()
    {
        $tmpRoot = $this->app->getTmpRoot();
        $root    = $tmpRoot . 'bi' . DS;
        if(!is_dir($root) && !mkdir($root, 0755, true))
        {
            $error   = error_get_last();
            $message = $error['message'];

            if(!empty($message)) a($message);
            else                 a(sprintf($this->lang->pivot->permissionDenied, $tmpRoot, $tmpRoot));
            exit;
        }
    }

    /**
     * 判断缓存是否存在。
     * Cache exist.
     *
     * @param  int $id
     * @access public
     * @return bool
     */
    public function cacheExist($id)
    {
        $filename = $this->getCacheFilePath($id);
        return file_exists($filename);
    }

    /**
     * 初始化pivotState对象。
     * Init pivotState.
     *
     * @param  object $pivot
     * @access public
     * @return object
     * @param bool $fromCache
     */
    public function initPivotState($pivot, $fromCache = true)
    {
        $this->app->loadClass('pivotstate', true);

        $drills         = $this->getDrillsByPivotID($pivot->id, $pivot->version);
        $pivot->filters = $this->getFiltersByPivotID($pivot->id, $pivot->version);
        $sqlbuilder     = $this->loadModel('sqlbuilder')->getByObject($pivot->id, 'pivot');
        $pivotState     = new pivotState($pivot, $drills, $this->app->getClientLang(), $sqlbuilder);

        if($fromCache) $pivotState->updateFromCache($this->getCache($pivot->id));
        $pivotState->updateFromPost($_POST);

        $pivotState->canChangeMode = $pivotState->setCanChangeMode();

        if($pivotState->mode == 'builder')
        {
            $pivotState->sqlbuilder = $this->sqlbuilder->initSqlBuilder($pivotState->sqlbuilder);
            $pivotState->sql = $pivotState->sqlbuilder->sql;
            if(empty($_POST)) $pivotState->setStep2FinishSql();
        }

        return $pivotState;
    }

    /**
     * 处理设计透视表页面的各种操作。
     * Handle design action.
     *
     * @param  object  $pivot
     * @access public
     * @return bool
     */
    public function handleDesignAction($pivot)
    {
        $this->loadModel('bi');

        $this->pivotState = $this->initPivotState($pivot, !$this->isEnterDesignAction($_POST));
        $this->view->pivotState = $this->pivotState;
        $this->pivotState->setAction($this->getDesignAction());

        $this->view->settingErrors = array();
        $this->view->originData    = array();
        $this->view->originConfigs = array();

        if($this->pivotState->isPublish() || $this->pivotState->isFirstDesign())
        {
            $this->clearCache($pivot->id);
            return false;
        }

        if($this->pivotState->isDesign())
        {
            $this->pivotState->setPager();
            $this->pivotState->setStep('query');
            $this->pivotState->setAction('query');
        }

        $funcKey = $this->pivotState->action;

        $settingsFunc = array('addGroup', 'deleteGroup', 'deleteColumn', 'deleteDrill', 'changeOrigin', 'changeSlice', 'changeShowMode');
        if(in_array($funcKey, $settingsFunc)) $funcKey = 'settings';

        $funcName = "handle{$funcKey}DesignAction";
        if(method_exists($this, $funcName)) $this->$funcName();

        $this->loadModel('sqlbuilder');
        if($this->sqlbuilder->isSqlBuilderAction())
        {
            $this->pivotState->sqlbuilder = $this->sqlbuilder->sqlBuilderAction();
            $this->pivotState->sql = $this->pivotState->sqlbuilder->sql;
            if($this->sqlbuilder->isQueryAction())
            {
                $this->pivotState->processQueryFilters();
                $this->pivotState->pivotFilters = $this->preparePivotFilters();
            }
        }

        if(empty($this->pivotState->pivotFilters)) $this->pivotState->pivotFilters = $this->preparePivotFilters();

        $this->pivotState = $this->pivot->filterPivotSpecialChars($this->pivotState);

        $this->setCache();

        return true;
    }

    /**
     * 处理第一步查询sql动作。
     * Handle query design action for step query.
     *
     * @access public
     * @return bool
     */
    public function handleQueryDesignAction()
    {
        // if($this->pivotState->mode == 'builder' && !$this->pivotState->checkSqlBuilder()) return false;
        $this->app->loadClass('pager', $static = true);

        $this->bi->query($this->pivotState, $this->pivotState->driver);

        if($this->pivotState->isError())
        {
            $this->pivotState->setStep('query');
            return false;
        }
        if(empty($this->pivotState->pivotFilters)) $this->pivotState->pivotFilters = $this->preparePivotFilters();
        $this->pivotState->matchQueryFilterFromSql();
        $statePager = (array)$this->pivotState->pager;

        extract($statePager);
        $this->view->pager = new pager($total, $recPerPage, $pageID);
        return true;
    }

    public function handleQueryFilterDesignAction()
    {
        $filters = $this->pivotState->filters;
        foreach($filters as $index => $filter)
        {
            if(strpos($filter['type'], 'select') === false) continue;

            $this->pivotState->filters[$index]['items']   = $this->getFilterOptionUrl($filter, $this->pivotState->sql, $this->pivotState->fieldSettings);
            $this->pivotState->filters[$index]['default'] = '';
        }

        $addQueryFilter = $this->pivotState->addQueryFilter;
        if(empty($addQueryFilter) || strpos($addQueryFilter['type'], 'select') === false) return;

        $this->pivotState->addQueryFilter['items']   = $this->getFilterOptionUrl($addQueryFilter, $this->pivotState->sql, $this->pivotState->fieldSettings);
        $this->pivotState->addQueryFilter['default'] = '';
    }

    /**
     * 处理第一步添加查询筛选器编辑操作。
     * Handle add query filter design action.
     *
     * @access public
     * @return bool
     */
    public function handleAddQueryFilterDesignAction()
    {
        $this->pivotState->addQueryFilter = $this->pivotState->getDefaultQueryFilter();
        return true;
    }

    /**
     * 处理第一步保存查询筛选器编辑操作。
     * Handle save query filter design action.
     *
     * @access public
     * @return bool
     */
    public function handleSaveQueryFilterDesignAction()
    {
        if(!empty($this->pivotState->addQueryFilter))
        {
            $this->pivotState->addVariableToSql();
            $this->pivotState->saveQueryFilter();
        }

        $this->pivotState->sqlChanged();
        $this->pivotState->pivotFilters = $this->preparePivotFilters('', array(), false);

        return true;
    }

    /**
     * 处理第二步透视表数据表格动作。
     * Handle table design action for step design.
     *
     * @access public
     * @return bool
     */
    public function handleTableDesignAction()
    {
        if(!$this->pivotState->isQueried() && !$this->handleQueryDesignAction())
        {
            $this->pivotState->setStep('query');
            return false;
        }

        $this->pivotState->completeSettings();

        $errors = $this->pivotState->checkSettings();
        if(!empty($errors))
        {
            if($this->pivotState->step != 'design' || $this->pivotState->checkStepDesign)
            {
                $this->pivotState->setStep('design');
                $this->view->settingErrors = $errors;
                $this->pivotState->checkStepDesign = false;
            }
            return false;
        }

        if($this->pivotState->autoGenDrills) $this->pivot->autoGenDrillSettings($this->pivotState);
        $this->pivotState->autoGenDrills = false;

        $sql      = $this->pivotState->sql;
        $settings = $this->pivotState->settings;
        $fields   = $this->pivotState->fieldSettings;
        $langs    = $this->pivotState->langs;
        $filters  = $this->pivotState->filters;
        $driver   = $this->pivotState->driver;

        if(empty($settings)) return false;

        $genFunc = $this->pivotState->isSummaryNotUse() ? "genOriginSheet" : "genSheet";

        $this->pivotState->setStep2FinishSql();
        $mergedFilters = $this->pivotState->getFilters();
        $filterWheres = $this->pivotState->convertFiltersToWhere($mergedFilters);
        list($data, $configs)         = $this->pivot->$genFunc($fields, $settings, $sql, empty($mergedFilters) ? false : $filterWheres, $langs, $driver);
        list($cols, $rows, $cellSpan) = $this->bi->convertDataForDtable($data, $configs, $this->pivotState->version, 'design');

        $this->view->originData    = $data;
        $this->view->originConfigs = $configs;

        $this->pivotState->pivotCols     = $cols;
        $this->pivotState->pivotData     = $rows;
        $this->pivotState->pivotCellSpan = $cellSpan;

        if(empty($this->pivotState->pivotFilters) || $this->pivotState->filterChanged)
        {
            $this->pivotState->pivotFilters = $this->preparePivotFilters($sql, $filters);
            $this->pivotState->filterChanged = false;
        }

        return true;
    }

    /**
     * 处理第二步透视表参数设置动作。
     * Handle settings design action for step 2.
     *
     * @access public
     * @return bool
     */
    public function handleSettingsDesignAction()
    {
        $this->pivotState->completeSettings();
        $this->pivotState->processColumnShowOrigin();
        return true;
    }

    /**
     * 处理第二步透视表添加列动作。
     * Handle add column design action for step 2.
     *
     * @access public
     * @return bool
     */
    public function handleAddColumnDesignAction()
    {
        $this->pivotState->addColumn();
        return true;
    }

    /**
     * 处理第三步透视表改变对象动作。
     * Handle change object design action for step 3.
     *
     * @access public
     * @return bool
     */
    public function handleChangeObjectDesignAction()
    {
        $prefix = $this->config->db->prefix;
        $sql    = $this->pivotState->sql;
        if(isset($this->pivotState->defaultDrill['objectChanged']))
        {
            unset($this->pivotState->defaultDrill['objectChanged']);

            $objectTable = $prefix . $this->pivotState->defaultDrill['object'];
            $this->pivotState->defaultDrill['whereSql'] = $this->pivot->autoGenWhereSQL($objectTable, $sql);
        }

        if(empty($this->pivotState->drills)) return true;
        foreach($this->pivotState->drills as $index => $drill)
        {
            if(isset($drill['objectChanged']))
            {
                unset($drill['objectChanged']);

                $objectTable = $prefix . $drill['object'];
                $drill['whereSql'] = $this->pivot->autoGenWhereSQL($objectTable, $sql);
                $this->pivotState->drills[$index] = $drill;
            }
        }

        return true;
    }

    /**
     * 处理第三步透视表添加下钻动作。
     * Handle add drill design action for step 3.
     *
     * @access public
     * @return bool
     */
    public function handleAddDrillDesignAction()
    {
        $this->pivotState->defaultDrill = $this->pivotState->initDrill();
        return true;
    }

    /**
     * Handle preview drill result action for step 3.
     *
     * @access public
     * @return bool
     */
    public function handlePreviewResultDesignAction()
    {
        if(isset($this->pivotState->defaultDrill['preview']))
        {
            unset($this->pivotState->defaultDrill['preview']);
            $drill = $this->pivotState->defaultDrill;
        }
        if(empty($this->pivotState->drills) && is_null($drill)) return true;
        foreach($this->pivotState->drills as $index => $drillInfo)
        {
            if(isset($drillInfo['preview']))
            {
                $drill = $drillInfo;
                unset($drill['preview']);
            }
        }

        $previewResult = $this->pivot->getDrillResult($drill['object'], $drill['whereSql'], $this->pivotState->filters);

        $this->view->previewCols  = $previewResult['status'] == 'success' ? $previewResult['cols']  : array();
        $this->view->previewData  = $previewResult['status'] == 'success' ? $previewResult['data']  : array();
        $this->view->errorMessage = $previewResult['status'] == 'fail'    ? $previewResult['error'] : null;

        return true;
    }

    /**
     * 准备透视表的筛选器组件参数。
     * Prepare pivot filters.
     *
     * @param  string $sql
     * @param  array  $filters
     * @access public
     * @return array
     * @param bool $checkSql
     */
    public function preparePivotFilters($sql = '', $filters = array(), $checkSql = true)
    {
        if(empty($sql))     $sql     = $this->pivotState->sql;
        if(empty($filters)) $filters = $this->pivotState->filters;

        if($checkSql && !$this->pivotState->isQueried()) return array();

        $pivotFilters = array('query' => array(), 'result' => array());

        foreach($filters as $index => $filter)
        {
            $type  = $filter['type'];
            $field = $filter['field'];
            $from  = zget($filter, 'from', 'result');

            $multiple = false;
            if($from == 'result' || $type == 'multipleselect') $multiple = true;

            $optionUrl = $this->getFilterOptionUrl($filter, $sql, $this->pivotState->fieldSettings);
            $pivotFilters[$from][] = array('title' => $filter['name'], 'type' => $type, 'name' => $field, 'value' => zget($filter, 'default', ''), 'onChange' => 'handleFilterChange', 'items' => $optionUrl, 'multiple' => $multiple);
        }

        return array_values($pivotFilters);
    }

    /**
     * 获取筛选器的下拉选项内容。
     * Get filter options.
     *
     * @param  array  $filter
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getFilterOptions($filter, $sql = '')
    {
        if(empty($sql)) $sql = $this->pivotState->sql;

        $fieldSettings = $this->pivotState->fieldSettings;
        $filterField   = $filter['field'];
        $from          = zget($filter, 'from', 'result');
        $isQueryFilter = $from === 'query';

        if($isQueryFilter)
        {
            if(empty($filter['items'])) $filter['items'] = $this->pivot->getSysOptions($filter['typeOption']);
            return $filter['items'];
        }

        if(!isset($fieldSettings[$filterField])) return array();

        $fieldSetting = $fieldSettings[$filterField];
        extract($fieldSetting);
        return $this->pivot->getSysOptions($type, $object, $field, $sql, zget($filter, 'saveAs', ''));
    }

    /**
     * 处理第一步设置字段名操作。
     * Handle save fields action for step 1.
     *
     * @access public
     * @return void
     */
    public function handleSaveFieldsDesignAction()
    {
        $fieldSettings = $this->pivotState->fieldSettings;
        $relatedObject = $this->pivotState->relatedObject;
        $langs         = $this->pivotState->langs;
        $objectFields  = $this->loadModel('dataview')->getObjectFields();

        foreach($fieldSettings as $field => $setting)
        {
            foreach($this->config->langs as $lang => $langName)
            {
                if(!(isset($fieldSettings[$field][$lang]) && isset($langs[$field][$lang]))) continue;
                $langs[$field][$lang] = $fieldSettings[$field][$lang];
            }
        }

        $this->pivotState->fieldSettings = $fieldSettings;
        $this->pivotState->langs         = $langs;
        $this->pivotState->buildQuerySqlCols();
    }

    /**
     * 处理第三步添加筛选器操作。
     * Handle filters action for step 3.
     *
     * @access public
     * @return void
     */
    public function handleAddFilterDesignAction()
    {
        $this->pivotState->addFilter();
    }

    public function handleChangeModeDesignAction()
    {
        if($this->pivotState->mode == 'builder')
        {
            $this->pivotState->sql = '';
            $this->pivotState->step2FinishSql = '';
            $this->pivotState->clearFieldSettings();
            $this->pivotState->clearSettings();
            $this->pivotState->clearColumnDrill();
            $this->pivotState->clearFilters();
            $this->pivotState->clearDrills();
            $this->pivotState->queryCols = array();
            $this->pivotState->queryData = array();
            $this->pivotState->pivotCols = array();
            $this->pivotState->pivotData = array();
        }
    }
}
