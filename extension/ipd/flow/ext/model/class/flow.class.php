<?php
class flowFlow extends flowModel
{
    /**
     * Post data of a flow.
     *
     * @param  object $flow
     * @param  object $action
     * @param  int    $dataID
     * @param  string $prevModule
     * @access public
     * @return array
     */
    public function post($flow, $action, $dataID = 0, $prevModule = '')
    {
        $result = parent::post($flow, $action, $dataID, $prevModule);
        if(!isset($result['result']) || $result['result'] != 'success') return $result;

        if($action->open == 'modal' && helper::isAjaxRequest())
        {
            $result['load'] = true;
            $result['closeModal'] = true;
        }
        elseif($flow->buildin == 1)
        {
            if($dataID > 0)
            {
                $result['load'] = helper::createLink($flow->module, 'view', "id={$dataID}");
            }
            elseif($flow->module == 'story' or $flow->module == 'task')
            {
                $result['load'] = $flow->module == 'story' ? helper::createLink('product', 'browse') : helper::createLink('project', 'browse');
            }
        }
        return $result;
    }

    /**
     * Print workflow defined fields for view and form page.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  object $object       bug | build | feedback | product | productplan | project | release | story | task | testcase | testsuite | testtask
     * @param  string $type         The parent component which fileds displayed in. It should be table or div.
     * @param  string $extras       The extra params.
     *                              columns=1|2|3|5         Number of the columns merged to display the fields. The default is 1.
     *                              position=left|right     The position which the fields displayed in a page.
     *                              inForm=0|1              The fields displayed in a form or not. The default is 1.
     *                              inCell=0|1              The fields displayed in a div with class cell or not. The default is 0.
     * @access public
     * @return string
     */
    public function printFields($moduleName, $methodName, $object, $type, $extras = '')
    {
        $groupID = $this->loadModel('workflowgroup')->getGroupIDByData($moduleName, $object);
        $action  = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName, $groupID);
        if(empty($action) or $action->extensionType == 'none') return null;

        parse_str(str_replace(array(',', ' '), array('&', ''), $extras), $params);

        $html    = '';
        $uiID    = empty($object) ? 0 : $this->loadModel('workflowlayout')->getUIByData($moduleName, $methodName, $object);
        $flow    = $this->loadModel('workflow')->getByModule($moduleName);
        $fields  = $this->workflowaction->getFields($moduleName, $methodName, true, $object, $uiID, $groupID);
        $layouts = $this->loadModel('workflowlayout')->getFields($moduleName, $methodName, $uiID, $groupID);
        static $loadedResource = array();
        if(empty($loadedResource[$moduleName][$methodName]['css']))
        {
            if(!empty($flow->css))   $html .= "<style>$flow->css</style>";
            if(!empty($action->css)) $html .= "<style>$action->css</style>";

            $loadedResource[$moduleName][$methodName]['css'] = true;
        }

        if($layouts)
        {
            $moreLinks = $this->config->moreLinks;
            foreach($this->config->moreLinks as $fieldName => $moreLink)
            {
                if(isset($layouts[$fieldName])) continue;
                if(isset($moreLinks[$fieldName]))
                {
                    $this->config->moreLinks[$fieldName] = $moreLinks[$fieldName];
                    continue;
                }

                unset($this->config->moreLinks[$fieldName]);
            }

            $allFields = $this->dao->select('`default`,`field`')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($moduleName)->fetchAll('field');
            foreach($fields as $fieldName => $field)
            {
                if(isset($allFields[$fieldName])) $field->default = $allFields[$fieldName]->default;
            }
            $function = "printFieldsIn" . $type;
            $html    .= $this->$function($object, $layouts, $fields, $params);
        }

        if(empty($loadedResource[$moduleName][$methodName]['js']))
        {
            $html .= $this->getCategoryScript($moduleName);
            $html .= $this->getFormulaScript($moduleName, $action, $fields);
            if($action->linkages)   $html .= $this->getLinkageScript($action, $fields, $uiID);
            if(!empty($flow->js))   $html .= "<script>$(document).ready(function(){" . $flow->js . "});</script>";
            if(!empty($action->js)) $html .= "<script>$(document).ready(function(){" . $action->js . "});</script>";

            $loadedResource[$moduleName][$methodName]['js'] = true;
        }

        echo htmlspecialchars_decode($html);
    }

    /**
     * Print fields in table.
     *
     * @param  object   $object
     * @param  array    $layouts
     * @param  array    $fields
     * @param  array    $params
     * @access public
     * @return string
     */
    public function printFieldsInTable($object, $layouts, $fields, $params = '')
    {
        $html    = '';
        $columns = zget($params, 'columns', 1);
        $inForm  = zget($params, 'inForm', 1);
        $colspan = $columns > 1 ? "colspan='$columns'" : '';

        if(!is_object($object)) $object = (object)$object;
        if(!$object) $object = new stdclass();

        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

        foreach($fields as $field)
        {
            if($field->buildin or !$field->show or !isset($layouts[$field->field])) continue;

            if((is_numeric($field->default) or $field->default) and empty($field->defaultValue)) $field->defaultValue = $field->default;
            if(empty($object->{$field->field})) $object->{$field->field} = $field->defaultValue;

            $require = '';
            if($inForm && !$field->readonly && $notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false) $require = "class='required'";

            if(($field->control == 'textarea' or $field->control == 'richtext') and empty($colspan) and $inForm) $colspan = "colspan='2'";

            $content = $inForm ? $this->getFieldControl($field, $object) : $this->getFieldValue($field, $object);
            $html .= "<tr class='field-row'><th>{$field->name}</th>";
            $html .= "<td $colspan $require>{$content}</td></tr>";
        }

        return $html;
    }

    /**
     * Print fields in div.
     *
     * @param  object $object
     * @param  array  $layouts
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return string
     */
    public function printFieldsInDiv($object, $layouts, $fields, $params = '')
    {
        $html     = '';
        $position = zget($params, 'position', 'right');
        $inCell   = zget($params, 'inCell', 0);
        $inForm   = zget($params, 'inForm', 1);

        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

        if($position == 'right')
        {
            if($inCell) $html .= "<div class='cell'>";

            $html .= "<div class='detail'>";
            $html .= "<div class='detail-title'>{$this->lang->extInfo}</div>";
            $html .= $inCell ? "<table class='table table-data'>" : "<table class='table table-form'>";

            $tableContent = '';
            foreach($fields as $field)
            {
                if($field->buildin or !$field->show or !isset($layouts[$field->field]) or $field->position != 'basic') continue;

                $require = '';
                if($inForm && !$field->readonly && $notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false) $require = "class='required'";

                $content = $inForm ? $this->getFieldControl($field, $object) : $this->getFieldValue($field, $object);

                $tableContent .= "<tr class='field-row'><th class='thWidth'>{$field->name}</th>";
                $tableContent .= "<td $require>{$content}</td></tr>";
            }

            if(!$tableContent) return false;

            $html .= $tableContent;
            $html .= '</table>';
            $html .= '</div>';

            if($inCell) $html .= '</div>';
        }

        if($position == 'left')
        {
            foreach($fields as $field)
            {
                if($field->buildin or !$field->show or !isset($layouts[$field->field]) or $field->position != 'info') continue;

                $require = '';
                if($inForm && !$field->readonly && $notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false) $require = "required";

                $content = $inForm ? $this->getFieldControl($field, $object) : $this->getFieldValue($field, $object);

                if($inCell) $html .= "<div class='cell'>";

                $html .= "<div class='detail field-row'>";
                $html .= "<div class='detail-title'>{$field->name}</div>";
                $html .= "<div class='detail-content $require'>{$content}</div>";
                $html .= '</div>';

                if($inCell) $html .= '</div>';
            }
        }

        return $html;
    }

    /**
     * Get control of a field.
     *
     * @param  object $field
     * @param  object $object
     * @param  string $controlName
     * @param  string $chosen
     * @access public
     * @return string
     */
    public function getFieldControl($field, $object, $controlName = '', $picker = 'picker-select')
    {
        $this->loadModel('workflowfield');

        /* If field type is number, set empty value with key as 0. */
        $extendField = clone $field;
        if(in_array($extendField->type, $this->config->workflowfield->numberTypes)) $extendField = $this->workflowfield->processNumberField($extendField);

        $control      = '';
        $readonly     = $extendField->readonly ? 'disabled' : '';
        $defaultValue = !empty($extendField->defaultValue) ? $extendField->defaultValue : $extendField->default;
        $value        = !empty($object) ? zget($object, $extendField->field, '') : $defaultValue;
        $data         = "data-module='$extendField->module' data-field='$extendField->field' data-value='$value'";
        $width        = $extendField->width != 'auto' ? "style='width:{$extendField->width}px'" : '';
        if(!$controlName) $controlName = $extendField->field;

        if(in_array($value, $this->config->flow->variables)) $value = $this->loadModel('workflowhook')->getParamRealValue($value, 'control');
        if($extendField->control == 'date') $value = substr($value, 0, 10);

        if($extendField->control == 'checkbox' or $extendField->control == 'radio' and isset($extendField->options[''])) unset($extendField->options['']);

        if($extendField->control == 'select')
        {
            list($prefix, $suffix) = $this->getCategoryButtons($extendField);

            if($field->field == 'subStatus') $prefix .= $this->getSubStatusScript($field);
            return $prefix . html::select($controlName, $extendField->options, $value, "class='form-control chosen' $data $readonly") . $suffix;
        }

        if($extendField->control == 'multi-select')
        {
            list($prefix, $suffix) = $this->getCategoryButtons($extendField);

            return $prefix . html::select($controlName . '[]', $extendField->options, $value, "class='form-control chosen' multiple $data $readonly") . $suffix;
        }

        if($extendField->control == 'checkbox') return html::checkbox($controlName, $extendField->options, $value, "class='form-control' $readonly");
        if($extendField->control == 'radio')    return html::radio($controlName, $extendField->options, $value, "$readonly");
        if($extendField->control == 'time')     return html::input($controlName, formatTime($value, DT_DATETIME2), "class='form-control form-time' $readonly $width");

        return parent::getFieldControl($field, $object, $controlName, $picker);
    }

    /**
     * Get buttons to configure category and refresh category options.
     *
     * @param  object $field
     * @access public
     * @return string
     */
    public function getCategoryButtons($field)
    {
        $prefix = '';
        $suffix = '';

        global $categoryFields;
        if(!isset($categoryFields[$field->module]))
        {
            $categoryFields[$field->module] = $this->dao->select('field')->from(TABLE_WORKFLOWFIELD)
                ->where('module')->eq($field->module)
                ->andWhere('options')->eq('category')
                ->fetchPairs();
        }

        if(isset($categoryFields[$field->module][$field->field]))
        {
            $prefix .= "<div class='input-group'>";
            if(commonModel::hasPriv('tree', 'browse'))
            {
                $link    = helper::createLink('tree', 'browse', "rootID=0&type={$field->module}_{$field->field}&currentModuleID=0&branch=&from=workflow", '', true);
                $suffix .= "<div class='input-group-btn'><a class='btn btn-icon' href='{$link}' data-toggle='modal' data-type='iframe' data-width='90%'><i class='icon icon-cog'></i></a></div>";
            }
            $suffix .= "<div class='input-group-btn'><a class='btn btn-icon' href='javascript:;' onclick='refresh{$field->module}Category(this)'><i class='icon icon-refresh'></i></a></div>";
            $suffix .= "</div>";
        }

        return array($prefix, $suffix);
    }

    /**
     * Get java script to refresh category.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getCategoryScript($module)
    {
        global $categoryFields;
        if(empty($categoryFields[$module])) return '';

        return <<<EOT
<script>
function refresh{$module}Category(selector)
{
    const \$category = \$(selector).closest('.input-group').find('select');
    const selected = \$category.val();
    const module = \$category.data('module');
    const field = \$category.data('field');
    const url = createLink('flow', 'ajaxGetPairs', 'module=' + module + '&field=' + field);
    $.getJSON(url, function(categories){
        \$category.empty();
        categories.forEach(category => \$category.append(\$('<option>').attr('value', category.value).html(category.text)));
        \$category.val(selected).trigger('chosen:updated');
    });
}
</script>
EOT;
    }

    /**
     * Get value string of one field.
     *
     * @param  object    $field
     * @param  object    $object
     * @access public
     * @return string
     */
    public function getFieldValue($field, $object)
    {
        if(!isset($object->{$field->field}) and $field->field == 'actions') return '';
        $fieldControl = is_array($field->control) && isset($field->control['control']) ? $field->control['control'] : $field->control;
        if($fieldControl == 'richtext' or $fieldControl == 'textarea' or $fieldControl == 'editor')
        {
            $object = $this->loadModel('file')->replaceImgURL($object, $field->field);
            return $object->{$field->field};
        }
        if($fieldControl == 'file')
        {
            $files = $this->loadModel('file')->getByObject($field->module, $object->id, $field->field);
            $fileItems = "<ul class='files-list'>";
            foreach($files as $file) $fileItems .= $this->file->printFile($file, 'view', common::hasPriv('file', 'edit'), common::hasPriv('file', 'delete'), $object);
            $fileItems .= '</ul>';

            /* method/objectType/showDelete printfile 文件内使用 */
            $method     = $this->app->rawMethod;
            $objectType = $field->module;
            $showDelete = common::hasPriv('file', 'delete') && common::hasPriv($objectType, 'edit');
            ob_start();
            include $this->app->getExtensionRoot() . $this->config->edition . '/flow/ui/printfile.html.php';
            $fileItems .= ob_get_contents();
            ob_end_clean();

            return $fileItems;
        }
        if(strpos($fieldControl, 'date') !== false && isset($object->{$field->field}) && helper::isZeroDate($object->{$field->field}))
        {
            return '';
        }

        if($fieldControl != 'checkbox' && $fieldControl != 'multi-select' && empty($field->control['multiple']))
        {
            $optionKey = zget($object, $field->field, '');
            return zget($field->options, $optionKey, $optionKey);
        }

        $content = '';
        $values  = json_decode($object->{$field->field}, true);
        if(empty($values)) $values = explode(',', str_replace(' ', '', $object->{$field->field}));
        if(!is_array($values)) $values = array($values);
        foreach($values as $value) $content .= ' ' . zget($field->options, $value, $value);

        return $content;
    }

    /**
     * Print workflow defined fields from browse page.
     *
     * @param string $module
     * @param object $object
     * @param string $fieldCode
     * @param bool   $returnResult  true|false
     * @access public
     * @return string|int
     */
    public function printFlowCell($module, $object, $fieldCode, $returnResult = false)
    {
        static $fields  = array();
        static $options = array();

        $fieldKey = $module . '_' . $fieldCode;

        if(isset($fields[$fieldKey]))
        {
            $field = $fields[$fieldKey];
        }
        else
        {
            $field = $this->loadModel('workflowfield')->getByField($module, $fieldCode);

            $fields[$fieldKey] = $field;
        }

        if(isset($field->buildin) && $field->buildin == 0)
        {
            if(strpos('select,radio,checkbox,multi-select', $field->control) === false)
            {
                if(isset($object->{$field->field}) && ($field->control == 'date' || $field->control == 'datetime') && helper::isZeroDate($object->{$field->field}))
                {
                    return $returnResult ? '' : print('');
                }
                else
                {
                    return $returnResult ? $object->{$field->field} : print($object->{$field->field});
                }
            }
            else
            {
                if(isset($options[$fieldKey]))
                {
                    $field->options = $options[$fieldKey];
                }
                else
                {
                    $field->options = $this->loadModel('workflowfield')->getFieldOptions($field);

                    $options[$fieldKey] = $field->options;
                }

                if($field->control == 'multi-select' or $field->control == 'checkbox')
                {
                    $result = array_reduce(explode(',', $object->{$field->field}), function($carry, $item) use ($field)
                    {
                        return $carry . zget($field->options, $item) . ' ';
                    }, '');
                    return $returnResult ? $result : print($result);
                }
                else
                {
                    return $returnResult ? zget($field->options, $object->{$field->field}) : print(zget($field->options, $object->{$field->field}));
                }
            }
        }
    }

    /**
     * Import from excel
     *
     * @param  object $flow
     * @access public
     * @return array
     */
    public function import($flow)
    {
        $this->loadModel('action');

        $errorList  = array();
        $recordList = array();
        $actionList = array();
        $dataList   = $this->post->dataList;
        $dataList   = $this->deleteEmpty($dataList);

        $this->loadModel('workflowrule');
        $fields = $this->loadModel('workflowfield')->getExportFields($flow->module);
        $rules  = array();
        foreach($fields as $field => $fieldName)
        {
            $fields[$field] = $this->workflowfield->getByField($flow->module, $field);

            if(empty($fields[$field]->rules)) continue;

            $fieldRules = explode(',', trim($fields[$field]->rules, ','));
            $fieldRules = array_unique($fieldRules);
            foreach($fieldRules as $ruleID)
            {
                if(empty($ruleID)) continue;

                $rule = $this->workflowrule->getByID($ruleID);
                if(empty($rule)) continue;

                $rules[$ruleID] = $rule;
            }
        }

        /* Check rules. */
        foreach($dataList as $i => $data)
        {
            foreach($fields as $fieldKey => $field)
            {
                if(isset($data[$fieldKey]) and is_array($data[$fieldKey])) $data[$fieldKey] = join(',', $data[$fieldKey]);
                if(!isset($data[$fieldKey]) and strpos(',radio,checkbox,multi-select,', ",$field->control,") !== false) $data[$fieldKey] = '';

                if(empty($field->rules)) continue;

                $fieldRules = explode(',', trim($field->rules, ','));
                $fieldRules = array_unique($fieldRules);
                foreach($fieldRules as $ruleID)
                {
                    $rule = $rules[$ruleID];
                    if($rule->type == 'system')
                    {
                        $functionName = 'check' . $rule->rule;
                        if(!validater::$functionName($data[$fieldKey]))
                        {
                            $this->dao->logError($rule->rule, $fieldKey, $field->name);
                            foreach(dao::$errors[$fieldKey] as $error)
                            {
                                $errorKey = 'dataList' . $i . $fieldKey;
                                if(!isset(dao::$errors[$errorKey])) dao::$errors[$errorKey] = '';
                                dao::$errors[$errorKey] .= $error;
                            }
                            unset(dao::$errors[$fieldKey]);
                        }
                    }
                    elseif($rule->type == 'regex' and !validater::checkREG($data[$fieldKey], $rule->rule))
                    {
                        $errorKey = 'dataList' . $i . $fieldKey;
                        dao::$errors[$errorKey] = sprintf($this->lang->error->reg, $field->name, $rule->rule);
                    }
                }
            }

            $dataList[$i] = $data;
        }
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $subTables = $this->dao->select('module, `table`')->from(TABLE_WORKFLOW)
            ->where('type')->eq('table')
            ->andWhere('parent')->eq($flow->module)
            ->fetchPairs();

        /* 导入数据。*/
        foreach($dataList as $key => $data)
        {
            if(!empty($this->post->dataList[$key]['id']) and empty($_POST['insert']))
            {
                $this->dao->update($flow->table)->data($data, 'sub_tables')->where('id')->eq($this->post->dataList[$key]['id'])->exec();

                $dataID   = $this->post->dataList[$key]['id'];
                $actionID = $this->action->create($flow->module, $dataID, 'edited');
            }
            else
            {

                $data['createdBy']   = $this->app->user->account;
                $data['createdDate'] = helper::now();

                /* 导入主流程数据。*/
                $this->dao->insert($flow->table)->data($data, 'sub_tables')->autoCheck()->exec();

                if(dao::isError())
                {
                    $daoErrors = dao::getError();
                    if(is_string($daoErrors)) $errorList['error' . $key] = $daoErrors;
                    if(is_array($daoErrors))
                    {
                        foreach($daoErrors as $field => $message)
                        {
                            /* Set error key. */
                            $errorKey = '';
                            $errorKey = 'dataList' . $key . $field;

                            $errorList[$errorKey] = $message;
                        }
                    }

                    break;
                }

                $dataID   = $this->dao->lastInsertId();
                $actionID = $this->action->create($flow->module, $dataID, 'import');
            }

            $recordList[] = $dataID;
            $actionList[] = $actionID;

            /* 导入明细表数据。*/
            if(isset($data['sub_tables']))
            {
                foreach($data['sub_tables'] as $subModule => $subDatas)
                {
                    if(!isset($subTables[$subModule])) continue;

                    $subTable = $subTables[$subModule];

                    foreach($subDatas as $subKey => $subData)
                    {
                        $subData['parent']      = $dataID;
                        $subData['createdBy']   = $this->app->user->account;
                        $subData['createdDate'] = helper::now();

                        $this->dao->insert($subTable)->data($subData)->autoCheck()->exec();

                        if(dao::isError())
                        {
                            $daoErrors = dao::getError();
                            if(is_string($daoErrors)) $errorList['error' . $key] = $daoErrors;
                            if(is_array($daoErrors))
                            {
                                foreach($daoErrors as $field => $message)
                                {
                                    /* Set error key. */
                                    $errorKey = '';
                                    $errorKey = 'dataList' . $key . 'sub_tables' . $subTable->module . $subKey . $field;

                                    $errorList[$errorKey] = $message;
                                }
                            }
                            break;
                        }
                    }

                    if(dao::isError()) break;
                }
            }

            if(dao::isError()) break;
        }

        /* 如果存在错误则把已导入的数据删除并返回错误信息。*/
        if($errorList)
        {
            $this->dao->delete()->from($flow->table)->where('id')->in($recordList)->exec();
            $this->dao->delete()->from(TABLE_ACTION)->where('id')->in($actionList)->exec();

            foreach($subTables as $subTable)
            {
                $this->dao->delete()->from($subTable)->where('parent')->in($recordList)->exec();
            }

            return array('result' => 'fail', 'message' => $errorList);
        }

        $locate = helper::createLink($flow->module, 'browse');
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'recordList' => $recordList, 'actionList' => $actionList, 'locate' => $locate);
    }

    public function buildControl($field, $fieldValue, $element = '', $childModule = '', $emptyValue = false, $preview = false)
    {
        if(!empty($fieldValue))
        {
            if($fieldValue == 'currentTime') $fieldValue = date('Y-m-d H:i:s');
            if($field->control == 'date' and !helper::isZeroDate($fieldValue))     $fieldValue = date('Y-m-d', strtotime($fieldValue));
            if($field->control == 'datetime' and !helper::isZeroDate($fieldValue)) $fieldValue = date('Y-m-d H:i:s', strtotime($fieldValue));

            if($field->control == 'select' or $field->control == 'multi-select' or $field->control == 'checkbox' or $field->control == 'radio')
            {
                if(is_array($fieldValue)) $fieldValue = join(',', $fieldValue);

                $decodedFieldValue = json_decode($fieldValue);
                if(empty($decodedFieldValue)) $decodedFieldValue = explode(',', $fieldValue);
                if(!is_array($decodedFieldValue)) $decodedFieldValue = array($decodedFieldValue);
                $fieldValue = $decodedFieldValue;

                $options = $this->loadModel('workflowfield')->getFieldOptions($field);
                foreach($fieldValue as $i => $value)
                {
                    $fieldKey = array_search($value, $options);
                    if($fieldKey) $fieldValue[$i] = $fieldKey;
                }

                $fieldValue = join(',', $fieldValue);
            }
        }
        if($field->control == 'multi-select' and $element) $element .= '[]';

        return parent::buildControl($field, $fieldValue, $element, $childModule, $emptyValue, $preview);
    }

    public function getDataByID($flow, $dataID, $decode = true)
    {
        $data = parent::getDataByID($flow, $dataID, $decode);
        if(!$decode) return $data;

        $table  = $flow->table;
        $fields = $this->loadModel('workflowfield')->getList($flow->module);

        $tableData = $this->dao->select('*')->from($table)->where('id')->eq($dataID)->fetch();
        if($tableData)
        {
            foreach($fields as $field)
            {
                $fieldControl = $field->control;
                $fieldName    = $field->field;
                if($decode and ($fieldControl == 'multi-select' or $fieldControl == 'checkbox') and $tableData->$fieldName and empty($data->$fieldName))
                {
                    $data->$fieldName = explode(',', str_replace(' ', '', $tableData->$fieldName));
                }
            }
        }

        return $data;
    }

    /**
     * Build operate menu.
     *
     * @param  object $flow
     * @param  object $data
     * @param  string $type     menu 菜单栏 | browse 仅列表页 | view 仅详情页 | browseandview 列表页和详情页同时显示
     * @access public
     * @return string
     */
    public function buildOperateMenu($flow, $data, $type = 'browse')
    {
        if($type != 'menu' && zget($data, 'deleted', '0') == '1' || !$flow) return '';

        $this->loadModel('workflow');
        $this->loadModel('workflowaction');
        $this->loadModel('workflowfield');
        $this->loadModel('workflowrelation');

        $isMobile   = $this->app->viewType === 'mhtml';
        $relations  = $this->workflowrelation->getList($flow->module);

        $btn          = $type == 'menu' ? 'btn btn-primary' : ($type == 'view' ? 'btn' : '');
        $menu         = ($type == 'view' && !$isMobile && !$flow->buildin) ? "<div class='main-actions'><div class='btn-toolbar'>" : '';
        $dropdownMenu = '';

        if($type == 'view' && $flow->buildin) $menu .= "<div class='divider'></div>";

        if($type == 'view' && !$isMobile && !$flow->buildin)
        {
            $menu .= $this->session->flowList ? baseHTML::a($this->session->flowList, $this->lang->goback, "class='btn btn-back'") : html::backButton('', '', 'btn');
            $menu .= "<div class='divider'></div>";
        }

        $relations = $this->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($flow->module)->fetchPairs();
        $actions   = $this->workflowaction->getList($flow->module);
        foreach($actions as $action)
        {
            $flowMenu = $this->buildActionMenu($flow->module, $action, $data, $type, $relations);

            if($type == 'browse' && $action->show == 'dropdownlist')
            {
                $dropdownMenu .= $flowMenu;
            }
            else
            {
                $menu .= $flowMenu;
            }
        }

        if($type == 'view' && $flow->approval == 'enabled' && commonModel::hasPriv('approval', 'progress') && !empty($this->config->openedApproval) && !empty($data->approval))
        {
            $menu .= "<div class='divider'></div>";
            $menu .= baseHTML::a(helper::createLink('approval', 'progress', "approvalID={$data->approval}"), $this->lang->flow->approvalProgress, "class='btn' data-toggle='modal' data-type='iframe'");
        }

        if($type == 'browse' && $dropdownMenu != '')
        {
            $dropdownBefore  = "<div class='dropdown'><a href='javascript:;' data-toggle='dropdown'>{$this->lang->more}<span class='caret'> </span></a>";
            $dropdownBefore .= "<ul class='dropdown-menu pull-right'>";
            $dropdownMenu    = $dropdownBefore . $dropdownMenu . "</ul></div>";
        }

        $menu .= $dropdownMenu;

        if($type == 'view' && !$isMobile && !$flow->buildin) $menu .= '</div></div>';

        return $menu;
    }

    /**
     * Build menu of an anction.
     *
     * @param  string $moduleName
     * @param  object $action
     * @param  object $data
     * @param  string $type
     * @param  array  $relations
     * @access public
     * @return string
     */
    public function buildActionMenu($moduleName, $action, $data, $type = 'browse', $relations = array())
    {
        if($action->status != 'enable') return '';
        if($action->method == 'browse') return '';
        if($action->method == 'export') return '';
        if($action->method == 'exporttemplate') return '';
        if($action->method == 'import') return '';
        if($action->method == 'showimport') return '';
        if($action->method == 'link') return '';
        if($action->method == 'unlink') return '';
        if($action->method == 'report') return '';
        if($type == 'menu' && $action->open == 'none') return '';
        if($type == 'menu' && $action->method == 'delete') return '';
        if($type == 'menu' && $action->type == 'batch' && $action->method != 'batchcreate') return '';
        if($type != 'menu' && $action->type == 'batch' && $action->virtual != '1') return '';
        if(strpos($action->position, $type) === false) return '';

        if($action->action == 'approvalsubmit' && isset($data->reviewStatus) && !in_array($data->reviewStatus, array('', 'wait', 'reject', 'reverting'))) return '';
        if($action->action == 'approvalreview' && !$this->loadModel('approval')->canApproval($data)) return '';
        if($action->action == 'approvalcancel' && !$this->loadModel('approval')->canCancel($data))   return '';

        $methodName = $action->action;

        if(strpos($moduleName, '.') !== false) list($appName, $moduleName) = explode('.', $moduleName);
        if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($module, $method) = explode('_', $methodName);

        if(empty($module)) $module = $moduleName;
        if(empty($method)) $method = $methodName;

        /* Filter the relation actions. */
        if($action->virtual == '1')
        {
            if(!isset($relations[$module])) return '';

            $actions = $relations[$module];

            if($method == 'create' && strpos(",{$actions},", ",one2one,") === false) return '';
            if($method == 'batchcreate' && strpos(",{$actions},", ",one2many,") === false) return '';
        }

        if(!commonModel::hasPriv($module, $method)) return '';

        $enabled = true;
        if($type != 'menu' && ($action->extensionType != 'none' or !$action->buildin)) $enabled = $this->checkConditions($action->conditions, $data);

        if($enabled)
        {
            $dataID = isset($data->id) ? $data->id : 0;
            $icon   = "<i class='icon-cogs'> </i>";
            $params = "dataID={$dataID}";
            if($action->method == 'create' or $action->method == 'batchcreate')
            {
                $icon   = "<i class='icon-plus'> </i>";
                $params = '';
            }
            if($action->method == 'edit') $icon = "<i class='icon-pencil'> </i>";
            if($action->virtual) $params = "step=form&prevModule={$moduleName}&prevDataID={$dataID}";

            $label    = $type == 'menu' ? $icon . $action->name : $action->name;
            $isMobile = $this->app->viewType === 'mhtml';
            if($isMobile)
            {
                $url = helper::createLink($module, $method, $params);
                if($action->method == 'delete') return "<a data-remote='$url' data-display='ajaxAction' data-ajax-delete='true' data-locate=''>{$label}</a>";
            }
            else
            {
                if(!isset($this->lang->$module)) $this->lang->$module = new stdclass();
                if(!isset($this->lang->$module->$method)) $this->lang->$module->$method = $label;

                $btn  = $type == 'menu' ? 'btn btn-primary' : ($type == 'view' ? 'btn btn-link' : '');
                $attr = ($action->open == 'modal') ? "data-toggle='modal'" : '';
                $link = baseHTML::a(helper::createLink($module, $method, $params), $label, "class='{$btn}' $attr data-size='lg'");

                if($type == 'browse' && $action->show == 'dropdownlist') return "<li>" . $link . "</li>";
                return $link;
            }
        }
        else
        {
            if($type == 'browse' and $action->show == 'direct') return baseHTML::a('javascript:;', $action->name, "class='disabled'");
        }

        return '';
    }

    /**
     * Create buildin module link
     *
     * @param  string $moduleName
     * @param  string $prevModule
     * @param  int    $prevDataID
     * @access public
     * @return string
     */
    public function createBuildinLink($moduleName, $prevModule, $prevDataID)
    {
        $params = '';
        $extras = "prevModule=$prevModule,prevDataID=$prevDataID";

        if($moduleName == 'story')    $params = "productID=0&branch=0&moduleID=0&storyID=0&projectID=0&bugID=0&planID=0&todoID=0&extra=$extras";
        if($moduleName == 'task')     $params = "projectID=0&storyID=0&moduleID=0&taskID=0&extras=$extras";
        if($moduleName == 'bug')      $params = "productID=0&branch=&extras=$extras";
        if($moduleName == 'testcase') $params = "productID=0&branch=&moduleID=0&from=&param=0&storyID=0&extras=$extras";
        if($moduleName == 'feedback') $params = "extras=$extras";

        return helper::createLink($moduleName, 'create', $params);
    }

    public function processDBData($module, $data, $decode = true)
    {
        static $fields = array();
        if(empty($fields[$module])) $fields[$module] = $this->loadModel('workflowfield')->getControlPairs($module);

        $editorFields = array();
        $this->loadModel('file');

        foreach($fields[$module] as $field => $control)
        {
            if($decode && $control == 'multi-select' or $control == 'checkbox')
            {
                $decodedValue = json_decode($data->{$field});
                if(empty($decodedValue))
                {
                    $data->$field = explode(',', $data->{$field});
                }
                else
                {
                    if(!is_array($decodedValue)) $data->$field = explode(',', $decodedValue);
                }
            }

            if($control == 'date' or $control == 'datetime' and isset($data->$field)) $data->$field = formatTime($data->$field);
            if($control == 'richtext') $editorFields[] = $field;
        }
        if($editorFields) $data = $this->file->replaceImgURL($data, implode(',', $editorFields));

        return $data;
    }

    /**
     * Set children of a flow.
     *
     * @param  string $module
     * @param  string $action
     * @param  array  $fields
     * @param  int    $dataID
     * @access public
     * @return void
     */
    public function setFlowChild($module, $action, $fields, $dataID = 0)
    {
        $this->loadModel('workflowaction');

        $uiID = 0;
        if($dataID)
        {
            $flow = $this->loadModel('workflow')->getByModule($module);
            $data = $this->getDataByID($flow, $dataID);
            $uiID = $this->loadModel('workflowlayout')->getUIByData($module, $action, $data);
        }

        $childFields  = array();
        $childDatas   = array();
        $childModules = $this->loadModel('workflow')->getList('table', '', $module);
        foreach($childModules as $childModule)
        {
            $key = 'sub_' . $childModule->module;

            if(isset($fields[$key]) && $fields[$key]->show)
            {
                $childData = $this->getDataList($childModule, '', 0, '', $dataID);

                $childFields[$key] = $this->workflowaction->getFields($childModule->module, $action, true, $childData, $uiID);
                $childDatas[$key]  = $childData;
            }
        }

        return array($childFields, $childDatas);
    }

    /**
     * Send mail.
     *
     * @param  object $flow
     * @param  object $method
     * @param  int    $dataID
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function sendmail($flow, $method, $dataID, $actionID)
    {
        /* Get action info. */
        $action          = $this->loadModel('action')->getByID($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Set toList and ccList. */
        $data   = $this->getDataByID($flow, $dataID);
        $users  = $this->loadModel('user')->getDeptPairs();
        $toList = $this->getToList($flow, $dataID, $method);
        $ccList = '';

        /* send notice if user is online and return failed accounts. */
        $toList = $this->action->sendNotice($actionID, $toList);
        $ccList = explode(',', trim($toList, ','));
        $toList = array_shift($ccList);
        $ccList = join(',', $ccList);

        $uiID   = empty($data) ? 0 : $this->loadModel('workflowlayout')->getUIByData($flow->module, $method->action, $data);
        $fields = $this->loadModel('workflowaction')->getFields($flow->module, $method->action, true, $data, $uiID);

        list($childFields, $childDatas) = $this->setFlowChild($flow->module, $method->action, $fields, $dataID);

        /* Create the email content. */
        $createdBy = zget($this->config->flow->defaultFields->createdBy, $flow->module, 'createdBy');
        $subject   = "{$flow->name}{$method->name}#{$data->id} " . ($createdBy ? zget($users, $data->$createdBy) : '');

        /* Get mail content. */
        $mailTitle  = $subject;
        $oldcwd     = getcwd();
        $modulePath = $this->app->getModulePath($appName = 'sys', 'flow');
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');

        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }

        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();

        chdir($oldcwd);

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent, $ccList);
        if($this->mail->isError()) error_log(join("\n", $this->mail->getError()));
    }

    /**
     * 获取发信的收件人列表。
     * Get toList.
     *
     * @param  object $flow
     * @param  int    $dataID
     * @param  object $method
     * @access public
     * @return string
     */
    public function getToList($flow, $dataID, $method = null)
    {
        $toList = '';
        $data   = $this->getDataByID($flow, $dataID);

        if(isset($data->assignedTo)) $toList .= $data->assignedTo . ',';
        if(isset($data->createdBy))  $toList .= $data->createdBy . ',';
        if(isset($data->mailto))     $toList .= is_array($data->mailto) ? implode(',', $data->mailto) . ',' : $data->mailto . ',';
        if(!empty($method) && !empty($method->toList)) $toList .= $method->toList;

        $toList = explode(',', trim($toList, ','));
        if($toList)
        {
            $this->loadModel('workflowhook');
            $userFields = $this->dao->select('field')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($flow->module)->andWhere('options')->eq('user')->fetchPairs();
            foreach($toList as $key => $toUser)
            {
                if(!$toUser) continue;

                if($toUser == 'deptManager')
                {
                    $toList[] = $this->workflowhook->getParamRealValue($toUser);
                }
                elseif(in_array($toUser, $userFields))
                {
                    if(is_array($data->{$toUser}))
                    {
                        $toList = array_merge($toList, $data->{$toUser});
                    }
                    else
                    {
                        $toList[] = $data->{$toUser};
                    }
                }
                else
                {
                    if(isset($users[$toUser])) $toList[] = $toUser;
                    if(isset($data->$toUser))
                    {
                        if(is_array($data->$toUser))
                        {
                            foreach($data->$toUser as $user) $toList[] = $user;
                        }
                        else
                        {
                            $toList[] = $data->$toUser;
                        }
                    }
                }
            }
        }

        if($toList)
        {
            $toList = array_unique($toList);
            $toList = implode(',', $toList);
        }

        return $toList;
    }

    /**
     * Check rules.
     *
     * @param  array  $fields
     * @param  object $data
     * @param  object $dao
     * @param  int    $dataID
     * @param  array  $notEmptyRule
     * @param  array  $ruleList
     * @access public
     * @return object
     */
    public function checkRules($fields, $data, $dao, $dataID = 0, $notEmptyRule = array(), $ruleList = array())
    {
        $tmpData = clone $data;

        foreach($fields as $field)
        {
            /* If the field don't show in view, don't check it. */
            if(empty($field->show) || empty($field->rules) || !empty($field->readonly)) continue;

            $rules = explode(',', trim($field->rules, ','));
            $rules = array_unique($rules);

            /* 如果对象有这个字段并且这个字段值为空并且不要求这个字段必填，不需要验证。*/
            /* If the object has this field and the value of this field is empty and this field is not required, no need to check.*/
            if(isset($data->{$field->field}) && empty($data->{$field->field}))
            {
                if($notEmptyRule && !in_array($notEmptyRule->id, $rules)) continue;
            }

            if(!isset($data->{$field->field})) $data->{$field->field} = false;

            /* Check rules of fields. */
            foreach($rules as $rule)
            {
                if(empty($ruleList[$rule])) continue;

                $rule = $ruleList[$rule];

                if($rule->type == 'system')
                {
                    if($rule->rule == 'unique')
                    {
                        if($dao->sqlobj->data->{$field->field} == '' ) continue;
                        $condition = isset($data->id) ? '`id` != ' . $data->id : '';
                        if(!empty($dataID)) $condition = "id != '{$dataID}'";
                        $dao->check($field->field, $rule->rule, $condition);
                    }
                    else
                    {
                        /* 如果传值是0，并且不是下拉选择控件，不验证必填。*/
                        /* If the value passed is 0, and not is select control, it is not required to verify. */
                        if($field->control == 'file')
                        {
                            $files = !empty($_FILES[$field->field]) ? $_FILES[$field->field] : '';
                            if(empty($files['size'][0])) dao::$errors[$field->field][] = sprintf($this->lang->error->notempty, $field->name);

                            return $dao;
                        }

                        if(is_numeric($data->{$field->field}) && $data->{$field->field} == 0 && $rule->rule == 'notempty' && !in_array($field->control, array('select', 'multi-select'))) continue;
                        $dao->check($field->field, $rule->rule);
                    }
                }
                elseif($rule->type == 'regex')
                {
                    $dao->check($field->field, 'reg', $rule->rule);
                }
                elseif($rule->type == 'func')
                {
                    /* To do something. */
                }
            }
        }

        $data = $tmpData;

        return $dao;
    }

    /**
     * Build batch actions of a flow.
     *
     * @param  string $moduleName
     * @access public
     * @return string
     */
    function buildBatchActions($moduleName)
    {
        $batchActions = '';
        $index        = 1;
        $relations    = $this->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($moduleName)->fetchPairs();
        $actions      = $this->loadModel('workflowaction', 'flow')->getList($moduleName);
        foreach($actions as $action)
        {
            if($action->virtual != '1' && $action->status   != 'enable') continue;
            if($action->virtual != '1' && $action->type     != 'batch')  continue;
            if($action->virtual != '1' && $action->position != 'browse' && $action->position != 'browseandview') continue;
            $methodName = $action->action;
            $module     = '';
            $method     = '';
            $params     = 'step=form';


            if(empty($module)) $module = $moduleName;
            if(empty($method)) $method = $methodName;

            /* Filter the relation actions. */
            if($action->virtual == '1')
            {
                if(!isset($relations[$module])) continue;

                $virtualActions = $relations[$module];

                if($method == 'create' && strpos(",{$virtualActions},", ",many2one,") === false) continue;
                if($method == 'batchcreate' && strpos(",{$virtualActions},", ",many2many,") === false) continue;

                $params .= "&prevModule={$moduleName}";
            }
            if(!commonModel::hasPriv($module, $method)) continue;
            $actionLink = helper::createLink($module, $method, $params);
            if($index == 1)
            {
                $batchActions .= baseHTML::a('javascript:;', $action->name, "class='btn' onclick=\"setFormAction('$actionLink')\"");
            }
            else
            {
                if($index == 2)
                {
                    $batchActions .= "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span> </button>";
                    $batchActions .= "<ul class='dropdown-menu' role='menu'>";
                }
                $batchActions .= '<li>' . baseHTML::a('javascript:;', $action->name, "onclick=\"setFormAction('$actionLink')\"") . '</li>';
            }

            $index++;
        }

        if($batchActions)
        {
            if($index > 2) $batchActions .= '</ul>';   // $index > 2 means there are more than one batch action.

            $batchActions = "<div class='btn-toolbar dropup'>{$batchActions}</div>";
        }

        return $batchActions;
    }

    /**
     * Format datas by chart type.
     *
     * @param  array    $chart
     * @param  array    $datas
     * @param  array    $fieldList
     * @access public
     * @return array | object
     */
    public function formatDataByType($chart, $datas, $fieldList)
    {
        $chartDatas = array();
        foreach($datas as $label => $data)
        {
            $chartData = new stdClass();
            $chartData->label = $label;
            $chartData->value = $chart->countType == 'count' ? $data : reset($data);
            $chartDatas[] = $chartData;
        }

        return $chartDatas;
    }
}

function formatMoney($money, $unit = 1)
{
    if($money === 0) return '';

    $decimals    = 2;
    $formatMoney = number_format((float)$money / $unit, $decimals);

    /* If the formated money is too small, change decimals. */
    if($money > 0 && (float)$formatMoney == 0)
    {
        $decimals    = ceil(log10($unit));
        $formatMoney = number_format($money / $unit, $decimals);
    }

    return trim(preg_replace('/\.0*$/', '', $formatMoney));
}
