<?php
/**
 * The model file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class zentaobizChart extends chartModel
{
    /**
     * Get chart filed list.
     *
     * @param  int    $chartID
     * @access public
     * @return array
     */
    public function getChartFieldList($chartID)
    {
        $chart     = $this->getByID($chartID);
        $fieldList = array();
        if(isset($chart->fieldSettings) and is_array($chart->fieldSettings))
        {
            foreach($chart->fieldSettings as $field => $fieldSetting)
            {
                $fieldObject  = $fieldSetting['object'];
                $relatedField = $fieldSetting['field'];

                $this->app->loadLang($fieldObject);
                $fieldList[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;
            }
        }

        return $fieldList;
    }

    /**
     * Create chart.
     *
     * @param  int    $dimensionID
     * @access public
     * @return int
     */
    public function create($dimensionID)
    {
        $data = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('group', '')
            ->setDefault('dimension', $dimensionID)
            ->join('group', ',')
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactListMenu')
            ->get();

        $this->dao->insert(TABLE_CHART)->data($data)
            ->batchCheck($this->config->chart->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Edit chart basic field.
     *
     * @param  int $chartID
     * @access public
     * @return void
     */
    public function edit($chartID)
    {
        $chart = fixer::input('post')
            ->setDefault('group', '')
            ->join('group', ',')
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactListMenu')
            ->get();

        $this->dao->update(TABLE_CHART)
            ->data($chart)
            ->where('id')->eq($chartID)
            ->batchCheck($this->config->chart->design->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;
    }

    /**
     * Update chart.
     *
     * @param  int $chartID
     * @param  int $step
     * @access public
     * @return void
     */
    public function update($chartID)
    {
        $chart = fixer::input('post')
            ->skipSpecial('sql')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('sqlChange')
            ->get();

        $data = new stdclass();
        $data->name     = $chart->name;
        $data->type     = $chart->type;
        $data->group    = $chart->group;
        $data->desc     = $chart->desc;
        $data->stage    = $chart->stage;
        $data->step     = $chart->step;
        $data->mode     = $chart->mode;
        $data->settings = json_encode($chart->settings);
        $data->fields   = isset($chart->fieldSettings) ? json_encode($chart->fieldSettings) : '';
        $data->sql      = base64_decode($chart->sql);
        $data->langs    = !empty($chart->langs) ? json_encode($chart->langs) : '';
        $data->filters  = isset($chart->filters) ? json_encode($chart->filters) : '[]';
        $data->acl       = $chart->acl;
        $data->whitelist = $chart->whitelist && $chart->acl == 'private' ? implode(',', array_filter($chart->whitelist)) : '';

        if(isset($data->fields)) $data->fields = html_entity_decode($data->fields);

        $this->dao->update(TABLE_CHART)
            ->data($data)
            ->where('id')->eq($chartID)
            ->batchCheck($this->config->chart->design->requiredFields, 'notempty')
            ->exec();

        if($chart->mode == 'builder')
        {
            $sqlbuilder = json_decode($this->post->sqlbuilder, true);
            if(!empty($sqlbuilder['sql'])) $sqlbuilder['sql'] = base64_decode($sqlbuilder['sql']);
            $this->loadModel('sqlbuilder')->save($chartID, 'chart', $sqlbuilder);
        }

        return !dao::isError();
    }

    /**
     * Get chart.
     *
     * @param  int    $chartID
     * @access public
     * @return object
     */
    public function getByID($chartID)
    {
        $chart = parent::getByID($chartID);
        if(!$chart) return $chart;

        $chart->used = $this->loadModel('pivot')->checkIFChartInUse($chart->id, 'chart');

        return $chart;
    }

    /**
     * Get charts.
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
        $viewableObjects = $this->loadModel('bi')->getViewableObject('chart');
        $this->loadModel('screen');
        if($groupID)
        {
            $groups = $this->dao->select('id')->from(TABLE_MODULE)
                ->where('deleted')->eq(0)
                ->andWhere('type')->eq('chart')
                ->andWhere('path')->like("%,$groupID,%")
                ->fetchPairs('id');

            $conditions = '';
            foreach($groups as $groupID)
            {
                $conditions .= " FIND_IN_SET($groupID, `group`) or";
            }
            $conditions = trim($conditions, 'or');

            $charts = $this->dao->select('*')->from(TABLE_CHART)
                ->where('deleted')->eq(0)
                ->andWhere('type')->ne('table')
                ->andWhere('id')->in($viewableObjects)
                ->beginIF($conditions)->andWhere("({$conditions})")->fi()
                ->beginIF(!empty($dimensionID))->andWhere('dimension')->eq($dimensionID)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id', false);
        }
        else
        {
            $charts = $this->dao->select('*')->from(TABLE_CHART)
                ->where('deleted')->eq(0)
                ->andWhere('type')->ne('table')
                ->andWhere('id')->in($viewableObjects)
                ->beginIF(!empty($dimensionID))->andWhere('dimension')->eq($dimensionID)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id', false);
        }

        return $this->processCharts($charts);
    }

    /**
     * Batch process sql, setting, type of charts.
     *
     * @param  array  $charts
     * @access public
     * @return array
     */
    public function processCharts($charts)
    {
        $this->loadModel('screen');
        foreach($charts as $chart)
        {
            $chart = $this->processChart($chart);
            $chart->used = $this->loadModel('pivot')->checkIFChartInUse($chart->id, 'chart');
        }
        return $charts;
    }

    /**
     * Get data of a chart.
     *
     * @param  object $chart
     * @access public
     * @return array
     */
    public function getChartData($chart)
    {
        $filterValues = json_decode($chart->filters);

        $rows = array();
        if(strpos($chart->type, 'Report') === false)
        {
            $fields   = json_decode(json_encode($chart->fieldSettings), true);
            $settings = json_decode($chart->settings, true);
            /* Remove deleted fields.*/
            if(!empty($settings))
            {
                a($settings);
                foreach($settings as $type => $setting)
                {
                    foreach($setting as $key => $settingField)
                    {
                        if(strpos($settingField['field'], '.') !== false)
                        {
                            $settingType = explode('.', $settingField['field']);
                            if(!isset($fields[$settingType[0]][$settingType[1]]) and !isset($fields[$settingType[0]]['children'][$settingType[1]])) unset($settings[$type][$key]);
                        }
                        if(strpos($settingField['field'], '.') === false and !isset($fields[$settingField['field']])) unset($settings[$type][$key]);
                    }
                }
            }

            $filter = isset($settings['filter']) ? $settings['filter'] : array();
            $group  = isset($settings['group'])  ? $settings['group']  : array();

            /* Line data must be ordered by time. */
            if($chart->type == 'line')
            {
                $order = array(array('value' => $settings['xaxis'][0]['field'], 'sort' => 'asc'));
            }
            else
            {
                $order = isset($settings['order']) ? $settings['order'] : array();
            }
            $sql  = $this->loadModel('dataset')->stripVars($chart->sql);
            $rows = $this->dao->query($sql)->fetchAll();
        }

        $data  = array();
        $users = $this->loadModel('user')->getPairs('noletter');
        switch($chart->type)
        {
        case 'table':
        case 'line':
        case 'bar':
        case 'pie':
            $func = 'gen' . ucfirst($chart->type) . 'Data';
            $data = $this->$func($fields, $settings, $rows, $users);
            break;
        case 'testingReport':
        case 'buildTestingReport':
            if(!$filterValues['build.id'])
            {
                $filters = array('project' => array(), 'execution' => array(), 'build' => array());
                list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                $filterValues['project.id']   = $defaults['project'];
                $filterValues['execution.id'] = $defaults['execution'];
                $filterValues['build.id']     = $defaults['build'];
            }
            $data = $this->genTestingReport($chart->type, $filterValues);
            break;
        case 'executionTestingReport':
            if(!$filterValues['execution.id'])
            {
                $filters = array('project' => array(), 'execution' => array());
                list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                $filterValues['project.id']   = $defaults['project'];
                $filterValues['execution.id'] = $defaults['execution'];
            }
            $data = $this->genTestingReport($chart->type, $filterValues);
            break;
        case 'projectTestingReport':
            if(!$filterValues['project.id'])
            {
                $filters = array('project' => array());
                list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                $filterValues['project.id'] = $defaults['project'];
            }
            $data = $this->genTestingReport($chart->type, $filterValues);
            break;
        case 'dailyTestingReport':
            if(!$filterValues['build.id'])
            {
                $filters = array('project' => array(), 'execution' => array(), 'build' => array());
                list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                $filterValues['project.id']   = $defaults['project'];
                $filterValues['execution.id'] = $defaults['execution'];
                $filterValues['build.id']     = $defaults['build'];
            }
            $data = $this->genTestingReport($chart->type, $filterValues);
            break;
        }

        if($data) $data['title'] = array('text' => $chart->name, 'top' => 10, 'left' => 10);

        return $data;
    }

    /**
     * Get table data of a line chart or a bar chart or a pie chart.
     *
     * @param  object $chart
     * @access public
     * @return array
     */
    public function getTableData($chart)
    {
        if(strpos('line,bar,pie', $chart->type) === false) return array();

        $tableChart = clone $chart;
        $tableChart->type = 'table';

        $settings = json_decode($chart->settings);
        if($chart->type == 'line')
        {
            foreach($settings->xaxis as $x)
            {
                $x->dateGroup = $x->group;
                $settings->group[] = $x;
            }
            $settings->group  = $settings->xaxis;
            $settings->column = $settings->yaxis;
        }
        if($chart->type == 'bar')
        {
            $settings->group  = $settings->xaxis;
            $settings->column = $settings->yaxis;
        }
        if($chart->type == 'pie')
        {
            $settings->column = $settings->metric;
        }

        $tableChart->settings = json_encode($settings);
        return $this->getChartData($tableChart);
    }

    /**
     * Get fields.
     *
     * @param  object $info
     * @access public
     * @return array
     */
    public function getFields($info)
    {
        $fields = array();
        if(empty($info->schema->fields)) return array();
        foreach($info->schema->fields as $key => $field)
        {
            if($field['type'] == 'object')
            {
                $object = $field['object'];
                $field['children'] = $info->schema->objects[$object];
            }

            if(!$field['name']) $field['name'] = $key;
            $fields[$key] = $field;
        }

        return $fields;
    }

    /**
     * Get table info from key.
     *
     * @param  string $key
     * @access public
     * @return object
     */
    public function getTableInfo($key)
    {
        $this->loadmodel('dataview');

        $info      = new stdclass();
        $info->key = $key;

        /* Middle table */
        if(strpos($key, 'ztv_bi_') === 0)
        {
            $dataview = $this->dataview->getByView($key);
            $fields   = $this->dataview->getFields($dataview->id);

            $info->name   = $dataview->name;
            $info->desc   = $dataview->name;
            $info->schema = $this->getSchemaFromFields($key, $key, $fields);

            return $info;
        }

        /* Old dataview table config */
        if(isset($this->lang->dataview->tables[$key]))
        {
            $tableinfo = $this->lang->dataview->tables[$key];

            $info->name   = $tableinfo['name'];
            $info->desc   = $tableinfo['desc'];
            $info->schema = $this->dataview->includeTable($key);

            return $info;
        }

        /* Dev tables */
        $fields = $this->loadmodel('dev')->getFields($this->config->db->prefix . $key);
        $name   = $this->lang->dev->tableList[$key];

        $info->name   = $name;
        $info->desc   = $name;
        $info->schema = $this->getSchemaFromFields($key, $this->config->db->prefix . $key, $fields);

        return $info;
    }

    /**
     * Get table schema from fields.
     *
     * @param  string $key
     * @param  string $table
     * @param  array  $fields
     * @access public
     * @return object
     */
    public function getSchemaFromFields($key, $table, $fields)
    {

        $schema = new stdclass();
        $schema->primaryTable = $key;
        $schema->tables       = array($key => $table);
        $schema->joins        = array();
        $schema->fields       = array();
        $schema->objects      = array();

        $trans = $this->config->chart->transTypes;

        foreach($fields as $field => $attr)
        {
            $type = $attr['type'];

            $schema->fields[$field] = array('name' => $field, 'type' => isset($trans[$type]) ? $trans[$type] : 'string');
        }

        return $schema;
    }

    /**
     * Settings of chart.
     *
     * @param  string $type
     * @param  string $dataset
     * @access public
     * @return string
     */
    public function settings($type, $dataset)
    {
        $html = '<div class="item border"></div>';

        foreach($this->config->chart->settings[$type] as $key => $value)
        {
          $html .= '<div class="item">';
          $html .= '<p>' . $this->lang->chart->$key . '</p>';
          $html .= html::select('dataset', array() , '', "class='chosen form-control'");
          $html .= '</div>';
        }

        $html .= '<div class="item border"></div>';

        $html .= '<div class="item">';
        $html .= '<p>' . $this->lang->chart->filter . '</p>';
        $html .= html::select('filter', array(), '', "class='chosen form-control'");
        $html .= '</div>';

        $html .= '<div class="item">';
        $html .= '<p>' . $this->lang->chart->orderby . '</p>';
        $html .= html::select('orderby', array(), '', "class='chosen form-control'");
        $html .= '</div>';

        return $html;
    }

    /**
     * Get data
     *
     * @param  object $schema
     * @param  object $filter
     * @param  object $filterValues
     * @param  object $group
     * @param  object $order
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getData($schema, $filter, $filterValues, $group, $order, $limit = 0)
    {
        if(isset($schema->showDeleted) and $schema->showDeleted === false) $filter[] = array('field' => 'deleted', 'operator' => '=', 'value' => '0');

        if(isset($schema->sql))
        {
            foreach($filterValues as $key => $value)
            {
                if(!$value)
                {
                    $value = "''";
                }
                else if(is_array($value))
                {
                    $val = array();
                    foreach($value as $v) $val[] = "'$v'";
                    $value = implode(',', $val);
                }

                if(strpos($key, '.') === false) $key = $key . '.id';
                $schema->sql = str_ireplace('$' . $key, $value, $schema->sql);
            }

            $sql = $this->loadModel('dataset')->stripVars($schema->sql);
            return $this->dao->query($sql)->fetchAll();
        }

        $orderMap = array();
        foreach($order as $o)
        {
            $orderMap[$o['value']] = $o['sort'];
        }

        $orders = array();
        foreach($group as $g)
        {
            $field = $g['field'];
            if(!$field) continue;

            $sort = isset($orderMap[$field]) ? $orderMap[$field] : 'asc';
            if(strpos($field, '.') === false)
            {
                $orders[] = '`' . $field . '`' . ' ' . $sort;
            }
            else
            {
                $fields = explode('.', $field);
                $orders[] = '`' . $fields[0] . '`.`' . $fields[1] . '`' . ' ' . $sort;
            }
        }
        $orders = implode(',', $orders);

        $fields = array();
        foreach($schema->fields as $field => $info)
        {
            if($info['type'] == 'object') continue;

            $alias = str_replace('.', '_', $field);
            $fields[] = $schema->primaryTable . '.' . $field . ' AS `' . $alias . '`';
        }
        foreach($schema->objects as $object => $data)
        {
            foreach($data as $subField => $subInfo)
            {
                $alias    = $object . '_' . $subField;
                $fields[] = $object . '.' . $subField . ' AS `' . $alias . '`';
            }
        }

        $joins = array();
        foreach($schema->joins as $table => $relation)
        {
            $joins[] = " LEFT JOIN ". $schema->tables[$table] . ' AS `' . $table . "` ON $relation";
        }

        $filters = array();
        $opmap   = array('in' => 'IN', 'notin' => 'NOT IN', 'notnull' => 'IS NOT NULL', 'null' => 'IS NULL');
        $where   = array();
        foreach($filter as $f)
        {
            if(strpos($f['field'], '.') === false)
            {
                if($f['field'] == 'deleted')
                {
                    $field = '`' . $schema->primaryTable . '`.`' . $f['field'] . '`';
                    $type  = 'string';
                }
                else
                {
                    $field = '`' . $schema->primaryTable . '`.`' . $f['field'] . '`';
                    $type  = $schema->fields[$f['field']]['type'];
                }
            }
            else
            {
                $field = $f['field'];
                $fs    = explode('.', $f['field']);
                $type  = $schema->objects[$fs[0]][$fs[1]]['type'];
            }

            if($type == 'datetime')
            {
                $where[] = $field . ' >= "' . $f['value'] . ' 00:00:00"';
                $where[] = $field . ' <= "' . $f['value'] . ' 23:59:59"';
            }
            else
            {
                $operator = isset($opmap[$f['operator']]) ? $opmap[$f['operator']] : $f['operator'];
                $value    = '';
                if(!in_array($f['operator'], array('null', 'notnull')))
                {
                    if(!is_array($f['value']))
                    {
                        $value = "'" . $f['value'] . "'";
                    }
                    else
                    {
                        $values = array();
                        foreach($f['value'] as $v)
                        {
                            $values[] = $type == 'number' ? $v : "'" . $v . "'";
                        }
                        $value = '(' . implode(',', $values) . ')';
                    }
                }

                $where[] = $field . ' ' . $operator . ' ' . $value;
            }
        }

        /* Check priv. */
        if(!$this->app->user->admin)
        {
            $tableName = $schema->primaryTable;
            switch($tableName)
            {
            case 'product':
            case 'story':
            case 'bug':
            case 'testcase':
            case 'testrun':
            case 'testresult':
                if(!$this->app->user->view->products) return array();
                $where[] = 'product.id IN (' . $this->app->user->view->products . ')';
                break;
            case 'task':
            case 'build':
            case 'execution':
                if(!$this->app->user->view->sprints) return array();
                $where[] = 'execution.id IN (' . $this->app->user->view->sprints . ')';
                break;
            case 'project':
                if(!$this->app->user->view->projects) return array();
                $where[] = 'project.id IN (' . $this->app->user->view->projects . ')';
                break;
            }
        }

        $from = ' FROM ' . $schema->tables[$schema->primaryTable] . " AS `$schema->primaryTable` ";
        $sql  = 'SELECT ' . implode(',', $fields) . $from . implode(' ', $joins);

        if(!empty($where)) $sql .= ' WHERE ' . implode(' AND ', $where);
        if($orders)        $sql .= " ORDER BY $orders ";
        if($limit)         $sql .= " LIMIT $limit ";
        return $this->dao->query($sql)->fetchAll();
    }

    /**
     * Get field options.
     *
     * @param  array  $fields
     * @param  string $field
     * @access public
     * @return array
     */
    public function getFieldOptionsFromFields($fields, $field)
    {
        if(strpos($field, '.') !== false)
        {
            $fs = explode('.', $field);
            return $fields[$fs[0]]['children'][$fs[1]]['options'];
        }

        return $fields[$field]['options'];
    }

    /**
     * Get field name.
     *
     * @param  array  $fields
     * @param  string $field
     * @access public
     * @return string
     */
    public function getFieldName($fields, $field)
    {
        if(strpos($field, '.') !== false)
        {
            $fs = explode('.', $field);
            $title = $fields[$fs[0]]['name'] . '.' . $fields[$fs[0]]['children'][$fs[1]]['name'];
        }
        else
        {
            $title = $fields[$field]['name'];
        }

        return $title;
    }

    /**
     * Get field type.
     *
     * @param  array  $fields
     * @param  string $field
     * @access public
     * @return string
     */
    public function getFieldType($fields, $field)
    {
        if(strpos($field, '.') !== false)
        {
            $fs = explode('.', $field);
            $type = $fields[$fs[0]]['children'][$fs[1]]['type'];
        }
        else
        {
            $type = $fields[$field]['type'];
        }

        return $type;
    }

    /**
     * Gen table data.
     *
     * @param  string $schema
     * @param  object $settings
     * @param  array  $rows
     * @param  array  $users
     * @access public
     * @return array
     */
    public function genTable($schema, $settings, $rows, $users)
    {
        if(!$settings or empty($settings['column'])) return array('columns' => array(), 'source' => array(), 'rowspan' => array());

        $info   = $this->getTableInfo($schema);;
        $fields = $this->getFields($info);

        $this->genTableData($fields, $settings, $rows, $users);
    }

    /**
     * Gen table data.
     *
     * @param array $fields.
     * @param array $settings.
     * @param array $rows.
     * @param array $users.
     * @access public
     * @return array
     */
    public function genTableData($fields, $settings, $rows, $users)
    {
        /* Columns. */
        $columns = array();
        foreach($this->config->chart->settings['table'] as $key => $info)
        {
            if(!isset($settings[$key])) continue;
            foreach($settings[$key] as $setting)
            {
                $title = $setting['name'] ? $setting['name'] : $this->getFieldName($fields, $setting['field']);
                if(isset($setting['valOrAgg']) and $setting['valOrAgg'] != 'value')
                {
                    $dataIndex = $setting['field'] . ':' . $setting['valOrAgg'];
                }
                else
                {
                    $dataIndex = $setting['field'];
                }

                if(isset($setting['split']) and $setting['split'])
                {
                    foreach($this->getFieldOptionsFromFields($fields, $setting['field']) as $fkey => $value)
                    {
                        $columns[] = array('title' => $title . '(' . $value . ')', 'dataIndex' => $dataIndex . ':' . $fkey, 'type' => $this->getFieldType($fields, $setting['field']));
                    }
                }
                else
                {
                    $columns[] = array('title' => $title, 'dataIndex' => $dataIndex, 'type' => $this->getFieldType($fields, $setting['field']));
                }
            }
        }

        $userColumns = array();
        foreach($columns as $index => $col)
        {
            if($col['type'] == 'user') $userColumns[] = $col['dataIndex'];
        }

        $rowspan   = array();
        $groups    = isset($settings['group']) ? $settings['group'] : array();
        $spanIndex = array();
        foreach($groups as $group)
        {
            $rowspan[$group['field']] = array();
            $spanIndex[$group['field']] = 0;
        }

        /* Data. */
        $data    = array();
        $rowspan = array();
        if(isset($settings['group']))
        {
            $cols = array('group' => array(), 'agg' => array(), 'value' => array(), 'option' => array());
            foreach($settings['group'] as $setting)
            {
                $cols['group'][] = $setting['field'];

                $fs = explode('.', $setting['field']);
                if(strpos($setting['field'], '.') !== false)
                {
                    if($fields[$fs[0]]['children'][$fs[1]]['type'] == 'option')
                    {
                        $cols['option'][$setting['field']] = $fields[$fs[0]]['children'][$fs[1]]['options'];
                    }
                }
                else if($fields[$setting['field']]['type'] == 'option')
                {
                    $cols['option'][$setting['field']] = $fields[$setting['field']]['options'];
                }
            }

            foreach($settings['column'] as $setting)
            {
                $key = $setting['field'];
                if(isset($setting['valOrAgg']) and $setting['valOrAgg'] != 'value')
                {
                    $key .= ':' . $setting['valOrAgg'];
                    $cols['agg'][$key] = $setting['valOrAgg'];
                }
                else{
                    $cols['value'][] = $key;
                }

                if(strpos($setting['field'], '.') !== false)
                {
                    $fs = explode('.', $setting['field']);
                    if($fields[$fs[0]]['children'][$fs[1]]['type'] == 'option') $cols['option'][$setting['field']] = $fields[$fs[0]]['children'][$fs[1]]['options'];
                }
                else if($fields[$setting['field']]['type'] == 'option')
                {
                    $cols['option'][$setting['field']] = $fields[$setting['field']]['options'];
                }
            }

            $groupData = array();
            foreach($rows as $rowIndex => $row)
            {
                $rowFields = array();
                foreach($settings['group'] as $setting)
                {
                    $field = str_replace('.', '_', $setting['field']);
                    if(isset($setting['dateGroup']))
                    {
                        $rowFields[] = $row->$field > '1970-01-01' ? $this->getDateBegin($row->$field, $setting['dateGroup']) : '';
                    }
                    else
                    {
                        $rowFields[] = $row->$field;
                    }
                }

                $key = implode('*', $rowFields);
                if(!isset($groupData[$key]))
                {
                    $groupData[$key] = array();
                    foreach($settings['column'] as $setting)
                    {
                        if(isset($setting['split']) and $setting['split'])
                        {
                            foreach($this->getFieldOptionsFromFields($fields, $setting['field']) as $fkey => $value)
                            {
                                if(isset($setting['valOrAgg']) and $setting['valOrAgg'] != 'value')
                                {
                                    $field = $setting['field'];
                                    $groupData[$key][$field . ':' . $setting['valOrAgg'] . ':' . $fkey] = array('count' => 0, 'sum' => 0, 'distinct' => array(), 'max' => '');
                                }
                                else
                                {
                                    $groupData[$key][$setting['field']] = array();
                                }
                            }
                        }
                        else
                        {
                            if(isset($setting['valOrAgg']) and $setting['valOrAgg'] != 'value')
                            {
                                $field     = $setting['field'];
                                $fieldName = str_replace('.', '_', $field);
                                $groupData[$key][$field . ':' . $setting['valOrAgg']] = array('count' => 0, 'sum' => 0, 'value' => array(), 'distinct' => array(), 'max' => $row->$fieldName);
                            }
                            else
                            {
                                $groupData[$key][$setting['field']] = array();
                            }
                        }
                    }
                }

                foreach($settings['column'] as $setting)
                {
                    $field = str_replace('.', '_', $setting['field']);
                    $format = (isset($setting['format']) and $setting['format']) ? $setting['format'] : '';
                    if(isset($setting['valOrAgg']) and $setting['valOrAgg'] != 'value')
                    {
                        $fieldKey  = $setting['field'];
                        $fieldName = $fieldKey . ':' . $setting['valOrAgg'];
                        if(isset($setting['split'])  and $setting['split']) $fieldName .= ':' . $row->$fieldKey;

                        if($row->$field === null) continue; // Ignore null value.
                        $fieldValue = $format == 'pastDays' ? round((time() - strtotime($row->$field))/3600/24) : $row->$field;
                        switch($setting['valOrAgg'])
                        {
                        case 'avg':
                            $groupData[$key][$fieldName]['count']++;
                            $groupData[$key][$fieldName]['sum'] += $fieldValue;
                            break;
                        case 'count':
                            $groupData[$key][$fieldName]['count']++;
                            break;
                        case 'count_distinct':
                        case 'value_distinct':
                            $groupData[$key][$fieldName]['distinct'][$fieldValue] = true;
                            break;
                        case 'value_all':
                            $groupData[$key][$fieldName]['value'][$fieldValue] = true;
                            break;
                        case 'max':
                            if($groupData[$key][$fieldName]['max'] < $fieldValue) $groupData[$key][$setting['field']]['max'] = $fieldValue;
                            break;
                        case 'min':
                            if($groupData[$key][$fieldName]['min'] > $fieldValue) $groupData[$key][$setting['field']]['max'] = $fieldValue;
                            break;
                        case 'sum':
                            $groupData[$key][$fieldName]['sum'] += $fieldValue;
                            break;
                        }
                    }
                    else
                    {
                        $fieldValue = $format == 'pastDays' ? round((time() - strtotime($row->$field))/3600/24) : $row->$field;
                        $groupData[$key][$setting['field']][] = $fieldValue;
                    }
                }
            }

            $preIndex = array();
            foreach($cols['group'] as $fieldName)
            {
                $rowspan[$fieldName]  = array(0 => 0);
                $preIndex[$fieldName] = 0;
            }
            foreach($cols['agg'] as $fieldName => $type)
            {
                $rowspan[$fieldName]  = array(0 => 0);
                $preIndex[$fieldName] = 0;
            }

            $rowIndex = 0;
            $preKeys  = array();
            foreach($cols['group'] as $g) $preKeys[] = '!--BEGIN--!';

            $changed = -1;
            foreach($groupData as $groupKey => $groupValue)
            {
                $keys = explode('*', $groupKey);
                $row  = array();
                foreach($cols['group'] as $key => $fieldName)
                {
                    $value = $keys[$key];
                    if(isset($cols['option'][$fieldName])) $value = zget($cols['option'][$fieldName], $value, $value);
                    $row[$fieldName] = $value;
                }

                foreach($cols['agg'] as $fieldName => $type)
                {
                    $fieldList = explode(':', $fieldName);
                    $fname     = $fieldList[0];
                    $split     = false;
                    foreach($settings['column'] as $setting)
                    {
                        if($setting['field'] == $fname)
                        {
                            $split = isset($setting['split']) ? $setting['split'] : false;
                            break;
                        }
                    }

                    if($split)
                    {
                        foreach($this->getFieldOptionsFromFields($fields, $fname) as $fkey => $value)
                        {
                            $fieldNameKey = $fieldName . ':' . $fkey;
                            $value = $groupValue[$fieldNameKey];
                            if($type == 'avg')
                            {
                                $row[$fieldNameKey] = $value['sum'] / $value['count'];
                            }
                            else if($type == 'count_distinct')
                            {
                                $row[$fieldNameKey] = count($value['distinct']);
                            }
                            else if($type == 'value_distinct')
                            {
                                $row[$fieldNameKey] = implode(', ', array_keys($value['distinct']));
                            }
                            else if($type == 'value_all')
                            {
                                $row[$fieldNameKey] = implode(', ', array_keys($value['value']));
                            }
                            else
                            {
                                $row[$fieldNameKey] = $value[$type];
                            }
                        }
                    }
                    else
                    {
                        $value = $groupValue[$fieldName];
                        if($type == 'avg')
                        {
                            $row[$fieldName] = $value['sum'] / $value['count'];
                        }
                        else if($type == 'count_distinct')
                        {
                            $row[$fieldName] = count($value['distinct']);
                        }
                        else if($type == 'value_distinct')
                        {
                            $row[$fieldName] = implode(', ', array_keys($value['distinct']));
                        }
                        else if($type == 'value_all')
                        {
                            $row[$fieldName] = implode(', ', array_keys($value['value']));
                        }
                        else
                        {
                            $row[$fieldName] = $value[$type];
                        }
                    }
                }

                $count = !empty($cols['value']) ? count($groupValue[$cols['value'][0]]) : 1;

                foreach($cols['group'] as $index => $key)
                {
                    if($keys[$index] != $preKeys[$index])
                    {
                        $changed = $index;
                        break;
                    }
                }
                foreach($cols['group'] as $index => $key)
                {
                    if($changed <= $index)
                    {
                        $rowspan[$key][$rowIndex] = $count;
                        $preIndex[$key] = $rowIndex;
                    }
                    else
                    {
                        $rowspan[$key][$preIndex[$key]] += $count;
                    }
                }

                foreach($cols['agg'] as $key => $type)
                {
                    $rowspan[$key][$rowIndex] = $count;
                }

                if(!empty($cols['value']))
                {
                    for($i = 0; $i < $count; $i++)
                    {
                        foreach($cols['value'] as $fieldName)
                        {
                            $value = $groupValue[$fieldName][$i];
                            if(isset($cols['option'][$fieldName])) $value = zget($cols['option'][$fieldName], $value, $value);
                            $row[$fieldName] = $value;
                        }
                        $data[] = $row;
                    }
                }
                else
                {
                    $data[] = $row;
                }

                $rowIndex += $count;
                $preKeys = $keys;
            }
        }
        else
        {
            foreach($rows as $rowIndex => $row)
            {
                $rowData = array();
                foreach($settings['column'] as $setting)
                {
                    $field = str_replace('.', '_', $setting['field']);

                    $format = (isset($setting['format']) and $setting['format']) ? $setting['format'] : '';
                    $fieldValue = $format == 'pastDays' ? round((time() - strtotime($row->$field))/3600/24) : $row->$field;
                    if(strpos($setting['field'], '.') !== false)
                    {
                        $fs = explode('.', $setting['field']);
                        if($fields[$fs[0]]['children'][$fs[1]]['type'] == 'option')
                        {
                            $value = zget($fields[$fs[0]]['children'][$fs[1]]['options'], $fieldValue, '');
                        }
                        else
                        {
                            $value = $fieldValue;
                        }
                    }
                    else
                    {
                        if($fields[$setting['field']]['type'] == 'option')
                        {
                            $value = zget($fields[$setting['field']]['options'], $fieldValue, '');
                        }
                        else
                        {
                            $value = $fieldValue;
                        }
                    }
                    $rowData[$setting['field']] = $value;
                }
                $data[] = $rowData;
            }
        }

        foreach($rowspan as $key => $span)
        {
            $rowspan[$key] = (Object)$span;
        }

        if(!empty($userColumns))
        {
            foreach($data as $i => $row)
            {
                foreach($userColumns as $index)
                {
                    $data[$i][$index] = zget($users, $data[$i][$index], $data[$i][$index]);
                }
            }
        }

        return array('columns' => $columns, 'source' => $data, 'rowspan' => empty($rowspan) ? (Object)$rowspan : $rowspan);
    }

    /**
     * Get date begin
     *
     * @param  string $date
     * @param  string $type
     * @access private
     * @return string
     */
    private function getDateBegin($date, $type)
    {
        switch($type)
        {
            case 'day':
                return date('Y-m-d', strtotime($date));
            case 'week':
                return date('Y-m-d', strtotime("$date Monday"));
            case 'month':
                return date('Y-m', strtotime($date));
            case 'year':
                return date('Y', strtotime($date));
        }
    }

    /**
     * Gen line.
     *
     * @param  string $schema
     * @param  object $settings
     * @param  array  $rows
     * @param  array  $users
     * @access public
     * @return array
     */
    public function genLine($schema, $settings, $rows, $users)
    {
        if(!$settings or empty($settings['xaxis']) or empty($settings['yaxis'])) return array('xAxis' => array(), 'yAxis' => array());

        $info   = $this->getTableInfo($schema);;
        $fields = $this->getFields($info);

        return $this->genLineData($fields, $settings, $rows, $users);

    }

    /**
     * Gen line data.
     *
     * @param array $fields.
     * @param array $settings.
     * @param array $rows.
     * @param array $users.
     * @access public
     * @return array
     */
    public function genLineData($fields, $settings, $rows, $users)
    {
        $xaxis = $settings['xaxis'][0];
        $xkey  = $xaxis['field'];
        $yaxis = $settings['yaxis'];
        $type  = $xaxis['group'];

        $xs = array();
        $ys = array();

        if(!empty($rows))
        {
            $xkey2  = str_replace('.', '_', $xkey);
            $begin = $rows[0];
            $end   = end($rows);
            if(!$begin->$xkey2 or substr($begin->$xkey2, 0, 4) === '0000')
            {
                foreach($rows as $row)
                {
                    if($row->$xkey2 and substr($row->$xkey2, 0, 4) !== '0000')
                    {
                        $begin = $row;
                        break;
                    }
                }
            }

            if(!$end->$xkey2 or substr($end->$xkey2, 0, 4) === '0000')
            {
                foreach(array_reverse($rows) as $row)
                {
                    if($row->$xkey2 and substr($row->$xkey2, 0, 4) !== '0000')
                    {
                        $end = $row;
                        break;
                    }
                }
            }

            $date = $this->getDateBegin($begin->$xkey2, 'day');
            $endDate = $this->getDateBegin($end->$xkey2, 'day');

            while($date <= $endDate)
            {
                $xs[] = $this->getDateBegin($date, $type);
                $date = $this->getDateBegin($date . " +1$type", 'day');
            }

            $aggs   = array();
            $titles = array();
            foreach($yaxis as $y)
            {
                $aggs[$y['field']]   = $y['valOrAgg'];
                $titles[$y['field']] = $y['name'];
                $ys[$y['field']]     = array();
            }
        }

        foreach($rows as $row)
        {
            $date = $this->getDateBegin($row->$xkey2, $type);
            foreach($aggs as $key => $agg)
            {
                $key2 = str_replace('.', '_', $key);
                $value = $row->$key2;

                if(!isset($ys[$key][$date])) $ys[$key][$date] = array('value' => 0, 'sum' => 0, 'count' => 0, 'max' => $value, 'min' => $value, 'distinct' => array());
                switch($agg)
                {
                    case 'value':
                        $ys[$key][$date]['value'] = $value;
                        break;
                    case 'avg':
                        $ys[$key][$date]['sum'] += $value;
                        $ys[$key][$date]['count']++;
                        break;
                    case 'count':
                        $ys[$key][$date]['count']++;
                        break;
                    case 'count_distinct':
                        $ys[$key][$date]['distinct'][$value] = true;
                        break;
                    case 'max':
                        if($value > $ys[$key][$date]['max']) $ys[$key][$date]['max'] = $value;
                        break;
                    case 'min':
                        if($value < $ys[$key][$date]['min']) $ys[$key][$date]['min'] = $value;
                        break;
                    case 'sum':
                        $ys[$key][$date]['sum'] += $value;
                        break;
                }
            }
        }

        $series = array();
        foreach($ys as $key => $values)
        {
            $agg  = $aggs[$key];
            $y    = $ys[$key];
            $data = array();
            foreach($xs as $x)
            {
                if(!isset($y[$x]))
                {
                    $data[] = 0;
                    continue;
                }

                switch($agg)
                {
                    case 'avg':
                        $data[] = $y[$x]['sum'] / $y[$x]['count'];
                        break;
                    case 'count':
                        $data[] = $y[$x]['count'];
                        break;
                    case 'count_distinct':
                        $data[] = count($y[$x]['distinct']);
                        break;
                    default:
                        $data[] = $y[$x][$agg];
                        break;
                }
            }
            $title = $titles[$key] ? $titles[$key] : $this->getFieldName($fields, $key);
            $series[] = array('data' => $data, 'type' => 'line', 'name' => $title);
        }

        $options = array('xAxis' => array('type' => 'category', 'data' => $xs), 'yAxis' => array('type' => 'value'), 'series' => $series, 'tooltip' => array('trigger' => 'axis'));
        return $options;
    }

    /**
     * Gen bar.
     *
     * @param  string $schema
     * @param  object $settings
     * @param  array  $rows
     * @param  array  $users
     * @access public
     * @return array
     */
    public function genBar($schema, $settings, $rows, $users)
    {
        if(!$settings or empty($settings['xaxis']) or empty($settings['yaxis'])) return array('xAxis' => array(), 'yAxis' => array());

        $info   = $this->getTableInfo($schema);;
        $fields = $this->getFields($info);

        return $this->genBarData($fields, $settings, $rows, $users);
    }

    /**
     * Gen bar data.
     *
     * @param array $fields.
     * @param array $settings.
     * @param array $rows.
     * @param array $users.
     * @access public
     * @return array
     */
    public function genBarData($fields, $settings, $rows, $users)
    {
        $xaxis = $settings['xaxis'][0];
        $xkey  = $xaxis['field'];
        $yaxis = $settings['yaxis'];

        $aggs   = array();
        $ys     = array();
        $titles = array();
        foreach($yaxis as $y)
        {
            $aggs[$y['field']]   = $y['valOrAgg'];
            $ys[$y['field']]     = array();
            $titles[$y['field']] = $y['name'];
        }

        $xs = array();
        foreach($rows as $row)
        {
            $xkey2  = str_replace('.', '_', $xkey);
            if(isset($xaxis['dateGroup']))
            {
                $x = $row->$xkey2 > '1970-01-01' ? $this->getDateBegin($row->$xkey2, $xaxis['dateGroup']) : '';
            }
            else
            {
                $x = $row->$xkey2;
            }
            if(!in_array($x, $xs)) $xs[] = $x;
            foreach($aggs as $key => $agg)
            {
                $key2 = str_replace('.', '_', $key);
                $value = $row->$key2;
                if(!isset($ys[$key][$x])) $ys[$key][$x] = array('value' => 0, 'sum' => 0, 'count' => 0, 'max' => $value, 'min' => $value, 'distinct' => array());
                switch($agg)
                {
                    case 'value':
                        $ys[$key][$x]['value'] = $value;
                        break;
                    case 'avg':
                        $ys[$key][$x]['sum'] += $value;
                        $ys[$key][$x]['count']++;
                        break;
                    case 'count':
                        $ys[$key][$x]['count']++;
                        break;
                    case 'count_distinct':
                        $ys[$key][$x]['distinct'][$value] = true;
                        break;
                    case 'max':
                        if($value > $ys[$key][$x]['max']) $ys[$key][$x]['max'] = $value;
                        break;
                    case 'min':
                        if($value < $ys[$key][$x]['min']) $ys[$key][$x]['min'] = $value;
                        break;
                    case 'sum':
                        $ys[$key][$x]['sum'] += $value;
                        break;
                }
            }
        }

        $series = array();
        foreach($ys as $key => $values)
        {
            $agg  = $aggs[$key];
            $y    = $ys[$key];
            $data = array();
            foreach($xs as $x)
            {
                if(!isset($y[$x]))
                {
                    $data[] = 0;
                    continue;
                }

                switch($agg)
                {
                    case 'avg':
                        $data[] = $y[$x]['sum'] / $y[$x]['count'];
                        break;
                    case 'count':
                        $data[] = $y[$x]['count'];
                        break;
                    case 'count_distinct':
                        $data[] = count($y[$x]['distinct']);
                        break;
                    default:
                        $data[] = $y[$x][$agg];
                        break;
                }
            }
            $title = $titles[$key] ? $titles[$key] : $this->getFieldName($fields, $key);
            $series[] = array('data' => $data, 'type' => 'bar', 'name' => $title);
        }

        foreach($xs as $index => $x)
        {
            $type = $this->getFieldType($fields, $xkey);
            if($type == 'option')
            {
                $title = $fields[$xkey]['options'][$x];
            }
            else if($type == 'user')
            {
                $title = zget($users, $x, $x);
            }
            else
            {
                $title = $x;
            }

            $xs[$index] = $title;
        }
        $options = array('xAxis' => array('type' => 'category', 'data' => $xs, 'axisLabel' => array('interval' => 0)), 'yAxis' => array('type' => 'value'), 'series' => $series, 'tooltip' => array('trigger' => 'axis'));
        return $options;
    }

    /**
     * Gen pie data.
     *
     * @param array $fields.
     * @param array $settings.
     * @param array $rows.
     * @param array $users.
     * @access public
     * @return array
     */
    public function genPieData($fields, $settings, $rows, $users)
    {
        $xaxis = $settings['group'][0];
        $xkey  = $xaxis['field'];
        $yaxis = $settings['metric'];

        $aggs   = array();
        $ys     = array();
        foreach($yaxis as $y)
        {
            $aggs[$y['field']]   = $y['valOrAgg'];
            $ys[$y['field']]     = array();
        }

        $xs = array();
        foreach($rows as $row)
        {
            if(empty($xkey)) break;
            $xkey2  = str_replace('.', '_', $xkey);
            $x = $row->$xkey2;
            if(!in_array($x, $xs)) $xs[] = $x;
            foreach($aggs as $key => $agg)
            {
                $key2 = str_replace('.', '_', $key);
                $value = $row->$key2;
                if(!isset($ys[$key][$x])) $ys[$key][$x] = array('value' => 0, 'sum' => 0, 'count' => 0, 'max' => $value, 'min' => $value, 'distinct' => array());
                switch($agg)
                {
                    case 'value':
                        $ys[$key][$x]['value'] = $value;
                        break;
                    case 'avg':
                        $ys[$key][$x]['sum'] += $value;
                        $ys[$key][$x]['count']++;
                        break;
                    case 'count':
                        $ys[$key][$x]['count']++;
                        break;
                    case 'count_distinct':
                        $ys[$key][$x]['distinct'][$value] = true;
                        break;
                    case 'max':
                        if($value > $ys[$key][$x]['max']) $ys[$key][$x]['max'] = $value;
                        break;
                    case 'min':
                        if($value < $ys[$key][$x]['min']) $ys[$key][$x]['min'] = $value;
                        break;
                    case 'sum':
                        $ys[$key][$x]['sum'] += $value;
                        break;
                }
            }
        }

        $series = array();
        foreach($ys as $key => $values)
        {
            $agg  = $aggs[$key];
            $y    = $ys[$key];
            $data = array();
            foreach($xs as $x)
            {
                if(!isset($y[$x]))
                {
                    $data[] = 0;
                    continue;
                }

                switch($agg)
                {
                    case 'avg':
                        $value = $y[$x]['sum'] / $y[$x]['count'];
                        break;
                    case 'count':
                        $value = $y[$x]['count'];
                        break;
                    case 'count_distinct':
                        $value = count($y[$x]['distinct']);
                        break;
                    default:
                        $value = $y[$x][$agg];
                        break;
                }
                $type = $this->getFieldType($fields, $xkey);
                if($type == 'option')
                {
                    $fieldOptions = $this->getFieldOptionsFromFields($fields, $xkey);
                    $title        = $fieldOptions[$x];
                }
                else if($type == 'user')
                {
                    $title = zget($users, $x, $x);
                }
                else
                {
                    $title = $x;
                }
                $data[] = array('value' => $value, 'name' => $title);
            }
            $series[] = array('data' => $data, 'type' => 'pie');
        }

        $options = array('series' => $series, 'tooltip' => array('trigger' => 'item', 'formatter' => "{b}<br/> {c} ({d}%)"));
        return $options;
    }

    /**
     * Gen testing report.
     *
     * @param  string $type
     * @param  array  $filterValues
     * @access public
     * @return array
     */
    public function genTestingReport($type, $filterValues)
    {
        if(isset($filterValues['casemodule.id']))
        {
            $moduleIdList = $filterValues['casemodule.id'];
        }
        else if(isset($filterValues['casemodule']))
        {
            $moduleIdList = $filterValues['casemodule'];
        }
        else
        {
            $moduleIdList = array();
        }
        if(!is_array($moduleIdList)) $moduleIdList = array($moduleIdList);

        switch($type)
        {
        case 'testingReport':
        case 'buildTestingReport':
            if(isset($filterValues['build.id']))
            {
                $buildIdList = $filterValues['build.id'];
            }
            else if(isset($filterValues['build']))
            {
                $buildIdList = $filterValues['build'];
            }
            else
            {
                $buildIdList = array(0);
            }

            $builds = $this->dao->select('t1.*, t2.name executionName, t3.name projectName')
                ->from(TABLE_BUILD)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')
                ->on('t1.execution = t2.id')
                ->leftJoin(TABLE_PROJECT)->alias('t3')
                ->on('t2.project = t3.id')
                ->where('t1.id')->in($buildIdList)
                ->fetchAll('id');
            $testtasks = $this->dao->select('id,name,begin,end')->from(TABLE_TESTTASK)->where('build')->in($buildIdList)->fetchAll('id');
            break;
        case 'executionTestingReport':
            if(isset($filterValues['execution.id']))
            {
                $executionIdList = $filterValues['execution.id'];
            }
            else if(isset($filterValues['execution']))
            {
                $executionIdList = $filterValues['execution'];
            }
            else
            {
                $executionIdList = array(0);
            }

            $executions = $this->dao->select('t1.*, t2.name projectName')
                ->from(TABLE_EXECUTION)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')
                ->on('t1.project = t2.id')
                ->where('t1.id')->in($executionIdList)
                ->fetchAll();
            $testtasks = $this->dao->select('id,name,begin,end')->from(TABLE_TESTTASK)->where('execution')->in($executionIdList)->fetchAll('id');
            break;
        case 'projectTestingReport':
            if(isset($filterValues['project.id']))
            {
                $projectIdList = $filterValues['project.id'];
            }
            else if(isset($filterValues['project']))
            {
                $projectIdList = $filterValues['project'];
            }
            else
            {
                $projectIdList = array(0);
            }
            $projects = $this->dao->select('*')
                ->from(TABLE_PROJECT)
                ->where('id')->in($projectIdList)
                ->fetchAll('', false);
            $testtasks = $this->dao->select('id,name,begin,end')->from(TABLE_TESTTASK)->where('project')->in($projectIdList)->fetchAll('id');
            break;
        case 'dailyTestingReport':
            $typeIdList = array();
            $dailyType   = 'build';
            if(isset($filterValues['build']))
            {
                $typeIdList = $filterValues['build'];
            }
            if(isset($filterValues['build.id']))
            {
                $typeIdList = $filterValues['build.id'];
            }
            else if(isset($filterValues['execution']))
            {
                $typeIdList = $filterValues['execution'];
                $dailyType = 'execution';
            }
            else if(isset($filterValues['execution.id']))
            {
                $typeIdList = $filterValues['execution.id'];
                $dailyType = 'execution';
            }
            else if(isset($filterValues['project']))
            {
                $typeIdList = $filterValues['project'];
                $dailyType = 'project';
            }
            else if(isset($filterValues['project.id']))
            {
                $typeIdList = $filterValues['project.id'];
                $dailyType = 'project';
            }
            $date = isset($filterValues['bug.openedDate']) ? $filterValues['bug.openedDate'] : helper::today();

            $testtasks = $this->dao->select('id,name,begin,end')->from(TABLE_TESTTASK)
                ->beginIF($dailyType == 'build')->where('build')->in($typeIdList)->fi()
                ->beginIF($dailyType == 'execution')->where('execution')->in($typeIdList)->fi()
                ->beginIF($dailyType == 'project')->where('project')->in($typeIdList)->fi()
                ->fetchAll('id');
            $builds = $this->dao->select('t1.*, t2.name executionName, t3.name projectName')
                ->from(TABLE_BUILD)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')
                ->on('t1.execution = t2.id')
                ->leftJoin(TABLE_PROJECT)->alias('t3')
                ->on('t2.project = t3.id')
                ->beginIF($dailyType == 'build')->where('t1.id')->in($typeIdList)->fi()
                ->beginIF($dailyType == 'execution')->where('t2.id')->in($typeIdList)->fi()
                ->beginIF($dailyType == 'project')->where('t1.project')->in($typeIdList)->fi()
                ->fetchAll('id');
            break;
        }

        $moduleCases = array();
        $users       = array();
        $days        = 0;
        $bugSummary  = array();

        $this->app->loadLang('bug');
        foreach($this->lang->bug->severityList as $severity => $label)
        {
            $bugSummary[$severity] = array('total' => 0);
            foreach($this->lang->bug->statusList as $status => $label)
            {
                if(!$status) continue;
                $bugSummary[$severity][$status] = 0;
            }
        }

        if(!empty($testtasks) and $type != 'dailyTestingReport')
        {
            $begin = '';
            $end   = '';
            foreach($testtasks as $task)
            {
                if(!$begin or ($task->begin < $begin and $task->begin)) $begin = $task->begin;
                if($task->end > $end and $task->end) $end   = $task->end;
            }

            if($begin and $end)
            {
                $begin = strtotime($begin);
                $end   = strtotime($end);

                $diff = $end - $begin;
                $days = abs(floor($diff / 86400)) + 1;
            }

            $cases = $this->dao->select('t2.id,t2.module')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')
                ->on('t1.case = t2.id')
                ->where('t1.task')->in(array_keys($testtasks))
                ->beginIF(!empty($moduleIdList))->andWhere('t2.module')->in($moduleIdList)->fi()
                ->fetchAll('id');
            $modules = array();
            foreach($cases as $case)
            {
                if(!isset($moduleCases[$case->module])) $moduleCases[$case->module] = array('name' => '', 'total' => 0, 'run' => array(), 'rate' => '0.00', 'users' => array(), 'bug' => 0, 'hours' => 0);
                $moduleCases[$case->module]['total']++;
                $modules[] = $case->module;
            }

            $runCases = $this->dao->select('t1.case, t1.lastRunner account,t1.date,t2.id,t3.module,t1.runSecond')->from(TABLE_TESTRESULT)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')
                ->on('t1.run = t2.id')
                ->leftJoin(TABLE_CASE)->alias('t3')
                ->on('t2.case = t3.id')
                ->where('t2.task')->in(array_keys($testtasks))
                ->beginIF(!empty($moduleIdList))->andWhere('t3.module')->in($moduleIdList)->fi()
                ->orderBy('t1.date')
                ->fetchAll();
            foreach($runCases as $case)
            {
                $moduleCases[$case->module]['run'][$case->case] = $case->case;
                $moduleCases[$case->module]['hours'] += $case->runSecond;
                $moduleCases[$case->module]['users'][$case->account] = $case->account;
                $users[$case->account] = $case->account;
            }

            $bugs = $this->dao->select('t1.id,t3.module,t1.status,t1.severity')->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')
                ->on('t1.run = t2.id')
                ->leftJoin(TABLE_CASE)->alias('t3')
                ->on('t1.case = t3.id')
                ->where('t2.task')->in(array_keys($testtasks))
                ->beginIF(!empty($moduleIdList))->andWhere('t3.module')->in($moduleIdList)->fi()
                ->fetchAll();

            foreach($bugs as $bug)
            {
                $moduleCases[$bug->module]['bug']++;

                $bugSummary[$bug->severity]['total']++;
                $bugSummary[$bug->severity][$bug->status]++;
            }

            $modulePairs = $this->loadModel('tree')->getModulesName($modules);
            foreach($moduleCases as $moduleID => $cases)
            {
                $moduleCases[$moduleID]['name']  = zget($modulePairs, $moduleID, '');
                $moduleCases[$moduleID]['users'] = count($moduleCases[$moduleID]['users']);
                $moduleCases[$moduleID]['rate']  = $moduleCases[$moduleID]['total'] ? round(count($moduleCases[$moduleID]['run'])/$moduleCases[$moduleID]['total']*100, 2): '0.00';
            }
        }
        else if(!empty($testtasks) and $type == 'dailyTestingReport')
        {
            $begin = '';
            $end   = '';
            foreach($testtasks as $task)
            {
                if(!$begin or ($task->begin < $begin and $task->begin)) $begin = $task->begin;
                if($task->end > $end and $task->end) $end   = $task->end;
            }

            if($begin and $end)
            {
                $begin = strtotime($begin);
                $end   = strtotime($end);

                $diff = $end - $begin;
                $days = abs(floor($diff / 86400)) + 1;
            }

            $cases = $this->dao->select('t2.id,t2.module')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')
                ->on('t1.case = t2.id')
                ->where('t1.task')->in(array_keys($testtasks))
                ->beginIF(!empty($moduleIdList))->andWhere('t2.module')->in($moduleIdList)->fi()
                ->fetchAll('id');
            $modules = array();
            foreach($cases as $case)
            {
                if(!isset($moduleCases[$case->module])) $moduleCases[$case->module] = array('name' => '', 'total' => 0, 'run' => array(), 'rate' => '0.00', 'users' => array(), 'bug' => 0, 'hours' => 0);
                $moduleCases[$case->module]['total']++;
                $modules[] = $case->module;
            }

            $runCases = $this->dao->select('t1.case, t1.lastRunner account,t1.date,t2.id,t3.module,t1.runSecond')
                ->from(TABLE_TESTRESULT)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')
                ->on('t1.run = t2.id')
                ->leftJoin(TABLE_CASE)->alias('t3')
                ->on('t2.case = t3.id')
                ->andWhere('t1.date')->le($date . ' 23:59:59')
                ->andWhere('t2.task')->in(array_keys($testtasks))
                ->beginIF(!empty($moduleIdList))->andWhere('t3.module')->in($moduleIdList)->fi()
                ->orderBy('t1.date')
                ->fetchAll();
            foreach($runCases as $case)
            {
                if(!isset($moduleCases[$case->module])) continue;
                $moduleCases[$case->module]['run'][$case->case] = $case->case;
                $moduleCases[$case->module]['hours'] += $case->runSecond;
                $moduleCases[$case->module]['users'][$case->account] = $case->account;
                $users[$case->account] = $case->account;
            }

            $bugs = $this->dao->select('t1.id,t3.module,t1.status,t1.severity')->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_TESTRUN)->alias('t2')
                ->on('t1.run = t2.id')
                ->leftJoin(TABLE_CASE)->alias('t3')
                ->on('t1.case = t3.id')
                ->where('t2.task')->in(array_keys($testtasks))
                ->andWhere('t1.openedDate')->le($date . ' 23:59:59')
                ->beginIF(!empty($moduleIdList))->andWhere('t3.module')->in($moduleIdList)->fi()
                ->fetchAll();

            foreach($bugs as $bug)
            {
                $moduleCases[$bug->module]['bug']++;

                $bugSummary[$bug->severity]['total']++;
                $bugSummary[$bug->severity][$bug->status]++;
            }

            $modulePairs = $this->loadModel('tree')->getModulesName($modules);
            foreach($moduleCases as $moduleID => $cases)
            {
                $moduleCases[$moduleID]['name']  = zget($modulePairs, $moduleID, '');
                $moduleCases[$moduleID]['users'] = count($moduleCases[$moduleID]['users']);
                $moduleCases[$moduleID]['rate']  = $moduleCases[$moduleID]['total'] ? round(count($moduleCases[$moduleID]['run'])/$moduleCases[$moduleID]['total']*100, 2): '0.00';
            }
        }

        $tables = array();

        switch($type)
        {
        case 'testingReport':
        case 'buildTestingReport':
            $projectNames   = array();
            $executionNames = array();
            $buildNames     = array();
            foreach($builds as $build)
            {
                $projectNames[$build->project]     = $build->projectName;
                $executionNames[$build->execution] = $build->executionName;
                $buildNames[$build->id]            = $build->name;
            }

            $table = array();
            $tr = array();
            $tr[] = array('value' => $this->lang->chart->project, 'cls' => 'bold short');
            $tr[] = array('value' => implode(', ', $projectNames));
            $tr[] = array('value' => $this->lang->chart->reportDate, 'cls' => 'bold short');
            $tr[] = array('value' => helper::today());
            $tr[] = array('value' => $this->lang->chart->period, 'cls' => 'bold');
            $tr[] = array('value' => $days . ' ' . $this->lang->chart->day);
            $table[] = $tr;

            $tr = array();
            $tr[] = array('value' => $this->lang->chart->build, 'cls' => 'bold');
            $tr[] = array('value' => implode(', ', $buildNames));
            $tr[] = array('value' => $this->lang->chart->stage, 'cls' => 'bold');
            $tr[] = array('value' => implode(', ', $executionNames));
            $tr[] = array('value' => $this->lang->chart->users, 'cls' => 'bold');
            $tr[] = array('value' => count($users));
            $table[] = $tr;

            $tables[] = $table;
            break;
        case 'executionTestingReport':
            $projectNames   = array();
            $executionNames = array();
            foreach($executions as $execution)
            {
                $projectNames[$execution->project] = $execution->projectName;
                $executionNames[$execution->id]    = $execution->name;
            }

            $table = array();
            $tr = array();
            $tr[] = array('value' => $this->lang->chart->project, 'cls' => 'bold short');
            $tr[] = array('value' => implode(',', $projectNames));
            $tr[] = array('value' => $this->lang->chart->reportDate, 'cls' => 'bold short');
            $tr[] = array('value' => helper::today());
            $tr[] = array('value' => $this->lang->chart->period, 'cls' => 'bold');
            $tr[] = array('value' => $days . ' ' . $this->lang->chart->day);
            $table[] = $tr;

            /*
            $tr[] = array('value' => $this->lang->chart->customer, 'cls' => 'bold short');
            $tr[] = array('value' => '');
             */

            $tr = array();
            $tr[] = array('value' => $this->lang->chart->stage, 'cls' => 'bold');
            $tr[] = array('value' => implode(',', $executionNames));
            $tr[] = array('value' => $this->lang->chart->users, 'cls' => 'bold');
            $tr[] = array('value' => count($users));
            $tr[] = array('value' => $this->lang->chart->testtasks, 'cls' => 'bold');
            $tr[] = array('value' => count($testtasks));
            $table[] = $tr;

            /*
            $tr = array();
            $tr[] = array('value' => $this->lang->chart->build, 'cls' => 'bold');
            $tr[] = array('value' => $build->name);
            $tr[] = array('value' => $this->lang->chart->cusBuild, 'cls' => 'bold');
            $tr[] = array('value' => '');
            $tr[] = array('value' => $this->lang->chart->purpose, 'cls' => 'bold');
            $tr[] = array('value' => '');
            $table[] = $tr;

            $tr = array();
            $tr[] = array('value' => $this->lang->chart->comment, 'cls' => 'bold');
            $tr[] = array('value' => '');
            $tr[] = array('value' => $this->lang->chart->major, 'cls' => 'bold');
            $tr[] = array('value' => '');
            $tr[] = array('value' => $this->lang->chart->conclusion, 'cls' => 'bold');
            $tr[] = array('value' => '');
            $table[] = $tr;
             */

            $tables[] = $table;
            break;
        case 'projectTestingReport':
            $projectNames   = array();
            foreach($projects as $project)
            {
                $projectNames[$project->id] = $project->name;
            }
            $table = array();
            $tr = array();
            $tr[] = array('value' => $this->lang->chart->project, 'cls' => 'bold short');
            $tr[] = array('value' => implode(',', $projectNames));
            $tr[] = array('value' => $this->lang->chart->reportDate, 'cls' => 'bold short');
            $tr[] = array('value' => helper::today());
            $tr[] = array('value' => $this->lang->chart->period, 'cls' => 'bold');
            $tr[] = array('value' => $days . ' ' . $this->lang->chart->day);
            $table[] = $tr;

            $tr = array();
            $tr[] = array('value' => $this->lang->chart->users, 'cls' => 'bold');
            $tr[] = array('value' => count($users));
            $tr[] = array('value' => $this->lang->chart->testtasks, 'cls' => 'bold');
            $tr[] = array('value' => count($testtasks));
            $tr[] = array('value' => '', 'cls' => 'bold');
            $tr[] = array('value' => '');
            $table[] = $tr;

            $tables[] = $table;
            break;
        case 'dailyTestingReport':
            $projectNames   = array();
            $executionNames = array();
            $buildNames     = array();
            foreach($builds as $build)
            {
                $projectNames[$build->project]     = $build->projectName;
                $executionNames[$build->execution] = $build->executionName;
                $buildNames[$build->id]            = $build->name;
            }

            $table = array();
            $tr = array();
            $tr[] = array('value' => $this->lang->chart->project, 'cls' => 'bold short');
            $tr[] = array('value' => implode(', ', $projectNames));
            $tr[] = array('value' => $this->lang->chart->reportDate, 'cls' => 'bold short');
            $tr[] = array('value' => helper::today());
            $tr[] = array('value' => $this->lang->chart->period, 'cls' => 'bold');
            $tr[] = array('value' => $days . ' ' . $this->lang->chart->day);
            $table[] = $tr;

            $tr = array();
            $tr[] = array('value' => $this->lang->chart->build, 'cls' => 'bold');
            $tr[] = array('value' => implode(', ', $buildNames));
            $tr[] = array('value' => $this->lang->chart->stage, 'cls' => 'bold');
            $tr[] = array('value' => implode(', ', $executionNames));
            $tr[] = array('value' => $this->lang->chart->users, 'cls' => 'bold');
            $tr[] = array('value' => count($users));
            $table[] = $tr;

            $tables[] = $table;
            break;
        }


        $table = array();
        $tr = array();
        $tr[] = array('value' => $this->lang->chart->result, 'cls' => 'bold');
        $tr[] = array('value' => $this->lang->chart->caseCount, 'cls' => 'bold');
        $tr[] = array('value' => $this->lang->chart->runCount, 'cls' => 'bold');
        $tr[] = array('value' => $this->lang->chart->runRate, 'cls' => 'bold');
        $tr[] = array('value' => $this->lang->chart->manpower, 'cls' => 'bold');
        $tr[] = array('value' => $this->lang->chart->bugs, 'cls' => 'bold');
        $tr[] = array('value' => $this->lang->chart->hours, 'cls' => 'bold');
        $table[] = $tr;

        foreach($moduleCases as $row)
        {
            $tr = array();
            $tr[] = array('value' => $row['name']);
            $tr[] = array('value' => $row['total']);
            $tr[] = array('value' => count($row['run']));
            $tr[] = array('value' => $row['rate'] . '%');
            $tr[] = array('value' => $row['users']);
            $tr[] = array('value' => $row['bug']);
            $tr[] = array('value' => round($row['hours']/3600));
            $table[] = $tr;
        }

        $tables[] = $table;

        $table = array();

        $tr = array();
        $tr[] = array('value' => 'Issue Level', 'cls' => 'bold');
        $tr[] = array('value' => $this->lang->chart->count, 'cls' => 'bold');
        foreach($this->lang->bug->statusList as $status => $label)
        {
            if(!$status) continue;
            $tr[] = array('value' => $label, 'cls' => 'bold');
        }
        $tr[] = array('value' => 'DI', 'cls' => 'bold');
        $table[] = $tr;

        $di = $bugSummary[1]['total'] * 10 + $bugSummary[2]['total'] * 3 + $bugSummary[3]['total'] + $bugSummary[4]['total'] * 0.1;
        foreach($bugSummary as $severity => $row)
        {
            $tr = array();
            $tr[] = array('value' => $this->lang->bug->severityList[$severity]);
            $tr[] = array('value' => $row['total']);
            foreach($this->lang->bug->statusList as $status => $label)
            {
                if(!$status) continue;
                $tr[] = array('value' => zget($row, $status, 0));
            }
            if($severity == 1) $tr[] = array('value' => $di, 'rowspan' => 4);
            $table[] = $tr;
        }

        $tables[] = $table;

        $info = array('title' => '', 'desc' => '');
        $values = array();
        foreach($filterValues as $key => $value)
        {
            if(strpos($key, '.') === false) $key = $key . '.id';
            $values[$key] = $value;
        }

        $info = array('title' => '', 'desc' => '');
        return array('tables' => $tables, 'info' => $info);
    }

    /**
     * Set filters.
     *
     * @param  string $filters
     * @access public
     * @return array
     */
    public function setFilters($filters)
    {
        $result  = array();
        $filters = $filters ? json_decode($filters) : array();
        foreach($filters as $filter)
        {
            if($filter->field == 'build.id')
            {
                $result[] = (Object)array('type' => 'select', 'multiple' => false, 'field' => 'project.id');
                $result[] = (Object)array('type' => 'select', 'multiple' => false, 'field' => 'execution.id');
            }
            else if($filter->field == 'execution.id')
            {
                $result[] = (Object)array('type' => 'select', 'multiple' => false, 'field' => 'project.id');
            }
            $result[] = $filter;
        }
        return $result;
    }

    /**
     * Get datasets.
     *
     * @access public
     * @return array
     */
    public function getDatasets()
    {
        $this->loadModel('dataview');
        $this->loadModel('dev');

        $datasets = arrayUnion(array('' => ''), $this->dao->select('view,name')->from(TABLE_DATAVIEW)->where('deleted')->eq(0)->fetchPairs());

        if(isset($this->lang->dev->tableList)) $datasets = arrayUnion($datasets, $this->lang->dev->tableList);

        return $datasets;
    }

    public function getSQLFieldOptions($sql, $fieldSetting = array())
    {
        $options  = array();

        if($fieldSetting)
        {
            $type    = $fieldSetting['type'];
            $object  = $fieldSetting['object'];
            $field   = $fieldSetting['field'];

            $options = $this->getSysOptions($type, $object, $field, $sql);
        }

        return arrayUnion(array('' => ''), $options);
    }

    public function getDenominatorTip($goalName, $calcName)
    {
        if(strpos($this->app->getClientLang(), 'zh') !== false)
        {
            return sprintf($this->lang->chart->denominatorTip['full'], $goalName, $calcName);
        }
        else
        {
            return sprintf($this->lang->chart->denominatorTip['full'], $calcName, $goalName);
        }
    }
}
