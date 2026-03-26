<?php
/**
 * The model file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class dashboardModel extends model
{
    /**
     * Get dashboard.
     *
     * @param  int    $dashboardID
     * @param  bool   $includeData
     * @access public
     * @return object
     */
    public function getByID($dashboardID, $includeData = false)
    {
        $dashboard = $this->dao->select('*')->from(TABLE_DASHBOARD)->where('id')->eq($dashboardID)->fetch();
        $dashboard->layout  = $dashboard->layout  ? json_decode($dashboard->layout)  : array();
        $dashboard->filters = $dashboard->filters ? json_decode($dashboard->filters) : array();

        $dashboard->datasets = array();
        $charts = array();
        foreach($dashboard->layout as $l) $charts[] = $l->i->id;

        $charts = $this->dao->select('*')->from(TABLE_CHART)->where('id')->in($charts)->fetchAll('id');
        foreach($dashboard->layout as $index => $l)
        {
            $chart = $charts[$l->i->id];
            $dashboard->layout[$index]->i = $chart;
            $dashboard->filters = $this->setFilters($dashboard->filters, $chart->filters);

            if(strpos($chart->type, 'Report') !== false or (strpos($chart->dataset, 'custom_') !== false))
            {
                foreach($dashboard->filters as $filter)
                {
                    $objectFields = explode('.', $filter->field);
                    $dashboard->datasets[$objectFields[0]] = $objectFields[0];
                }
            }
            else
            {
                $dashboard->datasets[$chart->dataset] = $chart->dataset;
            }
        }

        $filters = $this->loadModel('dataset')->getFilters($dashboard->datasets);

        /* Options. */
        $sysOptions = array();
        foreach($dashboard->filters as $filter)
        {
            if(isset($filters['option'][$filter->field]))
            {
                $type = $filters['option'][$filter->field]['type'];
                if($type != 'option') $sysOptions[$type] = array();
            }
        }
        list($sysOptions, $defaults) = $this->dataset->getSysOptions($sysOptions);

        $users = $this->loadModel('user')->getPairs('noletter');
        if($includeData and !empty($dashboard->layout)) $dashboard->layout = $this->getLayoutData($dashboard->layout, $defaults, $users, $defaults);

        return $dashboard;
    }

    /**
     * Set filters.
     *
     * @param  array  $dashboardFilters
     * @param  string $chartFilters
     * @access public
     * @return array
     */
    public function setFilters($dashboardFilters, $chartFilters)
    {
        $filterMap  = array();
        $newFilters = array();
        foreach($dashboardFilters as $filter)
        {
            if($filter->field == 'build.id' or $filter->field == 'execution.id')
            {
                if(!isset($filterMap['project.id']))
                {
                    $filterMap['project.id'] = true;
                    $newFilters[] = (Object)array('type' => 'select', 'multiple' => true, 'field' => 'project.id');
                }
                if($filter->field == 'build.id' and !isset($filterMap['execution.id']))
                {
                    $filterMap['execution.id'] = true;
                    $newFilters[] = (Object)array('type' => 'select', 'multiple' => true, 'field' => 'execution.id');
                }
            }
            if(!isset($filterMap[$filter->field]))
            {
                $filterMap[$filter->field] = true;
                $newFilters[] = $filter;
            }
        }

        $filters = $chartFilters ? json_decode($chartFilters) : array();
        foreach($filters as $filter)
        {
            if($filter->field == 'build.id')
            {
                if(!isset($filterMap['project.id']))   $newFilters[] = (Object)array('type' => 'select', 'multiple' => true, 'field' => 'project.id');
                if(!isset($filterMap['execution.id'])) $newFilters[] = (Object)array('type' => 'select', 'multiple' => true, 'field' => 'execution.id');
            }
            else if($filter->field == 'execution.id')
            {
                if(!isset($filterMap['project.id']))   $newFilters[] = (Object)array('type' => 'select', 'multiple' => true, 'field' => 'project.id');
            }
            if(!isset($filterMap[$filter->field])) $newFilters[] = $filter;
        }
        return $newFilters;
    }

    /**
     * Merge filter.
     *
     * @param  object $table
     * @param  array  $chartFilter
     * @param  array  $dashFilter
     * @access private
     * @return array
     */
    private function mergeFilter($table, $chartFilter, $dashFilter)
    {
        if(!isset($table->schema))
        {
            return array();
        }

        $primary = $table->schema->primaryTable;

        foreach($dashFilter as $key => $value)
        {
            if(strpos($key, '.') === false) $key .= '.id';

            list($t, $f) = explode('.', $key);
            if($t !== $primary and !isset($table->schema->objects[$t])) continue;

            $field = '';
            if($t == $primary)
            {
                if(!isset($table->schema->fields[$f])) continue;
                $type  = $table->schema->fields[$f]['type'];
                $field = $f;
            }
            else
            {
                if(!isset($table->schema->objects[$t][$f])) continue;
                $type  = $table->schema->objects[$t][$f]['type'];
                $field = $key;
            }

            if(is_array($value))
            {
                if(in_array($type, array('date', 'datetime')))
                {
                    $chartFilter[] = array('field' => $field, 'operator' => '>=', 'value' => $value[0]);
                    $chartFilter[] = array('field' => $field, 'operator' => '<=', 'value' => $value[1]);
                }
                else
                {
                    $chartFilter[] = array('field' => $field, 'operator' => 'in', 'value' => $value);
                }
            }
            else
            {
                $chartFilter[] = array('field' => $t == $primary ? $f : $key, 'operator' => '=', 'value' => $value);
            }
        }

        return $chartFilter;
    }

    /**
     * Get layout data.
     *
     * @param  array $layout
     * @param  array $filters
     * @param  array $users
     * @param  array $defaults
     * @access public
     * @return array
     */
    public function getLayoutData($layout, $filters = array(), $users = array(), $defaults = array())
    {
        $this->loadModel('chart');
        $this->loadModel('dataset');

        foreach($layout as $index => $l)
        {
            $chart = $l->i;
            if(strpos($chart->type, 'Report') === false)
            {
                $table    = $this->dataset->getTableInfo($chart->dataset);
                $fields   = $this->chart->getFields($table);
                $settings = json_decode($chart->settings, true);
                /* Remove deleted fields.*/
                foreach($settings as $type => $setting)
                {
                    foreach($setting as $key => $settingField)
                    {
                        if(!isset($fields[$settingField['field']])) unset($settings[$type][$key]);
                    }
                }

                $filter   = isset($settings['filter']) ? $settings['filter'] : array();
                $group    = isset($settings['group'])  ? $settings['group']  : array();
                $filter   = $this->mergeFilter($table, $filter, $filters ? $filters : array());

                /* Line data must be ordered by time. */
                if($chart->type == 'line')
                {
                    $order = array(array('value' => $settings['xaxis'][0]['field'], 'sort' => 'asc'));
                }
                else
                {
                    $order = isset($settings['order']) ? $settings['order'] : array();
                }
                $rows = $this->chart->getData($table->schema, $filter, $defaults, $group, $order);
            }

            switch($chart->type)
            {
                case 'table':
                    $data = $this->chart->genTable($chart->dataset, $settings, $rows, $users);
                    break;
                case 'line':
                    $data = $this->chart->genLine($chart->dataset, $settings, $rows, $users);
                    break;
                case 'bar':
                    $data = $this->chart->genBar($chart->dataset, $settings, $rows, $users);
                    break;
                case 'pie':
                    $data = $this->chart->genPie($chart->dataset, $settings, $rows, $users);
                    break;
                case 'testingReport':
                case 'buildTestingReport':
                case 'executionTestingReport':
                case 'projectTestingReport':
                case 'dailyTestingReport':
                    $data = $this->chart->genTestingReport($chart->type, $filters);
                    break;
            }

            $l->i->data = $data;
            $layout[$index] = $l;
        }

        return $layout;
    }

    /**
     * Get dashboards.
     *
     * @param  int    $dimensionID
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($dimensionID = 0, $moduleID = 0, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_DASHBOARD)
            ->where('deleted')->eq(0)
            ->beginIF($dimensionID)->andWhere('dimension')->eq($dimensionID)->fi()
            ->beginIF($moduleID)->andWhere('module')->eq($moduleID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Create dashboard.
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
            ->add('dimension', $dimensionID)
            ->get();

        $this->dao->insert(TABLE_DASHBOARD)->data($data)
            ->batchCheck($this->config->dashboard->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Update dashboard.
     *
     * @param  int    $dimensionID
     * @access public
     * @return void
     */
    public function update($dashboardID)
    {
        $dashboard = fixer::input('post')
            ->skipSpecial('layout,filters')
            ->get();

        if($dashboard->filters)
        {
            $filters = json_decode($dashboard->filters);
            foreach($filters as $filter)
            {
                $pregs = '/select|insert|update|CR|document|LF|eval|delete|script|alert|\'|\/\*|\#|\--|\ --|\/|\*|\-|\+|\=|\~|\*@|\*!|\$|\%|\^|\&|\(|\)|\/|\/\/|\.\.\/|\.\/|union|into|load_file|outfile/i';
                if(preg_match($pregs, $filter->field)) return;
            }
        }

        $this->dao->update(TABLE_DASHBOARD)
            ->data($dashboard)
            ->where('id')->eq($dashboardID)
            ->exec();
    }
}
