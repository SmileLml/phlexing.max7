<?php
/**
 * The control file of report module of zentaopms.
 *
 * @copyright   copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     zpl (http://zpl.pub/page/zplv12.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     report
 * @link        https://www.zentao.net
 */
helper::importControl('report');
class myReport extends report
{
    /**
     * Report details.
     *
     * @param  int    $reportID
     * @param  string $reportModule
     * @access public
     * @return void
     */
    public function show($reportID, $reportModule = '')
    {
        if($reportModule == 'program')
        {
            $this->loadModel('project')->setMenu($this->session->project);
            $this->view->projectID = $this->session->project;
        }
        elseif(isset($this->lang->report->menu->$reportModule))
        {
            /* Compatible with PHP5.x. */
            $reportModuleMenu = $this->lang->report->menu->$reportModule;
            $reportModuleMenu['alias'] = 'show';
            $this->lang->report->menu->$reportModule = $reportModuleMenu;
        }

        $report = $this->report->getReportByID($reportID);
        $permissionProduct = common::hasPriv('report', 'showProduct') or $this->app->user->admin;
        $permissionProject = common::hasPriv('report', 'showProject') or $this->app->user->admin;

        $interceptExecution = 0;
        if(strpos($report->sql, 'TABLE_EXECUTION') !== false)
        {
            $searchExecution     = stripos($report->sql, 'TABLE_EXECUTION as');
            $interceptExecution  = substr($report->sql, $searchExecution + 18, 3);
        }

        $start           = stripos($report->sql, 'where') + 6;
        $reportSQL       = $report->sql;
        $searchField     = $reportModule == 'product' ? stripos($report->sql, 'TABLE_PRODUCT as') : stripos($report->sql, 'TABLE_PROJECT as');
        $searchDataList  = $reportModule == 'product' ? $this->app->user->view->products : $this->app->user->view->projects;
        $interceptField  = substr($report->sql, $searchField + 16, 3);

        if(!empty($searchDataList))
        {
            $reportSql = "{$interceptField}.id in ({$searchDataList}) and ";
            $reportExecutionSql = $reportSql . "{$interceptExecution}.id in({$this->app->user->view->sprints}) and ";

            if(!$permissionProduct && $reportModule == 'product')
            {
                $reportSQL = substr_replace($report->sql, $reportSql, $start, 0);
            }
            elseif(!$permissionProject && $reportModule == 'project')
            {
                $reportSQL = $interceptExecution ? substr_replace($report->sql, $reportExecutionSql, $start, 0) : substr_replace($report->sql, $reportSql, $start, 0);
            }
        }

        if(!$report)
        {
            echo js::alert($this->lang->crystal->errorNoReport);
            echo js::locate('back');
        }

        if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $this->session->project) $this->report->buildReportList($this->session->project);

        $this->session->set('reportSQL', $report->sql);
        $this->session->set('reportParams', $report->params);
        $this->session->set('sqlVars', $report->vars);
        $this->session->set('sqlLangs', $report->langs);

        $this->view->submenu    = $reportModule;
        $this->view->setVars    = false;
        $this->view->title      = $this->report->replace4Workflow($report->name[$this->app->getClientLang()]);
        $this->view->position[] = $report->name[$this->app->getClientLang()];

        $sql = $reportSQL;
        if($report->vars)
        {
            $sqlVars = json_decode($report->vars, true);

            $this->view->setVars = true;
            $this->view->sqlVars = $sqlVars;
            if(isset($_POST['sqlVars']))
            {
                $sqlVarValues = $this->post->sqlVars;
            }
            else
            {
                $sqlVarValues = array();
                foreach($sqlVars['varName'] as $i => $varName)
                {
                    $varType = ($sqlVars['requestType'][$i] == 'select') ? $sqlVars['selectList'][$i] : $sqlVars['requestType'][$i];
                    $sqlVarValues[$varName] = isset($sqlVars['default'][$i]) ? $sqlVars['default'][$i] : '';
                    if($varType == 'dept' and empty($sqlVarValues[$varName])) $sqlVarValues[$varName] = 0;
                }
            }

            $this->session->set('sqlVarValues', serialize($sqlVarValues));
            foreach($sqlVars['varName'] as $sqlVar)
            {
                if(!isset($sqlVarValues[$sqlVar])) $sqlVarValues[$sqlVar] = '';
                $sqlVarValues[$sqlVar] = $this->report->processSqlVar($sqlVarValues[$sqlVar]);

                /* Set default value for project and execution when not default value. */
                if($sqlVar == 'project' and empty($sqlVarValues[$sqlVar]))
                {
                    $projects = arrayUnion(array('' => $this->lang->crystal->allProject), $this->loadModel('project')->getPairsByProgram());
                    $sqlVarValues[$sqlVar] = key($projects);
                }

                $sql = str_replace('$' . $sqlVar, $this->dbh->quote($sqlVarValues[$sqlVar]), $sql);
            }
            $this->view->sqlVarValues = $sqlVarValues;
        }

        /* replace define table name to real table name. */
        $sql = $this->report->replaceTableNames($sql);
        $tableAndField = $this->report->getTables($sql);
        $tables        = $tableAndField['tables'];
        $fields        = $tableAndField['fields'];

        /* Don't display the shadow products in product reports. */
        $productTable = str_replace('`', '', TABLE_PRODUCT);
        if($reportModule == 'product' && in_array($productTable, $tables))
        {
            $sql = str_replace("{$productTable}`", 'ztv_normalproduct`', $sql);
            $sql = str_replace("{$productTable})", 'ztv_normalproduct)', $sql);
            $sql = str_replace("{$productTable} ", 'ztv_normalproduct ', $sql);
        }

        $dataList = $this->dao->query($sql)->fetchAll();

        $moduleNames = array();
        foreach($tables as $table)
        {
            if(strpos($table, $this->config->db->prefix) === false) continue;
            $module = str_replace($this->config->db->prefix, '', $table);
            if($module == 'case')   $module = 'testcase';
            if($module == 'module') $module = 'tree';
            /* Code for workflow.*/
            if(strpos($module, 'flow_') !== false)
            {
                $moduleName = substr($module, 5);
                $flowFields = $this->loadModel('workflowfield')->getFieldPairs($moduleName);
                $this->lang->$moduleName = new stdclass();

                foreach($flowFields as $flowField => $fieldName)
                {
                    if(!$flowField) continue;
                    $this->lang->$moduleName->$flowField = $fieldName;
                }
                $moduleNames[$table] = $module;
            }
            else
            {
                if($this->app->loadLang($module))
                {
                    $moduleNames[$table] = $module;
                    if($module == 'project') $this->lang->project->statusList = arrayUnion($this->lang->project->statusList, $this->lang->report->projectStatusList);
                }
            }
        }

        $data          = (array)current($dataList);
        $this->session->set('reportDataCount', count($dataList));
        $moduleNames   = array_reverse($moduleNames, true);
        $reverseFields = array_reverse($fields, true);
        $mergeFields   = $this->report->mergeFields(array_keys($data), $reverseFields, $moduleNames);

        if($report->step == 2)
        {
            $condition = json_decode($report->params, true);
            if(!empty($condition['isUser'])) $this->view->users = $this->loadModel('user')->getPairs('noletter');

            $groupLang['group1'] = $this->report->getGroupLang($condition['group1'], $reverseFields, $moduleNames);
            $groupLang['group2'] = $this->report->getGroupLang($condition['group2'], $reverseFields, $moduleNames);
            list($headers, $reportData) = $this->report->processData($dataList, $condition);

            $this->view->headerNames = $this->report->getHeaderNames($fields, $moduleNames, $condition);
            $this->view->headers     = $headers;
            $this->view->condition   = $condition;
            $this->view->reportData  = $reportData;
            $this->view->groupLang   = $groupLang;
        }

        $this->view->dataList = $dataList;
        $this->view->tables   = $tables;
        $this->view->step     = $report->step;
        $this->view->reportID = $reportID;
        $this->view->report   = $report;
        $this->view->fields   = empty($mergeFields) ? array() : $mergeFields;

        $this->display();
    }
}
