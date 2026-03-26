<?php
class crystalReport extends model
{
    /**
     * Get report by id.
     *
     * @param  int    $reportID
     * @access public
     * @return object
     */
    public function getReportByID($reportID)
    {
        $report = $this->dao->select('*')->from(TABLE_REPORT)->where('id')->eq($reportID)->fetch();

        if(!empty($report))
        {
            $name = json_decode($report->name, true);
            if(empty($name)) $name[$this->app->getClientLang()] = $report->name;
            $report->name = $name;
        }

        return $report;
    }

    /**
     * Get report list.
     *
     * @param  string $module
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getReportList($module = '', $orderBy = 'id_asc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_REPORT)
            ->beginIF($module)->where('module')->like("%{$module}%")->fi()
            ->beginIF(!$module)->where('module')->notlike("%cmmi%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Check black list.
     *
     * @param  string $sql
     * @access public
     * @return string|bool
     */
    public function checkBlackList($sql)
    {
        $checkSql = " $sql ";
        foreach(explode(',', $this->config->crystal->sqlBlackList) as $keywords)
        {
            $keywords = trim($keywords);
            if(stripos($checkSql, " $keywords ") !== false) return $keywords;
        }
        foreach(explode(',', $this->config->crystal->sqlBlackFunc) as $keywords)
        {
            $keywords = trim($keywords);
            if(preg_match("/$keywords *\(/i", $checkSql)) return $keywords;
        }
        if(stripos($sql, 'select') !== 0) return 'noselect';

        return false;
    }

    /**
     * Get tables.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getTables($sql)
    {
        $sql = trim($sql, ';');
        $sql = str_replace(array("\r\n", "\n"), ' ', $sql);
        $sql = str_replace('`', '', $sql);
        preg_match_all('/^(explain select|select) (.+) from ([^;]+).*$/i', $sql, $tables);
        if(empty($tables[3][0])) return false;

        $fields = $tables[2][0];
        $tables = $tables[3][0];
        if(stripos($tables, 'where') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'where')));
        if(stripos($tables, 'limit') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'limit')));
        if(stripos($tables, 'having') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'having')));
        if(stripos($tables, 'group by') !== false) $tables = trim(substr($tables, 0, stripos($tables, 'group by')));

        /* Remove such as "left join|right join|join", "on (t1.id=t2.id)", result like t1, t2 as t3. */
        $tables  = $tables . ' ';
        if(stripos($tables, 'join') !== false) $tables = preg_replace(array('/join\s+([A-Z]+_\w+ .*)on/Ui', '/,\s*on\s+[^,]+/i'), array(',$1,on', ''), $tables);

        /* Match t2 as t3 */
        $fields = explode(',', $fields);
        preg_match_all('/(\w+) +as +(\w+)/i', $tables, $out);
        foreach($fields as $i => $field)
        {
            if($field) $asField = '';
            if(strrpos($field, ' as ') !== false) list($field, $asField) = explode(' as ', $field);

            $field     = trim($field);
            $asField   = trim($asField);
            $fieldName = $field;
            if(strrpos($field, '.') !== false)
            {
                $table     = substr($field, 0, strrpos($field, '.'));
                $fieldName = substr($field, strrpos($field, '.') + 1);
                if(!empty($out[0]) and in_array($table, $out[2])) $field = str_replace($table . '.', $out[1][array_search($table, $out[2])] . '.', $field);

                if($fieldName == '*') $fieldName = $field;
            }

            $fieldName = $asField ? $asField : $fieldName;
            $fields[$fieldName] = $field;
            unset($fields[$i]);
        }

        $tables = preg_replace('/as +\w+/i', ' ', $tables);
        $tables = trim(str_replace(array('(', ')', ','), ' ', $tables));
        $tables = preg_replace('/ +/', ' ', $tables);

        $tables = explode(' ', $tables);
        return array('tables' => $tables, 'fields' => $fields);
    }

    /**
     * Merge fields.
     *
     * @param  int    $dataFields
     * @param  int    $sqlFields
     * @param  int    $moduleNames
     * @access public
     * @return void
     */
    public function mergeFields($dataFields, $sqlFields, $moduleNames)
    {
        $mergeFields = array();
        foreach($dataFields as $field)
        {
            $mergeFields[$field] = $field;
            /* Such as $sqlFields['id'] = zt_task.id. */
            if(isset($sqlFields[$field]) and strrpos($sqlFields[$field], '.') !== false)
            {
                $sqlField  = $sqlFields[$field];
                $table     = substr($sqlField, 0, strrpos($sqlField, '.'));
                $fieldName = substr($sqlField, strrpos($sqlField, '.') + 1);

                if(isset($moduleNames[$table]))
                {
                    $moduleName = $moduleNames[$table];
                    if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                    $mergeFields[$field] = isset($this->lang->$moduleName->$fieldName) ? $this->lang->$moduleName->$fieldName : $field;
                    continue;
                }
            }

            if(strpos(join(',', $sqlFields), '.*') !== false)
            {
                /* Such as $sqlFields['zt_task.*'] = zt_task.*. */
                $existField = false;
                foreach($sqlFields as $sqlField)
                {
                    if(strrpos($sqlField, '.*') !== false)
                    {
                        $table = substr($sqlField, 0, strrpos($sqlField, '.'));
                        if(isset($moduleNames[$table]))
                        {
                            $moduleName = $moduleNames[$table];
                            if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                            $mergeFields[$field] = isset($this->lang->$moduleName->$field) ? $this->lang->$moduleName->$field : $field;
                            $existField = true;
                            break;
                        }
                    }
                }
                if($existField) continue;
            }

            foreach($moduleNames as $table => $moduleName)
            {
                if(strpos($moduleName, 'flow_') !== false) $moduleName = substr($moduleName, 5);
                if(isset($this->lang->$moduleName) and isset($this->lang->$moduleName->$field))
                {
                    $mergeFields[$field] = $this->lang->$moduleName->$field;
                    break;
                }
                $mergeFields[$field] = $field;
            }
        }

        $sqlLangs   = json_decode($this->session->sqlLangs, true);
        $clientLang = $this->app->getClientLang();
        foreach($mergeFields as $fieldName => $fieldValue)
        {
            if(isset($sqlLangs[$fieldName][$clientLang]))
            {
                $mergeFields[$fieldName] = $sqlLangs[$fieldName][$clientLang];
                continue;
            }

            if(empty($fieldValue)) $mergeFields[$fieldName] = $fieldName;
        }

        foreach($mergeFields as $field => $name) $mergeFields[$field] = $this->replace4Workflow($name);
        return $mergeFields;
    }

    /**
     * Get cell data.
     *
     * @param  array  $data
     * @param  array  $dataCols
     * @param  array  $condition
     * @param  bool   $initStaticData
     * @access public
     * @return array
     */
    public function getCellData($data, $dataCols, $condition, $initStaticData = false)
    {
        static $allTotal   = array();
        static $totalRadio = array();
        if($initStaticData)
        {
            $allTotal   = array();
            $totalRadio = array();
        }

        $cellData     = array();
        $col          = 0;
        $percentCols  = array();
        $colTotals    = array();
        foreach($dataCols as $i => $cols)
        {
            $colTotal    = 0;
            foreach($cols as $field)
            {
                if(array_key_exists($field, $data[$i]))
                {
                    $colTotal += $data[$i][$field];
                    $cellData[$col] = $data[$i][$field];
                }
                else
                {
                    $cellData[$col] = 0;
                }
                if(!isset($allTotal[$col])) $allTotal[$col] = 0;
                if(is_numeric($cellData[$col]) and strpos($cellData[$col], '.') !== false)
                {
                    $cellData[$col] = (float)$cellData[$col];
                    $allTotal[$col] = (float)$allTotal[$col];
                }
                $allTotal[$col] += $cellData[$col];

                $col++;
                if(isset($condition['percent'][$i]) and $condition['contrast'][$i] != 'crystalTotal')
                {
                    if(isset($condition['showAlone'][$i]))
                    {
                        $percentCols[$i][]  = $col;
                        $cellData[$col] = 0;
                        $col++;
                    }
                    else
                    {
                        $percentCols[$i][]  = $col - 1;
                    }
                }
            }

            if(isset($condition['reportTotal'][$i]))
            {
                $cellData[$col] = $colTotal;

                if(!isset($allTotal[$col])) $allTotal[$col] = 0;
                if(is_numeric($cellData[$col]) and strpos($cellData[$col], '.') !== false)
                {
                    $cellData[$col] = (float)$cellData[$col];
                    $allTotal[$col] = (float)$allTotal[$col];
                }
                $allTotal[$col] += $cellData[$col];

                $col++;
            }

            if(isset($condition['percent'][$i]) and $condition['contrast'][$i] == 'crystalTotal')
            {
                if(isset($condition['showAlone'][$i]) and $condition['showAlone'][$i])
                {
                    $percentCols[$i][]  = $col;
                    $cellData[$col] = 0;
                    $col++;
                }
                else
                {
                    $percentCols[$i][]  = $col - 1;
                }
            }

            $colTotals[$condition['reportField'][$i]]    = $colTotal;
        }

        if(isset($condition['percent']))
        {
            foreach($condition['percent'] as $i => $percent)
            {
                $reportField               = $condition['reportField'][$i];
                $contrastField             = $condition['contrast'][$i];
                $colTotals[$contrastField] = $data[$i][$contrastField];

                if(!isset($totalRadio[$i])) $totalRadio[$i] = array();
                if(!isset($totalRadio[$i][$reportField]))   $totalRadio[$i][$reportField]   = 0;
                if(!isset($totalRadio[$i][$contrastField])) $totalRadio[$i][$contrastField] = 0;
                $totalRadio[$i][$reportField] += zget($data[$i], $reportField, $colTotals[$reportField]);
                if($condition['contrast'][$i] == 'crystalTotal') $totalRadio[$i][$contrastField] = $data[$i][$contrastField];
            }
        }

        foreach($percentCols as $i => $percentCol)
        {
            $field       = $condition['contrast'][$i];
            $colTotal    = $condition['reportType'][$i] == 'count' ? $this->session->reportDataCount : zget($colTotals, $field, $colTotals[$condition['reportField'][$i]]);
            $colAllTotal = $totalRadio[$i][$field] ? $totalRadio[$i][$field] : $totalRadio[$i][$condition['reportField'][$i]];
            foreach($percentCol as $col)
            {
                if(isset($condition['showAlone'][$i]))
                {
                    $cellData[$col] = $colTotal == 0 ? "0%" :    (round($cellData[$col - 1] / $colTotal * 100, 2) . '%');
                    $allTotal[$col] = $colAllTotal == 0 ? "0%" : (round($allTotal[$col - 1] / $colAllTotal * 100, 2) . '%');
                }
                else
                {
                    $cellData[$col] .= ' (' . ($colTotal == 0    ? "0" : round($cellData[$col] / $colTotal * 100, 2 )) . '%)';
                    $allTotal[$col] .= ' (' . ($colAllTotal == 0 ? "0" : round($allTotal[$col] / $colAllTotal * 100, 2)) . '%)';
                }
            }
        }
        return array('cellData' => $cellData, 'allTotal' => $allTotal);
    }

    /**
     * Check sqlvar.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function checkSqlVar($sql)
    {
        $sql = $sql . ' ';
        preg_match_all('/\$(\w+)/i', $sql, $out);
        return array_unique($out[1]);
    }

    /**
     * Get header names.
     *
     * @param  array  $fields
     * @param  array  $moduleNames
     * @param  array  $condition
     * @access public
     * @return array
     */
    public function getHeaderNames($fields, $moduleNames, $condition)
    {
        if($this->config->edition != 'open') $this->loadModel('workflowfield');

        $headerNames = array();
        $sqlLangs    = json_decode($this->session->sqlLangs);

        foreach($condition['reportField'] as $i => $reportField)
        {
            /* Like $this->lang->task->priList. */
            $reportFieldList = $reportField . 'List';

            if(isset($fields[$reportField]) and strrpos($fields[$reportField], '.') !== false)
            {
                $table = substr($fields[$reportField], 0, strrpos($fields[$reportField], '.'));
                if(isset($moduleNames[$table]))
                {
                    $moduleName = $moduleNames[$table];
                    if(strpos($moduleName, 'flow_') !== false)
                    {
                        $moduleName = substr($moduleName, 5);
                        $flowField  = $this->workflowfield->getByField($moduleName, $reportField);
                        if(!empty($flowField))
                        {
                            $headerNames[$i] = $this->workflowfield->getFieldOptions($flowField);
                            continue;
                        }
                    }
                    else
                    {
                        if(isset($this->lang->$moduleName->$reportFieldList))
                        {
                            $headerNames[$i] = $this->lang->$moduleName->$reportFieldList;
                            continue;
                        }
                        elseif($this->config->edition != 'open')
                        {
                            $flowField  = $this->workflowfield->getByField($moduleName, $reportField);
                            if(!empty($flowField) and $flowField->buildin == 0 and isset($flowField->options))
                            {
                                $headerNames[$i] = $this->workflowfield->getFieldOptions($flowField);
                                continue;
                            }
                        }
                    }
                }
            }
            if(strpos(join(',', array_keys($fields)), '.*') !== false)
            {
                foreach($fields as $fieldName => $field)
                {
                    if(strpos($fieldName, '.*') === false) continue;
                    $table = substr($fieldName, 0, strrpos($fieldName, '.'));

                    if(isset($moduleNames[$table]))
                    {
                        $moduleName = $moduleNames[$table];
                        if(strpos($moduleName, 'flow_') !== false)
                        {
                            $moduleName = substr($moduleName, 5);
                            $flowField  = $this->workflowfield->getByField($moduleName, $reportField);
                            if(!empty($flowField))
                            {
                                $headerNames[$i] = $this->workflowfield->getFieldOptions($flowField);
                                continue;
                            }
                        }
                        else
                        {
                            if(isset($this->lang->$moduleName->$reportFieldList))
                            {
                                $headerNames[$i] = $this->lang->$moduleName->$reportFieldList;
                                continue;
                            }
                            elseif($this->config->edition != 'open')
                            {
                                $flowField  = $this->workflowfield->getByField($moduleName, $reportField);
                                if(!empty($flowField) and $flowField->buildin == 0 and isset($flowField->options))
                                {
                                    $headerNames[$i] = $this->workflowfield->getFieldOptions($flowField);
                                    continue;
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                foreach($moduleNames as $module)
                {
                    if(strpos($module, 'flow_') !== false)
                    {
                        $module     = substr($module, 5);
                        $flowField  = $this->workflowfield->getByField($module, $reportField);
                        if(!empty($flowField) and isset($flowField->options))
                        {
                            $headerNames[$i] = $this->workflowfield->getFieldOptions($flowField);
                            break;
                        }
                    }
                    else
                    {
                        if(isset($this->lang->$module->$reportFieldList))
                        {
                            $headerNames[$i] = $this->lang->$module->$reportFieldList;
                            break;
                        }
                        elseif($this->config->edition != 'open')
                        {
                            $flowField  = $this->workflowfield->getByField($module, $reportField);
                            if(!empty($flowField) and $flowField->buildin == 0 and isset($flowField->options))
                            {
                                $headerNames[$i] = $this->workflowfield->getFieldOptions($flowField);
                                continue;
                            }
                        }
                    }
                }
            }
        }

        $sqlLangs   = json_decode($this->session->sqlLangs, true);
        $clientLang = $this->app->getClientLang();
        foreach($headerNames as $fieldName => $fieldValue)
        {
            if(isset($sqlLangs[$fieldName][$clientLang])) $headerNames[$fieldName] = $sqlLangs[$fieldName][$clientLang];
        }
        return $headerNames;
    }

    /**
     * Get group lang.
     *
     * @param  string $field
     * @param  array  $sqlFields
     * @param  array  $moduleNames
     * @access public
     * @return array|string
     */
    public function getGroupLang($field, $sqlFields, $moduleNames)
    {
        if(isset($sqlFields[$field]) and strrpos($sqlFields[$field], '.') !== false)
        {
            $sqlField  = $sqlFields[$field];
            $table     = substr($sqlField, 0, strrpos($sqlField, '.'));
            $fieldList = substr($sqlField, strrpos($sqlField, '.') + 1) . 'List';

            if(isset($moduleNames[$table]))
            {
                $moduleName = $moduleNames[$table];
                return isset($this->lang->$moduleName->$fieldList) ? $this->lang->$moduleName->$fieldList : array();
            }
        }

        $fieldList  = $field . 'List';
        if(strpos(join(',', $sqlFields), '.*') !== false)
        {
            /* Such as $sqlFields['zt_task.*'] = zt_task.*. */
            $existField = false;
            foreach($sqlFields as $sqlField)
            {
                if(strrpos($sqlField, '.*') !== false)
                {
                    $table = substr($sqlField, 0, strrpos($sqlField, '.'));
                    if(isset($moduleNames[$table]))
                    {
                        $moduleName = $moduleNames[$table];
                        $groupLang = isset($this->lang->$moduleName->$fieldList) ? $this->lang->$moduleName->$fieldList : array();
                        $existField = true;
                        break;
                    }
                }
            }
            if($existField) return $groupLang;
        }

        foreach($moduleNames as $table => $moduleName)
        {
            if(isset($this->lang->$moduleName) and isset($this->lang->$moduleName->$fieldList)) return $this->lang->$moduleName->$fieldList;
        }
        return array();
    }

    /**
     * replace defined table names.
     *
     * @param  string $sql
     * @access public
     * @return string
     */
    public function replaceTableNames($sql)
    {
        if(preg_match_all("/TABLE_[A-Z]+/", $sql, $out))
        {
            rsort($out[0]);
            foreach($out[0] as $table)
            {
                if(!defined($table)) continue;
                $sql = str_replace($table, trim(constant($table), '`'), $sql);
            }
        }
        $sql = preg_replace("/= *'\!/U", "!='", $sql);
        return $sql;
    }

    /**
     * Process sql.
     *
     * @param  string $sqlVar
     * @param  string $requestType
     * @access public
     * @return string
     */
    public function processSqlVar($sqlVar, $requestType = '')
    {
        if(empty($sqlVar)) return $sqlVar;

        $format = $requestType == 'datetime' ? 'Y-m-d H:i:s' : 'Y-m-d';
        switch($sqlVar)
        {
        case '$MONDAY':     $sqlVar = date($format, time() - (date('N') - 1) * 24 * 3600); break;
        case '$SUNDAY':     $sqlVar = date($format, time() + (7 - date('N')) * 24 * 3600); break;
        case '$MONTHBEGIN': $sqlVar = date($format, time() - (date('j') - 1) * 24 * 3600); break;
        case '$MONTHEND':   $sqlVar = date($format, time() + (date('t') - date('j')) * 24 * 3600); break;
        }
        return $sqlVar;
    }

    /**
     * Process data.
     *
     * @param  array  $dataList
     * @param  array  $condition
     * @access public
     * @return array
     */
    public function processData($dataList, $condition)
    {
        $processData   = array();
        $headers       = array();
        $crystalTotals = array();

        foreach($dataList as $data)
        {
            $group1  = $data->{$condition['group1']};
            if(!isset($processData[$group1])) $processData[$group1] = array();
            $group2  = $condition['group2'] ? $data->{$condition['group2']} : '';
            if($condition['group2'] and !isset($processData[$group1][$group2])) $processData[$group1][$group2] = array();

            $reportData = $condition['group2'] ? $processData[$group1][$group2] : $processData[$group1];
            foreach($condition['reportField'] as $i => $reportField)
            {
                $fieldName = $condition['reportType'][$i] == 'sum' ? ($condition['sumAppend'][$i] == $condition['reportField'][$i] ? $reportField : $data->$reportField) : $data->$reportField;
                if($condition['reportType'][$i] == 'count')
                {
                    if(!isset($reportData[$i][$fieldName])) $reportData[$i][$fieldName] = 0;
                    $reportData[$i][$fieldName] += 1;
                }
                elseif($condition['reportType'][$i] == 'sum')
                {
                    if(!isset($reportData[$i][$fieldName]))
                    {
                        $reportData[$i][$fieldName] = $data->{$condition['sumAppend'][$i]};
                    }
                    else
                    {
                        $reportData[$i][$fieldName] += $data->{$condition['sumAppend'][$i]};
                    }
                }
                $headers[$i][$fieldName] = $fieldName;
            }

            if(isset($condition['percent']))
            {
                foreach($condition['percent'] as $i => $percent)
                {
                    $crystalTotals[$i] = isset($crystalTotals[$i]) ? $crystalTotals[$i] : 0;
                    $contrastField     = $condition['contrast'][$i];
                    if(isset($headers[$i][$contrastField])) continue;
                    if(!isset($reportData[$i][$contrastField])) $reportData[$i][$contrastField] = 0;
                    if($contrastField != 'crystalTotal') $reportData[$i][$contrastField] += $condition['reportType'][$i] == 'sum' ? $data->{$contrastField} : 1;
                    if($contrastField == 'crystalTotal' and  $condition['reportType'][$i] == 'sum') $crystalTotals[$i] += $data->{$condition['sumAppend'][$i]};
                }
            }

            if($condition['group2'])
            {
                $processData[$group1][$group2] = $reportData;
            }
            else
            {
                $processData[$group1] = $reportData;
            }
        }

        $crystalTotals = array_filter($crystalTotals);
        if(!empty($crystalTotals))
        {
            foreach($processData as $i => $group1)
            {
                foreach($group1 as $j => $group2)
                {
                    if(isset($group2['crystalTotal']))
                    {
                        $processData[$i][$j]['crystalTotal'] = $crystalTotals[$j];
                    }
                    else
                    {
                        foreach($group2 as $k => $item) $processData[$i][$j][$k]['crystalTotal'] = $crystalTotals[$j];
                    }
                }
            }
        }
        return array(0 => $headers, '1' => $processData, 'headers' => $headers, 'reportData' => $processData);
    }

    /**
     * Replace title for workflow.
     *
     * @param  string $title
     * @access public
     * @return string
     */
    public function replace4Workflow($title)
    {
        $clientLang = $this->app->getClientLang();
        $productCommonList   = isset($this->config->productCommonList[$clientLang]) ? $this->config->productCommonList[$clientLang] : $this->config->productCommonList['en'];
        $projectCommonList = isset($this->config->projectCommonList[$clientLang]) ? $this->config->projectCommonList[$clientLang] : $this->config->projectCommonList['en'];
        $productCommon = $productCommonList[0];
        $projectCommon = $projectCommonList[0];
        if(strpos($title, strtolower($productCommon)) !== false) $title = str_replace(strtolower($productCommon), strtolower($this->lang->productCommon), $title);
        if(strpos($title, $productCommon) !== false)             $title = str_replace($productCommon, $this->lang->productCommon, $title);
        return $title;
    }
}
