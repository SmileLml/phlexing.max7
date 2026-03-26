<?php
/**
 * The control file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <chunsheng@cnezsoft.com>
 * @package     dataview
 * @version     $Id: control.php 5086 2023-06-06 02:25:22Z
 * @link        http://www.zentao.net
 */
class dataview extends control
{
    /**
     * Ajax query, get fields and result.
     *
     * @access public
     * @return void
     */
    public function ajaxQuery()
    {
        $this->loadModel('bi');
        $filters    = (isset($_POST['filters']) and is_array($this->post->filters)) ? $this->post->filters : array();
        $driver     = isset($_POST['driver'])     ? $this->post->driver     : 'mysql';
        $recPerPage = isset($_POST['recPerPage']) ? $this->post->recPerPage : 25;
        $pageID     = isset($_POST['pageID'])     ? $this->post->pageID     : 1;

        $cloneFilters = $filters;
        foreach($filters as $index => $filter)
        {
            if(empty($filter['default'])) continue;
            if(!isset($filter['from']) || $filter['from'] != 'query') continue;

            $filters[$index]['default'] = $this->loadModel('pivot')->processDateVar($filter['default']);
        }

        $sql = base64_decode($this->post->sql);
        $querySQL = $this->bi->parseSqlVars($sql, $filters);
        $querySQL = trim($querySQL, ';');

        if(empty($querySQL)) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->empty));

        $this->app->loadClass('sqlparser', true);
        $parser = new sqlparser($querySQL);

        if(count($parser->statements) == 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->empty));
        if(count($parser->statements) > 1)  return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->onlyOne));

        $statement = $parser->statements[0];
        if($statement instanceof PhpMyAdmin\SqlParser\Statements\SelectStatement == false) return $this->send(array('result' => 'fail', 'message' => $this->lang->dataview->allowSelect));

        // check origin sql error.
        $explainResult = $this->bi->explainSQL($querySQL, $driver);
        if($explainResult['result'] == 'fail') return $this->send($explainResult);

        $sqlColumns = $this->bi->getColumns($querySQL, $driver);
        list($isUnique, $repeatColumn) = $this->dataview->checkUniColumn($querySQL, $driver, true, $sqlColumns);
        if(!$isUnique) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->dataview->duplicateField, implode(',', $repeatColumn))));

        $columns      = $this->bi->getColumnsType($querySQL, $driver, $sqlColumns);
        foreach ($columns as $key => $value) {
            if (!preg_match('/^[\p{Han}\w]+$/u', $key)) {
                return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->dataview->errorField, $key)));
            }
        }

        $columnFields = array();
        foreach($columns as $column => $type) $columnFields[$column] = $column;

        $tableAndFields = $this->loadModel('bi')->getTableAndFields($querySQL);
        $tables   = $tableAndFields['tables'];
        $fields   = $tableAndFields['fields'];

        $moduleNames = array();
        $aliasNames  = array();
        if($tables)
        {
            $moduleNames = $this->dataview->getModuleNames($tables);
            $aliasNames  = $this->dataview->getAliasNames($statement, $moduleNames);
        }

        list($fieldPairs, $relatedObject) = $this->dataview->mergeFields($columnFields, $fields, !empty($moduleNames) ? $moduleNames : $tables, $aliasNames);

        /* Limit 100. */
        if(!$statement->limit) $statement->limit = new stdclass();
        $statement->limit->offset   = $recPerPage * ($pageID - 1);
        $statement->limit->rowCount = $recPerPage;

        if($driver == 'mysql') $statement->options->options[] = 'SQL_CALC_FOUND_ROWS';
        $limitSQL = $statement->build();

        $queryResult = $this->bi->querySQL($querySQL, $limitSQL, $driver);
        if($queryResult['result'] == 'fail') return $this->send($queryResult);
        $rows      = $queryResult['rows'];
        $rowsCount = $queryResult['rowsCount'];

        return $this->send(array('result' => 'success', 'rows' => $rows, 'fields' => $fieldPairs, 'columns' => $columns, 'filters' => $cloneFilters, 'lineCount' => $rowsCount, 'columnCount' => count($fieldPairs), 'relatedObject' => $relatedObject, 'recPerPage' => $recPerPage, 'pageID' => $pageID));
    }
}
