<?php
/**
 * The model file of sqlbuilder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xinzhi Qi <qixinzhi@chandao.com>
 * @package     sqlbuilder
 * @link        http://www.zentao.net
 */
?>
<?php
class sqlbuilderModel extends model
{
    public function getByObject($objectID, $objectType)
    {
        $setting = $this->dao->select('`setting`')
            ->from(TABLE_SQLBUILDER)
            ->where('objectID')->eq($objectID)
            ->andWhere('objectType')->eq($objectType)
            ->fetch('setting');

        $setting = json_decode($setting, true);
        if(!is_array($setting)) $setting = array();

        $this->app->loadClass('sqlbuilderstate', true);
        $builder = new sqlBuilderState($setting);

        $this->setTableDescList($builder);
        $this->buildSql($builder);
        return $builder;
    }

    public function save($objectID, $objectType, $sqlbuilder)
    {
        $exists = $this->dao->select('objectID')
            ->from(TABLE_SQLBUILDER)
            ->where('objectID')->eq($objectID)
            ->fetch();

        $sqlbuilder = $this->initSqlBuilder($sqlbuilder);
        $data = new stdclass();
        $data->setting    = $sqlbuilder->getJson();
        $data->sql        = $sqlbuilder->sql;
        $data->objectID   = $objectID;
        $data->objectType = $objectType;

        if($exists) $this->dao->update(TABLE_SQLBUILDER)
            ->data($data)
            ->where('objectID')->eq($objectID)
            ->andWhere('objectType')->eq($objectType)
            ->exec();
        else $this->dao->insert(TABLE_SQLBUILDER)->data($data)->exec();
    }


    public function initSqlBuilder($sqlbuilder)
    {
        $this->app->loadClass('sqlbuilderstate', true);
        if(!$sqlbuilder instanceof sqlBuilderState) $sqlbuilder = new sqlBuilderState($sqlbuilder);

        $this->buildSql($sqlbuilder);

        return $sqlbuilder;
    }

    public function buildSql($builder)
    {
        $parser = $this->app->loadClass('sqlparser');
        $builder->build($parser);
    }

    public function isSqlBuilderAction()
    {
        $action = zget($_POST, 'action', '');
        return strpos($action, 'sqlBuilder-') !== false;
    }

    public function isQueryAction()
    {
        $action = zget($_POST, 'action', '');
        return $action === 'sqlBuilder-query';
    }

    public function sqlBuilderAction()
    {
        $action   = zget($_POST, 'action', '');
        $postData = json_decode(zget($_POST, 'data', '{}'), true);
        $sqlBuilder = zget($postData, 'sqlbuilder', array());
        if(!empty($sqlBuilder['sql'])) $sqlBuilder['sql'] = base64_decode($sqlBuilder['sql']);

        $this->app->loadClass('sqlbuilderstate', true);
        $builder = new sqlBuilderState($sqlBuilder);

        if(!empty($action))
        {
            $action = str_replace('-', '', $action);
            $funcName = "{$action}Action";
            if(method_exists($this, $funcName)) $this->$funcName($builder);
        }

        $this->setTableDescList($builder);
        $this->buildSql($builder);

        return $builder;
    }

    public function sqlBuilderStepAction($builder)
    {
        $step = $builder->step;

        if($step == 'table') return;

        $checkList = array('checkFrom' => 'table', 'checkJoins' => 'table', 'checkSelects' => 'field', 'checkFuncs' => 'func', 'checkWheres' => 'where', 'checkQuerys' => 'query');
        foreach($checkList as $check => $checkStep)
        {
            $result = $builder->$check();
            if(!is_string($result)) continue;
            if($step != $checkStep)
            {
                $builder->step = $checkStep;
                return $builder->setError($result);
            }
        }

        if($step == 'query') $this->sqlBuilderQueryAction($builder);
    }

    /**
     * 处理sql构建器改变联表选择操作。
     * Handle change builder table design action for sql builder.
     *
     * @access public
     * @return void
     */
    public function sqlBuilderTableAction($builder)
    {
    }

    /**
     * 处理sql构建器改变函数字段操作。
     * Handle change builder func design action for sql builder.
     *
     * @access public
     * @return void
     */
    public function sqlBuilderFuncAction($builder)
    {
        $builder->processFuncs();
        $builder->processGroupBy();
    }

    /**
     * 处理sql构建器改变选择字段操作。
     * Handle change builder field design action for sql builder.
     *
     * @access public
     * @return void
     */
    public function sqlBuilderFieldAction($builder)
    {
        $builder->processCheckAll();
        $builder->processGroupBy();
    }

    /**
     * 处理sql构建器改变选择分组聚合字段操作。
     * Handle change builder group design action for sql builder.
     *
     * @access public
     * @return void
     */
    public function sqlBuilderGroupAction($builder)
    {
        $builder->setAggFunc();
        $builder->processFuncs();
    }

    public function sqlBuilderQueryAction($builder)
    {
        $builder->processQueryFilters();

        $querys  = $builder->querys;
        $options = $builder->queryFilterSelectOptions;
        foreach($querys as $query)
        {
            $typeOption = $query['typeOption'];
            if(empty($typeOption) || isset($options[$typeOption])) continue;

            $options[$typeOption] = $this->getFilterOptionUrl($query);
        }
        $builder->queryFilterSelectOptions = $options;
    }

    /**
     * getFilterOptionUrl
     *
     * @param  array $filter
     * @access public
     * @return string
     */
    public function getFilterOptionUrl($filter)
    {
        $field  = $filter['field'];
        $value  = zget($filter, 'default', '');
        $values = is_array($value) ? implode(',', $value) : $value;

        $typeOption = $filter['typeOption'];
        $url = helper::createLink('pivot', 'ajaxGetSysOptions', "search={search}");
        return (object)array('url' => $url, 'method' => 'post', 'data' => array('type' => $typeOption, 'values' => $values));
    }

    /**
     * Set table desc list.
     *
     * @access private
     * @return void
     */
    private function setTableDescList($builder)
    {
        $joins     = $builder->joins;
        $tableDesc = $builder->tableDesc;
        $from      = $builder->from;

        $joins[] = $from;
        foreach($joins as $join)
        {
            $table = zget($join, 'table');
            if(empty($table) || isset($tableDesc[$table])) continue;
            $builder->addTableDesc($table, $this->getTableFieldList($table));
        }
    }

    /**
     * Get table desc list.
     *
     * @param  string $table
     * @access public
     * @return array
     */
    private function getTableFieldList($table)
    {
        $fields = $this->loadModel('dev')->getFields($table);

        $fieldList = array();
        foreach($fields as $field => $info)
        {
            $name = !empty($info['name']) ? $info['name'] : $field;
            $fieldList[$field] = $name;
        }

        return $fieldList;
    }
}
