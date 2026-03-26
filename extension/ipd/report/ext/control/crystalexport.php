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
     * Export report.
     *
     * @param  int    $step
     * @param  int    $reportID
     * @access public
     * @return void
     */
    public function crystalExport($step = 2, $reportID = 0)
    {
        if($_POST)
        {
            $tables = array();
            $hasSqlVar = false;
            if($step != 0 and $this->session->reportSQL)
            {
                $sql    = $this->session->reportSQL;
                $result = $this->report->checkSqlVar($sql);

                if($result)
                {
                    $sqlVarValues = $this->session->sqlVarValues ? unserialize($this->session->sqlVarValues) : array();
                    if($sqlVarValues)
                    {
                        foreach($result as $sqlVar)
                        {
                            $sqlVarValues[$sqlVar] = $this->report->processSqlVar($sqlVarValues[$sqlVar]);
                            $sql = str_replace('$' . $sqlVar, $this->dbh->quote($sqlVarValues[$sqlVar]), $sql);
                        }
                    }

                    $hasSqlVar    = true;
                    $sqlVars      = json_decode($this->session->sqlVars, true);
                    $sqlVarValues = $sqlVarValues;
                }
                $rowspan  = array();
                $dataList = array();
                $fields   = array();
                if(!$result or $this->session->sqlVarValues)
                {
                    /* replace define table name to real table name. */
                    $sql = $this->report->replaceTableNames($sql);
                    $tableAndField = $this->report->getTables($sql);
                    $tables   = $tableAndField['tables'];
                    $fields   = $tableAndField['fields'];

                    /* Don't display the shadow products in product reports. */
                    $productTable = str_replace('`', '', TABLE_PRODUCT);
                    if(in_array($productTable, $tables)) $sql = str_replace($productTable, 'ztv_normalproduct', $sql);

                    $dataList = $this->dbh->query($sql)->fetchAll();

                    /* Set rowspan. */
                    $colField      = array_keys($fields);
                    $firstColField = array_shift($colField);
                    $dataLists     = $dataList;
                    $prevData      = '';
                    $colNum        = 0;
                    foreach($dataLists as $i => $colData)
                    {
                        if($prevData == $colData->$firstColField)
                        {
                            if(!isset($rowspan[$colNum]['rows'][$firstColField])) $rowspan[$colNum]['rows'][$firstColField] = 1;
                            $rowspan[$colNum]['rows'][$firstColField] ++;
                            continue;
                        }

                        $prevData = $colData->$firstColField;
                        $colNum   = $i;
                    }

                    $moduleNames = array();
                    foreach($tables as $table)
                    {
                        if(strpos($table, $this->config->db->prefix) === false) continue;
                        $module = str_replace($this->config->db->prefix, '', $table);
                        if($module == 'case')   $module = 'testcase';
                        if($module == 'module') $module = 'tree';
                        /* Code for workflow. */
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

                    $data = (array)current($dataList);
                    $this->session->set('reportDataCount', count($dataList));
                    $moduleNames   = array_reverse($moduleNames, true);
                    $reverseFields = array_reverse($fields, true);
                    $mergeFields   = $this->report->mergeFields(array_keys($data), $reverseFields, $moduleNames);
                }

                if($step == 2 and $this->session->reportParams)
                {
                    $condition = json_decode($this->session->reportParams, true);
                    if(!empty($condition['isUser'])) $users = $this->loadModel('user')->getPairs('noletter');

                    $reportData = array();
                    $headers    = array();
                    $groupLang['group1']  = $this->report->getGroupLang($condition['group1'], $reverseFields, $moduleNames);
                    $groupLang['group2']  = $this->report->getGroupLang($condition['group2'], $reverseFields, $moduleNames);
                    list($headers, $reportData) = $this->report->processData($dataList, $condition);

                    $headerNames = $this->report->getHeaderNames($fields, $moduleNames, $condition);
                }

                $fields = empty($mergeFields) ? array() : $mergeFields;

                if($step == 2)
                {
                    $step2Fields = array();
                    $step2Fields['group1'] = $fields[$condition['group1']];
                    if($condition['group2']) $step2Fields['group2'] = $fields[$condition['group2']];

                    /* Set dataCols. */
                    $dataCols   = array();
                    $sqlLangs   = json_decode($this->session->sqlLangs, true);
                    $clientLang = $this->app->getClientLang();
                    foreach($headers as $i => $reportFields)
                    {
                        $showed[$i] = false;
                        foreach($reportFields as $field => $reportField)
                        {
                            if(isset($headerNames[$i]))
                            {
                                foreach($headerNames[$i] as $key => $headerName)
                                {
                                    $step2Fields[] = empty($headerName) ? $this->lang->report->null : $headerName;
                                    $percentKey = (empty($key) ? 'null' : $key) . 'Percent';
                                    if(isset($condition['percent'][$i]) and isset($condition['showAlone'][$i]) and $condition['contrast'][$i] != 'crystalTotal') $step2Fields[] = isset($sqlLangs[$percentKey][$clientLang]) ? $sqlLangs[$percentKey][$clientLang] : $this->lang->crystal->percentAB;
                                    $dataCols[$i][] = $key;
                                }
                                $showed[$i] = true;
                            }
                            elseif(isset($condition['isUser']['reportField'][$i]))
                            {
                                $user = zget($users, $reportField, $reportField);
                                $step2Fields[] = empty($user) ? $this->lang->report->null : $user;
                                $dataCols[$i][] = $reportField;
                            }
                            else
                            {
                                $step2Fields[] = zget($fields, $reportField, $reportField);
                                $dataCols[$i][] = $reportField;
                            }
                            if($showed[$i]) break;
                            $percentKey = $reportField . 'Percent';
                            if(isset($condition['percent'][$i]) and isset($condition['showAlone'][$i]) and $condition['contrast'][$i] != 'crystalTotal') $step2Fields[] = isset($sqlLangs[$percentKey][$clientLang]) ? $sqlLangs[$percentKey][$clientLang] : $this->lang->crystal->percentAB;
                        }
                        if(isset($condition['reportTotal'][$i])) $step2Fields[] = $this->lang->crystal->total;
                        $percentKey = $reportField . 'Percent';
                        if(isset($condition['percent'][$i]) and isset($condition['showAlone'][$i]) and $condition['contrast'][$i] == 'crystalTotal') $step2Fields[] = isset($sqlLangs[$percentKey][$clientLang]) ? $sqlLangs[$percentKey][$clientLang] : $this->lang->crystal->percentAB;
                    }

                    foreach($step2Fields as $i => $field)
                    {
                        if(is_numeric($i))
                        {
                            $step2Fields["col$i"] = $field;
                            unset($step2Fields[$i]);
                        }
                    }

                    $allTotal = array();
                    $rowspan  = array();
                    $colspan  = array();
                    $row      = 0;
                    foreach($reportData as $group1 => $group1Data)
                    {
                        if($condition['group2'])
                        {
                            $group2Num = 0;
                            foreach($group1Data as $group2 => $data)
                            {
                                $reportData = new stdclass();
                                $group2Num++;
                                if($group2Num == 1)
                                {
                                    if(count($group1Data) > 1)
                                    {
                                        $rowspan[$row]['rows'] = ',group1,';
                                        $rowspan[$row]['num'] = count($group1Data);
                                    }
                                    $reportData->group1 = $group1;
                                    if(!empty($condition['isUser']['group1']))
                                    {
                                        $reportData->group1 = zget($users, $group1, $group1);
                                    }
                                    elseif($groupLang['group1'])
                                    {
                                        $reportData->group1 = zget($groupLang['group1'], $group1, $group1);
                                    }
                                }

                                $reportData->group2 = $group2;
                                if(!empty($condition['isUser']['group2']))
                                {
                                    $reportData->group2 = zget($users, $group2, $group2);
                                }
                                elseif($groupLang['group2'])
                                {
                                    $reportData->group2 = zget($groupLang['group2'], $group2, $group2);
                                }

                                $data         = $this->report->getCellData($data, $dataCols, $condition);
                                $allTotal     = $data['allTotal'];
                                $cellDataList = $data['cellData'];

                                foreach($cellDataList as $i => $cellData) $reportData->{"col$i"} = $cellData;
                                $rows[$row] = $reportData;
                                $row++;
                            }
                        }
                        else
                        {
                            $reportData = new stdclass();
                            $reportData->group1 = $group1;
                            if(!empty($condition['isUser']['group1']))
                            {
                                $reportData->group1 = zget($users, $group1, $group1);
                            }
                            elseif($groupLang['group1'])
                            {
                                $reportData->group1 = zget($groupLang['group1'], $group1, $group1);
                            }

                            $data         = $this->report->getCellData($group1Data, $dataCols, $condition);
                            $allTotal     = $data['allTotal'];
                            $cellDataList = $data['cellData'];
                            foreach($cellDataList as $i => $cellData)
                            {
                                $reportData->{"col$i"} = $cellData ? $cellData : 0;
                            }
                            $rows[$row] = $reportData;
                            $row++;
                        }
                    }
                    $rows[$row] = new stdclass();
                    $rows[$row]->group1 = $this->lang->crystal->total;
                    if($condition['group2'])
                    {
                        $colspan[$row]['cols'] = ',group1,';
                        $colspan[$row]['num'] = 2;
                    }
                    foreach($step2Fields as $i => $field)
                    {
                        if(strpos($i, 'group') === false)
                        {
                            $i = str_replace('col', '', $i);
                            $rows[$row]->{"col$i"} = $allTotal[$i];
                        }
                    }

                    $dataList = $rows;
                    $fields   = array();

                    foreach($dataList as $row)
                    {
                        foreach($row as $colName => $value)
                        {
                            $fields[$colName] = $step2Fields[$colName];
                        }
                    }
                }

                if(isset($rowspan))$this->post->set('rowspan', $rowspan);
                if(isset($colspan))$this->post->set('colspan', $colspan);
                $this->post->set('fields', $fields);
                $this->post->set('rows', $dataList);
                $this->post->set('kind', 'report');
                $this->post->set('fileField', 'false');

                $this->loadModel('file');
                $this->lang->excel->title->report = $this->post->fileName;
                $this->config->excel->titleFields[] = 'group1';
                $this->config->excel->titleFields[] = 'group2';
                $this->config->excel->cellHeight    = 25;
                $this->config->excel->width->title  = 25;
                die($this->fetch('file', 'export2' . $this->post->fileType, $_POST));
            }
        }

        $report = $reportID ? $this->report->getReportByID($reportID) : '';
        $this->view->name = $reportID ? $report->name[$this->app->getClientLang()] : '';
        $this->display();
    }
}
