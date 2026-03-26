<?php
class zentaobizPivot extends pivotModel
{
    /**
     * Get pivots.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($dimensionID = 0, $groupID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $this->loadModel('screen');
        $fieldList = array_keys($this->config->pivot->dtable->definition->fieldList);
        /* 因为最后一个是actions，所以去掉最后一个。*/
        /* Since the last one is actions, we drop the last one. */
        array_pop($fieldList);

        $pivotFields = "t1.id,t1.createdBy,t1.group,t1.version,t1.code,t1.builtin,t1.stage,t2.name,t2.desc";
        $chartFields = '`' . implode('`,`', $fieldList) . '`,code,builtin,stage,type';

        if($groupID)
        {
            $groups = $this->dao->select('id')->from(TABLE_MODULE)
                ->where('deleted')->eq(0)
                ->andWhere('type')->eq('pivot')
                ->andWhere('path')->like("%,$groupID,%")
                ->fetchPairs('id');

            $conditions = '';
            foreach($groups as $groupID) $conditions .= " FIND_IN_SET($groupID, `group`) or";
            $conditions = trim($conditions, 'or');

            $pivots = $this->dao->select($pivotFields)->from(TABLE_PIVOT)->alias('t1')
                ->leftJoin(TABLE_PIVOTSPEC)->alias('t2')->on('t1.id=t2.pivot and t1.version=t2.version')
                ->where('deleted')->eq('0')
                ->beginIF($conditions)->andWhere("({$conditions})")->fi()
                ->beginIF(!empty($dimensionID))->andWhere('dimension')->eq($dimensionID)->fi()
                ->orderBy($orderBy)
                ->fetchAll();

            $charts = $this->dao->select($chartFields)->from(TABLE_CHART)
                ->where('deleted')->eq('0')
                ->andWhere('type')->eq('table')
                ->beginIF($conditions)->andWhere("({$conditions})")->fi()
                ->beginIF(!empty($dimensionID))->andWhere('dimension')->eq($dimensionID)->fi()
                ->orderBy($orderBy)
                ->fetchAll();

            $pivots = array_merge($pivots, $charts);
        }
        else
        {
            $pivots = $this->dao->select($pivotFields)->from(TABLE_PIVOT)->alias('t1')
                ->leftJoin(TABLE_PIVOTSPEC)->alias('t2')->on('t1.id=t2.pivot and t1.version=t2.version')
                ->where('1=1')
                ->beginIF(!empty($dimensionID))->andWhere('t1.dimension')->eq($dimensionID)->fi()
                ->orderBy($orderBy)
                ->fetchAll();

            $charts = $this->dao->select($chartFields)->from(TABLE_CHART)
                ->where('deleted')->eq('0')
                ->andWhere('type')->eq('table')
                ->beginIF(!empty($dimensionID))->andWhere('dimension')->eq($dimensionID)->fi()
                ->orderBy($orderBy)
                ->fetchAll();

            $pivots = array_merge($pivots, $charts);
        }

        $pivots = $this->filterInvisiblePivot($pivots);

        if(!empty($pager))
        {
            $pager->setRecTotal(count($pivots));
            $pager->setPageTotal();
            if($pager->pageID > $pager->pageTotal) $pager->setPageID($pager->pageTotal);

            if($pivots)
            {
                $pivots = array_chunk($pivots, $pager->recPerPage);
                $pivots = $pivots[$pager->pageID - 1];
            }

        }

        return $this->processPivot($pivots, false);
    }

    /**
     * Get pivot filed list by pivot design.
     *
     * @param  int    $pivotID
     * @access public
     * @return array
     */
    public function getPivotFieldList($pivotID)
    {
        $pivot     = $this->getByID($pivotID);
        $fieldList = array();

        if(isset($pivot->fieldSettings) and is_array($pivot->fieldSettings))
        {
            foreach($pivot->fieldSettings as $field => $fieldSetting) $fieldList[$field] = $fieldSetting->name;
        }

        return $fieldList;
    }

    /**
     * Create pivot.
     *
     * @param  int    $dimensionID
     * @access public
     * @return int
     */
    public function create($dimensionID)
    {
        $pivot = fixer::input('post')
            ->setDefault('dimension', $dimensionID)
            ->setDefault('group', '')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setForce('name', '')
            ->setForce('desc', '')
            ->setForce('sql', '')
            ->join('group', ',')
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactList')
            ->get();

        $this->dao->insert(TABLE_PIVOT)->data($pivot)
            ->batchCheck($this->config->pivot->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        $pivotID = $this->dao->lastInsertID();

        $pivotSpec = new stdclass();
        $pivotSpec->pivot       = $pivotID;
        $pivotSpec->version     = '1';
        $pivotSpec->createdDate = helper::now();
        $pivotSpec = $this->processNameAndDesc($pivotSpec);

        $this->dao->insert(TABLE_PIVOTSPEC)->data($pivotSpec)->autoCheck()->exec();

        return $pivotID;
    }

    /**
     * Save query a pivot.
     *
     * @param int $pivotID
     * @access public
     * @return bool | int
     */
    public function querySave($pivotID)
    {
        $post = fixer::input('post')->skipSpecial('fields,objects,sql')->get();

        $this->dao->update(TABLE_PIVOT)
            ->set('sql')->eq($post->sql)
            ->set('fields')->eq($post->fields)
            ->set('settings')->eq('')
            ->where('id')->eq($pivotID)
            ->exec();

        if(dao::isError()) return false;
    }

    /**
     * Edit pivot basic field.
     *
     * @param  int $pivotID
     * @param  int $step
     * @access public
     * @return void
     */
    public function edit($pivotID)
    {
        $pivot = fixer::input('post')
            ->setDefault('group', '')
            ->setForce('name', '')
            ->setForce('desc', '')
            ->join('group', ',')
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('namelang,desclang,contactList')
            ->get();

        $pivot = $this->processNameAndDesc($pivot);

        $pivotSpec = new stdclass();
        $pivotSpec->name   = $pivot->name;
        $pivotSpec->desc   = $pivot->desc;
        $pivotSpec->driver = $pivot->driver;
        unset($pivot->name);
        unset($pivot->desc);
        unset($pivot->driver);

        $this->dao->update(TABLE_PIVOT)
            ->data($pivot)
            ->batchCheck($this->config->pivot->design->requiredFields, 'notempty')
            ->where('id')->eq($pivotID)
            ->exec();

        $this->dao->update(TABLE_PIVOTSPEC)
            ->data($pivotSpec)
            ->where('pivot')->eq($pivotID)
            ->andWhere('version')->eq($pivot->version)
            ->exec();

        if(dao::isError()) return false;
    }

    /**
     * Update pivot.
     *
     * @param  int $pivotID
     * @param  int $step
     * @access public
     * @return void
     */
    public function update($pivotID, $pivotState)
    {
        $pivot     = $this->getByID($pivotID);
        $version   = $pivot->version;
        $upVersion = $pivot->stage === 'published';

        if($upVersion)
        {
            if($pivot->builtin == '1')
            {
                $splitVersion = explode('.', $version);
                $majorVersion = $splitVersion[0];
                $minorVersion = isset($splitVersion[1]) ? $splitVersion[1] : 0;
                $minorVersion = (int)$minorVersion + 1;
                $version = $majorVersion . '.' . (string)$minorVersion;
            }
            else
            {
                $version = (int)$pivot->version + 1;
            }
        }

        if(empty($pivotState->sql))
        {
            $pivotState->clearFieldSettings();
            $pivotState->clearSettings();
            $pivotState->clearFilters();
            $pivotState->clearDrills();
        }

        $pivotState->clearColumnDrill();

        $data = new stdclass();
        $data->version   = (string)$version;
        $data->group     = is_array($pivotState->group) ? implode(',', $pivotState->group) : $pivotState->group;
        $data->stage     = $pivotState->stage;
        $data->acl       = $pivotState->acl;
        $data->whitelist = $data->acl == 'private' ?  $pivotState->whitelist : '';

        $this->loadModel('sqlbuilder')->save($pivotID, 'pivot', $pivotState->sqlbuilder);

        $this->dao->update(TABLE_PIVOT)->data($data)
            ->where('id')->eq($pivotID)
            ->batchCheck($this->config->pivot->design->requiredFields, 'notempty')
            ->exec();

        $this->updateDrills($pivotState->id, (string)$version, $pivotState->drills);

        $specData = new stdclass();
        $specData->pivot       = $pivotID;
        $specData->version     = (string)$version;
        $specData->driver      = $pivot->driver;
        $specData->mode        = $pivotState->mode;
        $specData->name        = json_encode($pivotState->names);
        $specData->desc        = json_encode($pivotState->descs);
        $specData->settings    = json_encode($pivotState->settings);
        $specData->fields      = $pivotState->getFields('json');
        $specData->sql         = $pivotState->sql;
        $specData->langs       = $pivotState->getLangs('json');
        $specData->filters     = $pivotState->getFiltersForSave('json');
        $specData->vars        = json_encode($pivotState->vars);
        $specData->createdDate = helper::now();
        if(isset($specData->fields)) $specData->fields = html_entity_decode($specData->fields);

        if($upVersion)
        {
            $this->dao->insert(TABLE_PIVOTSPEC)->data($specData)->autoCheck()->exec();
        }
        else
        {
            $this->dao->update(TABLE_PIVOTSPEC)->data($specData)
                ->where('pivot')->eq($pivotID)
                ->andWhere('version')->eq($version)
                ->autoCheck()->exec();
        }

        if(dao::isError()) return false;
    }

    /**
     * Update pivot drills
     *
     * @param  int    $pivotID
     * @param  array  $drills
     * @param  string $status
     * @access public
     * @return void
     */
    public function updateDrills($pivotID, $version, $drills, $status = 'published')
    {
        $this->dao->delete()->from(TABLE_PIVOTDRILL)
            ->where('pivot')->eq($pivotID)
            ->andWhere('version')->eq($version)
            ->beginIF($status == 'design')
            ->andWhere('status')->eq($status)
            ->andWhere('account')->eq($this->app->user->account)
            ->fi()
            ->exec();

        $drills = array_values(array_filter($drills));
        if(!empty($drills))
        {
            foreach($drills as $drillInfo)
            {
                $drillInfo = (array)$drillInfo;
                if(empty($drillInfo['field'])) continue;
                $drill = new stdclass();
                $drill->pivot     = $pivotID;
                $drill->version   = $version;
                $drill->field     = $drillInfo['field'];
                $drill->object    = $drillInfo['object'];
                $drill->whereSql  = $drillInfo['whereSql'];
                $drill->condition = json_encode($drillInfo['condition']);
                $drill->status    = $status;
                $drill->type      = !empty($drillInfo['type']) ? $drillInfo['type'] : 'manual';

                if(!$drill->whereSql)   $drill->whereSql = '';
                if($status == 'design') $drill->account  = $this->app->user->account;

                $this->dao->insert(TABLE_PIVOTDRILL)->data($drill)->exec();
            }
        }
    }

    /**
     * Update design filters.
     *
     * @param  int    $pivotID
     * @param  array  $designingFilters
     * @access public
     * @return bool
     */
    public function updateDesignFilters($pivotID, $designingFilters)
    {
        $filters = $this->dao->select('filters')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->fetch('filters');

        if(empty($filters))
        {
            $filters = array();
        }
        else
        {
            $filters = json_decode($filters, true);
            foreach($filters as $index => $filter)
            {
                if(isset($filter['status']) && $filter['status'] == 'design' && isset($filter['account']) && $filter['account'] == $this->app->user->account)
                {
                    unset($filters[$index]);
                    continue;
                }
            }
        }

        foreach($designingFilters as $index => $designingFilter)
        {
            $designingFilters[$index]['status']  = 'design';
            $designingFilters[$index]['account'] = $this->app->user->account;
        }

        $filters = array_merge($filters, $designingFilters);

        $this->dao->update(TABLE_PIVOT)->set('filters')->eq(json_encode(array_filter($filters)))->where('id')->eq($pivotID)->exec();

        return !dao::isError();
    }

    /**
     * Process a pivot's name and description from the post data.
     *
     * @param  obejct $data
     * @access public
     * @return object
     */
    public function processNameAndDesc($data)
    {
        $clientLang = $this->app->getClientLang();

        if(empty($_POST['name'][$clientLang])) dao::$errors['name' . $clientLang][] = zget($this->config->langs, $clientLang) . sprintf($this->lang->error->notempty, $this->lang->pivot->name);
        if(dao::isError()) return $data;

        $names = array();
        foreach($this->post->name as $langKey => $name)
        {
            $name = trim($name);
            $names[$langKey] = htmlspecialchars($name);
        }

        if($names) $data->name = json_encode($names);

        $descs = array();
        foreach($this->post->desc as $langKey => $desc)
        {
            $desc = trim($desc);
            if(empty($desc)) continue;

            $descs[$langKey] = strip_tags($desc, $this->config->allowedTags !== null && is_array($this->config->allowedTags) ? '<' . implode('><', $this->config->allowedTags) . '>' : $this->config->allowedTags);
        }
        if($descs) $data->desc = json_encode($descs);

        return $data;
    }

    /**
     * Process group value.
     *
     * @param  array    $pivots
     * @param  array    $groups
     * @access public
     * @return array
     */
    public function processGroup($pivots, $groups)
    {
        foreach($pivots as $pivot)
        {
            $groupIDs     = explode(',', $pivot->group);
            $filterGroups = array_filter($groups, function($id) use ($groupIDs) {return in_array($id, $groupIDs);}, ARRAY_FILTER_USE_KEY);
            $pivot->group = implode(',', $filterGroups);
        }
        return $pivots;
    }

    /**
     * Get pivot column pairs.
     *
     * @param  array  $fieldSettings
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function getCommonColumn($fieldSettings, $langs)
    {
        $this->loadModel('workflowfield');
        $fieldPairs     = array();
        $workflowFields = array();
        $clientLang     = $this->app->getClientLang();
        foreach($fieldSettings as $field => $fieldList)
        {
            $fieldObject  = $fieldList['object'];
            $relatedField = $fieldList['field'];

            $this->app->loadLang($fieldObject);
            $fieldPairs[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;

            if(!isset($workflowFields[$fieldObject])) $workflowFields[$fieldObject] = $this->workflowfield->getFieldPairs($fieldObject);
            if(isset($workflowFields[$fieldObject][$relatedField])) $fieldPairs[$field] = $workflowFields[$fieldObject][$relatedField];

            if(!isset($langs[$field])) continue;
            if(!empty($langs[$field][$clientLang])) $fieldPairs[$field] = $langs[$field][$clientLang];
        }

        return $fieldPairs;
    }

    /**
     * Auto generate where condition according to sql.
     *
     * @param  string $objectTable
     * @param  string $sql
     * @access public
     * @return string
     */
    public function autoGenWhereSQL($objectTable, $sql)
    {
        $this->app->loadClass('sqlparser', true);
        $parser    = new sqlparser($sql);
        $statement = $parser->statements[0];

        $isSimpleSQL = (count($statement->from) == 1 && empty($statement->from[0]->alias));

        list($autoWhereExpr, $appendOperator) = $this->getAutoWhereExpr($statement, $objectTable, $isSimpleSQL);

        if(!empty($autoWhereExpr)) $autoWhereExpr = 'WHERE' . $autoWhereExpr;
        if(common::strEndsWith($autoWhereExpr, 'AND')) $autoWhereExpr = substr($autoWhereExpr, 0, -3);
        if(common::strEndsWith($autoWhereExpr, 'OR'))  $autoWhereExpr = substr($autoWhereExpr, 0, -2);
        return $autoWhereExpr;
    }

    /**
     * Auto generate where expression according to sql.
     *
     * @param  object $statement
     * @param  string $objectTable
     * @param  bool   $isSimpleSQL
     * @access public
     * @return string
     */
    public function getAutoWhereExpr($statement, $objectTable, $isSimpleSQL)
    {
        $autoWhereExpr = '';
        $appendOperator = false;
        if(empty($statement->where)) return array($autoWhereExpr, $appendOperator);

        foreach($statement->where as $condition)
        {
            if($condition->isOperator == 1 && $appendOperator)
            {
                $autoWhereExpr .= ' ' . $condition->expr;
                $appendOperator = false;
            }

            if(empty($condition->isOperator))
            {
                if($isSimpleSQL && $objectTable == $statement->from[0]->table)
                {
                    $autoWhereExpr .= ' t1.' . $condition->expr;
                    $appendOperator = true;
                }

                if(empty($condition->identifiers)) continue;
                $tableAlias = $condition->identifiers[0];
                $tableName  = $this->loadModel('bi')->getTableByAlias($statement, $tableAlias);

                if($tableName && $tableName == $objectTable)
                {
                    $autoWhereExpr .= ' ' . str_replace($tableAlias, 't1', $condition->expr);
                    $appendOperator = true;
                }
            }
        }

        return array($autoWhereExpr, $appendOperator);
    }

    /**
     * Auto generate refer sql according to target table.
     *
     * @param  string $objectTable
     * @access public
     * @return string
     */
    public function autoGenReferSQL($objectTable)
    {
        $objectTable = $this->config->db->prefix . $objectTable;
        return "SELECT t1.id FROM $objectTable AS t1";
    }

    /**
     * Get drill field list.
     *
     * @param  object $drill
     * @access public
     * @return array
     */
    public function getDrillFieldList($drill)
    {
        $this->loadModel('bi');
        $object    = $drill['object'];
        $whereSql  = isset($drill['whereSql']) && $drill['whereSql'] ? $drill['whereSql'] : '';
        $sql       = $this->getReferSQL($object, $whereSql);
        $tableList = $this->bi->parseTableList($sql);

        $drillFieldList = array();
        foreach($tableList as $alias => $table)
        {
            $object    = substr($table, strpos($table, '_') + 1);
            $tableName = isset($this->lang->dev->tableList[$object]) ? $this->lang->dev->tableList[$object] : $object;
            if($object == 'story') $tableName = $this->lang->pivot->story;

            $isSubquery = preg_match('/^\(.*\)$/', $table);

            if($isSubquery)
            {
                $sql      = trim(trim($table, '('), ')');
                $limitSQL = "SELECT * FROM ($sql) AS t1 limit 1";

                $queryResult = $this->bi->querySQL($sql, $limitSQL);
                if($queryResult['result'] == 'fail') continue;

                $fields = array_keys((array)$queryResult['rows'][0]);
                foreach($fields as $field)
                {
                    $fieldKey   = "$alias.$field";
                    $fieldTitle = "$alias.$field";

                    $drillFieldList[$fieldKey] = $fieldTitle;
                }
            }
            else
            {
                $fields = $this->loadModel('dev')->getFields($table);
                foreach($fields as $fieldName => $field)
                {
                    $fieldKey   = "$alias.$table.$fieldName";

                    $fieldName = !empty($field['name']) ? $field['name'] : $fieldName;
                    $fieldTitle = sprintf($this->lang->pivot->drill->drillFieldText, $tableName, $alias, $fieldName);

                    $drillFieldList[$fieldKey] = $fieldTitle;
                }
            }
        }

        return $drillFieldList;
    }

    /**
     * Get field list.
     *
     * @param  object   $pivotState
     * @param  int|null $modalIndex
     * @access public
     * @return array
     */
    public function getFieldList($pivotState, $modalIndex = null)
    {
        $clientLang     = $this->app->getClientLang();

        $fieldList = array();
        if(isset($pivotState->settings['summary']) and $pivotState->settings['summary'] == 'notuse')
        {
            foreach($pivotState->fieldSettings as $key => $field)
            {
                $fieldList[$key] = $this->getColLabel($key, $pivotState->fieldSettings, $pivotState->langs);
            }
        }
        elseif(isset($pivotState->settings['columns']))
        {
            foreach($pivotState->settings['columns'] as $column)
            {
                if(!empty($column['showOrigin'])) continue;
                $field = $column['field'];

                $fieldText = $pivotState->fieldSettings[$field][$clientLang];
                $colLabel  = str_replace('{$field}', $fieldText, $this->lang->pivot->colLabel);
                $colLabel  = str_replace('{$stat}', zget($this->lang->pivot->stepDesign->statList, $column['stat']), $colLabel);
                $fieldList[$column['field']] = $colLabel;
            }
        }

        $fieldItems = array();
        $drilledFields = array_column($pivotState->drills, 'field');
        $currentDrill  = isset($modalIndex) ? $pivotState->drills[$modalIndex] : null;

        foreach($fieldList as $field => $fieldName)
        {
            $isCurrentField = !is_null($currentDrill) && $field == $currentDrill['field'];
            $isDisabled     = in_array($field, $drilledFields) && !$isCurrentField;

            $fieldItems[] = array('value' => $field, 'text' => $fieldName, 'disabled' => $isDisabled);
        }

        return $fieldItems;
    }

    /**
     * 构建查询条件的sql语句。
     * Prepare condition sql.
     *
     * @param  array  $conditions
     * @access public
     * @return string
     */
    public function prepareConditionSql($conditions)
    {
        $sql = "WHERE 1=1";
        foreach($conditions as $objectField => $value) $sql .= " AND t1.$objectField = '$value'";

        return $sql;
    }

    /**
     * Clear drills generated by automatic.
     *
     * @param  array  $drills
     * @access public
     * @return array
     */
    public function clearAutoDrills($drills)
    {
        return array_values(array_filter($drills, function($drill)
        {
            $drill = (array)$drill;
            return empty($drill['type']) || $drill['type'] != 'auto';
        }));
    }

    /**
     * Auto generate drill settings by columns.
     *
     * @param  object  $pivotState
     * @access public
     * @return object
     */
    public function autoGenDrillSettings($pivotState)
    {
        $pivotID  = $pivotState->id;
        $sql      = $pivotState->sql;
        $settings = $pivotState->settings;
        $drills   = $pivotState->drills;

        if(!empty($drills)) $drills = $this->clearAutoDrills($drills);

        $columns     = zget($settings, 'columns', array());
        $drills      = !empty($drills) ? array_filter($drills) : array();
        $tableFields = $this->loadModel('bi')->getFieldsWithTable($sql);

        $drilledFields = array();
        foreach($drills as $drill)
        {
            $drill = (array)$drill;
            $drilledFields[] = $drill['field'];
        }

        foreach($columns as $index => $column)
        {
            if(!empty($column['drill']) && $column['drill']['type'] == 'manual') continue;
            if(in_array($column['field'], $drilledFields)) continue;
            if(empty($tableFields[$column['field']])) continue;
            if($column['showOrigin'] == '1') continue;

            $table  = $tableFields[$column['field']];
            $object = substr($table, strpos($table, '_') + 1);

            $whereSQL  = $this->autoGenWhereSQL($table, $sql);
            $result    = $this->getDrillResult($object, $whereSQL, $pivotState->filters, array());
            $condition = $this->autoGenDrillConditions($object, $column['field'], $pivotState);

            if(empty($condition)) continue;

            $drill = array();
            $drill['pivot']     = $pivotID;
            $drill['field']     = $column['field'];
            $drill['object']    = $object;
            $drill['whereSql']  = $result['status'] == 'success' ? $whereSQL : '';
            $drill['condition'] = $condition;
            $drill['type']      = 'auto';

            $drilledFields[]      = $drill['field'];
            $drills[] = $drill;

            $pivotState->settings['columns'][$index]['drill'] = $drill;
        }

        $pivotState->drills = $drills;
    }

    /**
     * Auto generate drill conditions
     *
     * @param  string $object
     * @param  string $field
     * @param  object $pivotState
     * @access public
     * @return array
     */
    public function autoGenDrillConditions($object, $field, $pivotState)
    {
        $this->loadModel('bi');
        $table       = $this->config->db->prefix . $object;
        $settings    = $pivotState->settings;
        $tableFields = $this->bi->getFieldsWithTable($pivotState->sql);
        $aliasFields = $this->bi->getFieldsWithAlias($pivotState->sql);

        $columns = !empty($settings['columns']) ? $settings['columns'] : array();
        $filters = !empty($pivotState->filters) ? $pivotState->filters : array();

        $drilledFields = array();
        $conditions = array();
        foreach($settings as $key => $setting)
        {
            if(strncmp($key, 'group', strlen('group')) === 0)
            {
                if(in_array($setting, $drilledFields)) continue;
                if($tableFields[$setting] != $table)   continue;

                $condition = array();
                $condition['drillObject'] = $table;
                $condition['drillAlias']  = 't1';
                $condition['drillField']  = $aliasFields[$setting];
                $condition['queryField']  = $setting;

                $drilledFields[] = $setting;
                $conditions[]    = $condition;
            }
        }

        foreach($columns as $column)
        {
            $slice = zget($column, 'slice', 'noSlice');

            if($slice == 'noSlice')              continue;
            if(in_array($slice, $drilledFields)) continue;
            if($tableFields[$slice] != $table)   continue;
            if($column['field'] != $field)       continue;

            $condition = array();
            $condition['drillObject'] = $table;
            $condition['drillAlias']  = 't1';
            $condition['drillField']  = $aliasFields[$slice];
            $condition['queryField']  = $slice;

            $drilledFields[] = $slice;
            $conditions[]    = $condition;

        }

        foreach($filters as $filter)
        {
            if(isset($filter['from']) && $filter['from'] == 'query') continue;
            if(in_array($filter['field'], $drilledFields)) continue;
            if($tableFields[$filter['field']] != $table)   continue;

            $condition = array();
            $condition['drillObject'] = $table;
            $condition['drillAlias']  = 't1';
            $condition['drillField']  = $aliasFields[$filter['field']];
            $condition['queryField']  = $filter['field'];

            $drilledFields[] = $filter['field'];
            $conditions[]    = $condition;
        }

        return $conditions;
    }

    /**
     * Get table desc list.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    public function getTableDescList($table)
    {
        $fields = $this->loadModel('dev')->getFields($this->config->db->prefix . $table);

        $fieldList = array();
        foreach($fields as $field => $info)
        {
            $name = !empty($info['name']) ? $info['name'] : $field;
            $fieldList[$field] = $name;
        }

        return $fieldList;
    }

    /**
     * Filter special chars in pivot state.
     *
     * @param  object $pivotState
     * @access public
     * @return object
     */
    public function filterPivotSpecialChars($pivotState)
    {
        $pivotState->queryData = $this->filterSpecialChars($pivotState->queryData);
        $pivotState->pivotData = $this->filterSpecialChars($pivotState->pivotData);

        return $pivotState;
    }
}
