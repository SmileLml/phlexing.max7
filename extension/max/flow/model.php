<?php
class flowModel extends model
{
    /**
     * Get data of a flow by Id.
     *
     * @param  object $flow
     * @param  int    $dataID
     * @param  bool   $decode
     * @access public
     * @return object
     */
    public function getDataByID($flow, $dataID, $decode = true)
    {
        $data = $this->dao->select('*')->from("$flow->table")->where('id')->eq($dataID)->fetch();

        if($data)
        {
            $fields = $this->dao->select('field')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($flow->module)->andWhere('control')->eq('file')->fetchPairs();
            $files  = $this->loadModel('file')->getByObject($flow->module, $dataID);
            foreach($fields as $field)
            {
                $filesName = "{$field}files";
                $data->$filesName = array();
                foreach($files as $file)
                {
                    $file->size = (int)$file->size;
                    if($file->extra == $field)
                    {
                        if(!isset($file->name)) $file->name = $file->title;
                        $data->{$filesName}[] = $file;
                    }
                }
            }

            $data = $this->processDBData($flow->module, $data, $decode);
        }

        return $data;
    }

    /**
     * Get data list by id list.
     *
     * @param  object $flow
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getDataByIDList($flow, $idList)
    {
        $dataList = $this->dao->select('*')->from($flow->table)
            ->where('deleted')->eq('0')
            ->andWhere('id')->in($idList)
            ->fetchAll('id', false);

        foreach($dataList as $data) $data = $this->processDBData($flow->module, $data);

        return $dataList;
    }

    /**
     * Get data list of a flow.
     *
     * @param  object $flow
     * @param  string $mode
     * @param  int    $label
     * @param  string $categoryQuery
     * @param  int    $parentID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $extraQuery   the param is used in CRRC extension.
     * @access public
     * @return array
     */
    public function getDataList($flow, $mode = 'browse', $label = 0, $categoryQuery = '', $parentID = 0, $orderBy = '', $pager = null, $extraQuery = '')
    {
        if(strpos($orderBy, '_') !== false)
        {
            $orderByExplode    = explode('_', $orderBy);
            $orderByExplode[0] = "`{$orderByExplode[0]}`";
            $orderBy = implode('_', $orderByExplode);
        }

        $querySessionName = $flow->module . 'Query';
        if($this->session->$querySessionName == false) $this->session->set($querySessionName, ' 1 = 1');
        $searchQuery = $this->loadModel('search')->replaceDynamic($this->session->$querySessionName);

        $labelQuery = '';
        if($label)
        {
            if($mode == 'bysearch')
            {
                $query = $this->search->getQuery($label);
                $searchQuery  = $query->sql;
                $labelOrderBy = '';
                $this->session->set($flow->module . 'Form', $query->form);
            }
            else
            {
                list($labelQuery, $labelOrderBy) = $this->getLabelQueryAndOrderBy($label);
            }

            if(!$orderBy) $orderBy = $labelOrderBy;
        }
        if(!$orderBy) $orderBy = 'id_desc';

        $searchQuery = $this->processSearchQuery($searchQuery, $flow);
        $productRelatedModules = ",productplan,release,story,build,bug,testcase,testtask,testsuite,feedback,";
        $dataList = $this->dao->select('*')->from($flow->table)
            ->where('deleted')->eq('0')
            ->beginIF(!$flow->buildin && $parentID)->andWhere('parent')->eq($parentID)->fi()
            ->beginIF($mode == 'bysearch')->andWhere($searchQuery)->fi()
            ->beginIF($labelQuery)->andWhere($labelQuery)->fi()
            ->beginIF($categoryQuery)->andWhere($categoryQuery)->fi()
            ->beginIF($extraQuery)->andWhere($extraQuery)->fi()
            ->beginIF($flow->module == 'product')->andWhere('id')->in($this->app->user->view->products)->fi()
            ->beginIF($flow->module == 'project')->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($flow->module == 'execution')->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($flow->module == 'task')->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($flow->module == 'caselib')->andWhere('product')->eq('0')->fi()
            ->beginIF($mode != 'bysearch' && $flow->belong == 'program')->andWhere('program')->eq((int)$this->session->program)->fi()
            ->beginIF($mode != 'bysearch' && $flow->belong == 'product')->andWhere('product')->eq((int)$this->session->product)->fi()
            ->beginIF($mode != 'bysearch' && $flow->belong == 'project')->andWhere('project')->eq((int)$this->session->project)->fi()
            ->beginIF($mode != 'bysearch' && $flow->belong == 'execution')->andWhere('execution')->eq((int)$this->session->execution)->fi()
            ->beginIF(strpos($productRelatedModules, ',' . $flow->module . ',') !== false)->andWhere('product')->in($this->app->user->view->products)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), $flow->module);
        $this->session->set($flow->module . 'QuerySQL', $this->dao->get());

        foreach($dataList as $data) $data = $this->processDBData($flow->module, $data);

        return $dataList;
    }

    /**
     * 处理搜索条件，替换 program, product, project, execution 字段的 NULL和all 的真实值。
     * Process searchQuery, replace NULL and all to real value for program, product, project, execution fields.
     *
     * @param  string $searchQuery
     * @param  object $flow
     * @access public
     * @return string
     */
    public function processSearchQuery($searchQuery, $flow)
    {
        if(empty($searchQuery)) return $searchQuery;

        $searchQuery = str_replace("`program` = ''",   "`program` = '0'",   $searchQuery);
        $searchQuery = str_replace("`product` = ''",   "`product` = '0'",   $searchQuery);
        $searchQuery = str_replace("`project` = ''",   "`project` = '0'",   $searchQuery);
        $searchQuery = str_replace("`execution` = ''", "`execution` = '0'", $searchQuery);

        if(strpos($searchQuery, "`program` = 'all'")   !== false) $searchQuery = str_replace("`program` = 'all'",   '`program` '   . helper::dbIN(array_keys($this->loadModel('program')->getPairs())),   $searchQuery);
        if(strpos($searchQuery, "`product` = 'all'")   !== false) $searchQuery = str_replace("`product` = 'all'",   '`product` '   . helper::dbIN(array_keys($this->loadModel('product')->getPairs())),   $searchQuery);
        if(strpos($searchQuery, "`project` = 'all'")   !== false) $searchQuery = str_replace("`project` = 'all'",   '`project` '   . helper::dbIN(array_keys($this->loadModel('project')->getPairs())),   $searchQuery);
        if(strpos($searchQuery, "`execution` = 'all'") !== false) $searchQuery = str_replace("`execution` = 'all'", '`execution` ' . helper::dbIN(array_keys($this->loadModel('execution')->getPairs())), $searchQuery);

        if(!empty($flow->belong) && strpos($searchQuery, "`{$flow->belong}`") === false) $searchQuery .= " AND `{$flow->belong}` = '" . (int)$this->session->{$flow->belong} . "'";
        return $searchQuery;
    }

    /**
     * Get data pairs of a flow.
     *
     * @param  object $flow
     * @param  mixed  $idList   array | string
     * @access public
     * @return array
     */
    public function getDataPairs($flow, $idList = array())
    {
        $valueFields = $this->loadModel('workflowfield', 'flow')->getValueFields($flow->module);
        if(!$valueFields)
        {
            $dataList = $this->dao->select('id')->from($flow->table)
                ->where('deleted')->eq('0')
                ->beginIF($idList)->andWhere('id')->in($idList)->fi()
                ->fetchPairs();
            foreach($dataList as $dataID) $dataList[$dataID] = $flow->name . $dataID;

            return $dataList;
        }

        $dataList = $this->dao->select('*')->from($flow->table)
            ->where('deleted')->eq('0')
            ->beginIF($idList)->andWhere('id')->in($idList)->fi()
            ->fetchAll('id', false);

        $fields = $this->dao->select('module, field, type, control, options')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($flow->module)
            ->andWhere('field')->in($valueFields)
            ->fetchAll('field');
        $fields = $this->loadModel('workflowaction', 'flow')->processFields($fields, true, $dataList);

        $dataPairs = array();
        foreach($dataList as $data)
        {
            $value = '';
            foreach($valueFields as $field)
            {
                $field = $fields[$field];

                /* Get display value. */
                if($field->field == 'id')
                {
                    $value .= $flow->name . $data->id . ' ';
                }
                elseif($field->options)
                {
                    if($field->control == 'multi-select' or $field->control == 'checkbox')
                    {
                        $dataValues = explode(',', $data->{$field->field});
                        if(!$dataValues) continue;

                        foreach($dataValues as $dataValue) $value .= zget($field->options, $dataValue) . ' ';
                    }
                    else
                    {
                        $value .= zget($field->options, $data->{$field->field}) . ' ';
                    }
                }
                else
                {
                    $value .= $data->{$field->field} . ' ';
                }

                if(strpos(',date,datetime,', ",$field->control,") !== false) $value = formatTime($value);
            }

            /* Push into array to display. */
            $dataPairs[$data->id] = trim($value);
        }

        return arrayUnion(array(''), $dataPairs);
    }

    /**
     * Get query string and orderBy of a label.
     *
     * @param  int    $labelID
     * @access public
     * @return array
     */
    public function getLabelQueryAndOrderBy($labelID)
    {
        $this->loadModel('workflowhook', 'flow');

        $label = $this->loadModel('workflowlabel', 'flow')->getByID($labelID);
        if(!$label) return array('', '');

        $query  = '(1';
        $fields = $this->loadModel('workflowfield', 'flow')->getControlPairs($label->module);
        foreach($label->params as $param)
        {
            $field    = $param['field'];
            $value    = $param['value'];
            $operator = $param['operator'];

            if(in_array($value, $this->config->flow->variables)) $value = $this->workflowhook->getParamRealValue($value);

            if($operator == 'include')
            {
                if($fields[$field] == 'multi-select' or $fields[$field] == 'checkbox')
                {
                    $values = explode(',', $value);
                    foreach($values as $value)
                    {
                        $value = $this->dbh->quote("%,$value,%");

                        $query .= " AND (1 AND CONCAT(',', `$field`, ',') LIKE $value)";
                    }
                }
                else
                {
                    $value = $this->dbh->quote("%,$value,%");

                    $query .= " AND (1 AND CONCAT(',', `$field`, ',') LIKE $value)";
                }
            }
            else if($operator == 'notinclude')
            {
                if($fields[$field] == 'multi-select' or $fields[$field] == 'checkbox')
                {
                    $values = explode(',', $value);
                    foreach($values as $value)
                    {
                        $value = $this->dbh->quote("%,$value,%");

                        $query .= " AND (1 AND CONCAT(',', `$field`, ',') NOT LIKE $value)";
                    }
                }
                else
                {
                    $value = $this->dbh->quote("%,$value,%");

                    $query .= " AND (1 AND CONCAT(',', `$field`, ',') NOT LIKE $value)";
                }
            }
            else if($operator == 'between')
            {
                $value2 = $param['value2'];
                $value2 = $this->dbh->quote($value2);
                $value  = $this->dbh->quote($value);

                $query .= " AND (1 AND `$field` BETWEEN $value AND $value2)";
            }
            else
            {
                $value    = $this->dbh->quote($value);
                $operator = zget($this->lang->workflowlabel->operatorList, $operator);

                $query .= " AND (1 AND `$field` $operator $value)";
            }
        }
        $query .= ')';

        $orderBy = '';
        if($label->orderBy)
        {
            foreach($label->orderBy as $labelOrderBy)
            {
                $orderBy .= $labelOrderBy['field'] . '_' . $labelOrderBy['type'] . ',';
            }
            if($orderBy) $orderBy = rtrim($orderBy, ',');
        }

        return array($query, $orderBy);
    }

    /**
     * Get module pairs to link.
     *
     * @param  string module
     * @access public
     * @return array
     */
    public function getLinkPairs($module)
    {
        $linkPairs = $this->loadModel('workflow', 'flow')->getPairs('', 'flow');
        unset($linkPairs[$module]);

        return $linkPairs;
    }

    /**
     * Get linked datas of a flow.
     *
     * @param  string $module
     * @param  int    $dataID
     * @param  string $linkType
     * @access public
     * @return array
     */
    public function getLinkedDatas($module, $dataID, $linkType = '')
    {
        $linkedDatas = array();

        if($linkType)
        {
            $linkedIDList = $this->dao->select('linkedID')->from(TABLE_WORKFLOWLINKDATA)
                ->where('objectType')->eq($module)
                ->andWhere('objectID')->eq($dataID)
                ->andWhere('linkedType')->eq($linkType)
                ->fetchPairs();

            if($linkedIDList)
            {
                $linkedFlow = $this->loadModel('workflow', 'flow')->getByModule($linkType);

                if($linkedFlow)
                {
                    $linkedDatas = $this->getDataByIDList($linkedFlow, $linkedIDList);
                }
                else if(in_array($linkType, $this->config->flow->linkPairs))
                {
                    list($linkedApp, $linkedModule) = $this->extractAppAndModule($linkType);

                    $linkedDatas = $this->loadModel($linkedModule, $linkedApp)->getByIDList($linkedIDList);
                }
            }
        }
        else
        {
            $flowList   = $this->dao->select('*')->from(TABLE_WORKFLOW)->fetchAll('module', false);
            $groupDatas = $this->dao->select('*')->from(TABLE_WORKFLOWLINKDATA)
                ->where('objectType')->eq($module)
                ->andWhere('objectID')->eq($dataID)
                ->fetchGroup('linkedType');

            foreach($groupDatas as $linkType => $datas)
            {
                $linkedFlow = zget($flowList, $linkType, '');

                $linkedIDList = array();
                foreach($datas as $data) $linkedIDList[] = $data->linkedID;

                if($linkedFlow)
                {
                    $linkedDatas[$linkType] = $this->getDataByIDList($linkedFlow, $linkedIDList);
                }
                else if(in_array($linkType, $this->config->flow->linkPairs))
                {
                    list($linkedApp, $linkedModule) = $this->extractAppAndModule($linkType);

                    $linkedDatas[$linkType] = $this->loadModel($linkedModule, $linkedApp)->getByIDList($linkedIDList);
                }
            }
        }

        return $linkedDatas;
    }

    /**
     * Get unlinked datas of a flow.
     *
     * @param  string $module
     * @param  int    $dataID
     * @param  string $linkedFlow
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getUnlinkedDatas($module, $dataID, $linkedFlow, $mode)
    {
        $linkedDatas   = $this->getLinkedDatas($module, $dataID, $linkedFlow->module);
        $unlinkedDatas = $this->getDataList($linkedFlow, $mode);

        foreach($unlinkedDatas as $id => $data)
        {
            if(isset($linkedDatas[$id])) unset($unlinkedDatas[$id]);
        }

        return $unlinkedDatas;
    }

    /**
     * Get formula script.
     *
     * @param  string $module
     * @param  object $action
     * @param  array  $fields
     * @param  array  $childFields
     * @access public
     * @return string
     */
    public function getFormulaScript($module, $action, $fields, $childFields = array())
    {
        $script = '';
        foreach($childFields as $childModule => $moduleFields)
        {
            $result = $this->getFieldScript($childModule, $action, $moduleFields, $mode = 'sub');
            if(is_array($result)) return $result['message'];

            $script .= $result;
        }

        $result = $this->getFieldScript($module, $action, $fields);
        if(is_array($result)) return $result['message'];

        $script .= $result;

        if($script) $script = "<script>\n$(function()\n{\n{$script}});\n</script>";

        return $script;
    }

    /**
     * Get script of formula fields of a module.
     *
     * @param  string $module
     * @param  object $action
     * @param  array  $fields
     * @access public
     * @return string
     */
    public function getFieldScript($module, $action, $fields, $mode = '')
    {
        static $targetFields = array();

        $flowModule = $mode == 'sub' ? substr($module, 4) : $module;
        foreach($fields as $field) $targetFields[$flowModule][$field->field] = $field;

        $script = '';
        $blank  = '    ';
        $errors = array();
        foreach($fields as $field)
        {
            if($field->control != 'formula' or empty($field->expression)) continue;

            $expression = '';
            $selectors  = array();
            $conditions = array();
            $vars       = array();
            $functions  = array();
            $existVars  = array();

            $items = json_decode($field->expression);
            foreach($items as $item)
            {
                switch($item->type)
                {
                case 'target' :
                    if(!isset($targetFields[$item->module][$item->field]))
                    {
                        $expression .= 0;
                        $errors[]    = "console.log('Error: target field not found.');";
                        $errors[]    = "console.log(" . json_encode($item). ");";
                        break;
                    }

                    $targetField = $targetFields[$item->module][$item->field];
                    $parseFunc   = $targetField->type == 'decimal' ? 'parseFloat' : 'parseInt';

                    if(!empty($item->function))
                    {
                        $var  = "{$item->module}_{$targetField->field}_{$item->function}";
                        $list = "{$item->module}_{$targetField->field}_list";

                        $functions[] = "var {$var} = 0;";
                        $functions[] = "var {$list} = $('.table-child [name*=\\\[sub_{$item->module}\\\]][name*=\\\[{$targetField->field}\\\]]').map(function(item, obj){if($.isNumeric($(obj).val())) return {$parseFunc}($(obj).val());});";
                        switch($item->function)
                        {
                        case 'sum' :
                            $functions[] = "if($list.length > 0) {$var} = {$parseFunc}({$list}.reduce((a, b) => {$parseFunc}(a) + {$parseFunc}(b)));\n";
                            break;
                        case 'average' :
                            $functions[] = "if($list.length > 0) {$var} = {$parseFunc}({$list}.reduce((a, b) => {$parseFunc}(a) + {$parseFunc}(b))) / {$list}.length;\n";
                            break;
                        case 'max' :
                            $functions[] = "if($list.length > 0) {$var} = {$parseFunc}(Math.max(...{$list}));\n";
                            break;
                        case 'min' :
                            $functions[] = "if($list.length > 0) {$var} = {$parseFunc}(Math.min(...{$list}));\n";
                            break;
                        case 'count' :
                            $functions[] = "if($list.length > 0) {$var} = {$list}.length;\n";
                            break;
                        }

                        $selector    = "[name*=\\\[sub_{$item->module}\\\]][name*=\\\[{$targetField->field}\\\]]";
                        $expression .= $var;
                    }
                    else
                    {
                        $var = "{$flowModule}_{$targetField->field}";

                        if($mode == 'sub')
                        {
                            $selector = "[name*=\\\[{$module}\\\]][name*=\\\[{$targetField->field}\\\]]";
                            $vars[]   = "var {$var} = {$parseFunc}($(this).closest('tr').find('{$selector}').val());";
                        }
                        else
                        {
                            if($action->type == 'batch' && $action->batchMode == 'different')
                            {
                                $selector = "[name^={$targetField->field}]";
                                $vars[]   = "var {$var} = {$parseFunc}($(this).closest('tr').find('{$selector}').val());";
                            }
                            elseif(!isset($existVars[$var]))
                            {
                                $selector = "[name={$targetField->field}]";
                                $vars[]   = "const {$var} = {$parseFunc}($('{$selector}').val());";

                                $existVars[$var] = $var;
                            }
                        }

                        $expression .= $var;
                    }

                    $selectors[]  = $selector;
                    $conditions[] = "if(!$.isNumeric($('{$selector}').val())) return false;";
                    break;
                case 'number' :
                    $expression .= $item->value;
                    break;
                default :
                    if($item->operator != '(' && $item->operator != ')') $item->operator = " {$item->operator} ";

                    $expression .= $item->operator;
                }
            }

            if($expression)
            {
                if($field->type == 'decimal')
                {
                    $decimalDigits = (int)substr($field->length, strpos($field->length, ',') + 1);
                    $precision     = pow(10, $decimalDigits);
                    $expression    = "Math.round(({$expression}) * {$precision}) / {$precision}";
                }
                else
                {
                    $expression = "Math.round({$expression})";
                }

                if($selectors)
                {
                    $script .= $blank . "$(document).on('change', '" . implode(',', $selectors) . "', function()\n";
                    $script .= $blank . "{\n";

                    //foreach($conditions as $condition) $script .= $blank . $blank . $condition . "\n";
                    //if($conditions) $script .= "\n";

                    foreach($vars as $var) $script .= $blank . $blank . $var . "\n";
                    if($vars) $script .= "\n";

                    foreach($functions as $function) $script .= $blank . $blank . $function . "\n";

                    $script .= $blank;
                }

                if($mode == 'sub')
                {
                    if($selectors)
                    {
                        $script .= $blank . "$(this).closest('tr').find('[name*=\\\[{$module}\\\]][name*=\\\[{$field->field}\\\]]').val({$expression}).trigger('change');\n";
                    }
                    else
                    {
                        $script .= $blank . "$('[name*=\\\[{$module}\\\]][name*=\\\[{$field->field}\\\]]').val({$expression}).trigger('change');\n";
                    }
                }
                else
                {
                    if($action->type == 'batch' && $action->batchMode == 'different')
                    {
                        if($selectors)
                        {
                            $script .= $blank . "$(this).closest('tr').find('[name^={$field->field}]').val({$expression}).trigger('change');\n";
                        }
                        else
                        {
                            $script .= $blank . "$('[name^={$field->field}]').val({$expression}).trigger('change');\n";
                        }
                    }
                    else
                    {
                        $script .= $blank . "$('[name={$field->field}]').val({$expression}).trigger('change');\n";
                    }
                }

                if($selectors) $script .= $blank . "});\n\n";
            }
        }

        foreach($errors as $error) $script .= $blank . $error . "\n";

        return $script;
    }

    /**
     * Get script to reset options of subStatus.
     *
     * @param  object $field
     * @access public
     * @return string
     */
    public function getSubStatusScript($field)
    {
        $this->app->loadLang($field->module);

        if(!isset($this->lang->{$field->module}->status)) return '';

        $statusLabel = $this->lang->{$field->module}->status;

        $field = $this->loadModel('workflowfield')->getByField($field->module, $field->field, $mergeOptions = false);

        $script  = '<script>';
        $script .= "var subStatusData = JSON.parse('" . json_encode($field->options) . "');\n";
        $script .= <<<EOT
$(function()
{
    $('.side-col th.thWidth').width($('.side-col .table:first-child th').width());

    // If subStatus is in div, change div to tr.
    var isEmptyBlock   = false;
    var subStatusBlock = $('#subStatus').closest('.table');
    if(subStatusBlock.find('tr').size() == 1) isEmptyBlock = true;

    // Move subStatus after status.
    $('#status:not(.other)').closest('tr').after($('#subStatus').parents('tr'));

    // If subStatus is in div, change div to tr.
    if($('#subStatus').parent().parent().is('div'))
    {
        var \$div   = $('#subStatus').parent().parent();
        var title   = \$div.find('div.detail-title').html();
        var content = \$div.find('div.detail-content').html();
        \$div.replaceWith('<tr><th>' + title + '</th><td>' + content + '</td></tr>');

        $('#subStatus').next('.chosen-container').remove();
        $('#subStatus').chosen();
    }

    if(isEmptyBlock) subStatusBlock.closest('.panel').remove();

    if($('#status:not(.other)').parents('tr').hasClass('hide'))
    {
        var statusLabel = '{$statusLabel}';
        if(statusLabel) $('#subStatus').parent().prev().html(statusLabel);
    }

    $('#status:not(.other)').change(function()
    {
        var status = $(this).val();
        if(!status) return;

        var oldStatus        = $(this).attr('oldStatus') === undefined ? status : $(this).attr('oldStatus');
        var oldSubStatus     = $('#subStatus').val();
        var subStatus        = subStatusData[status];
        var defaultSubStatus = subStatus === undefined ? '' : subStatus.default;
        var options          = subStatus === undefined ? {} : subStatus.options;

        $('#subStatus').empty();
        $('#subStatus').append('<option></option>');

        $.each(options, function(key, value)
        {
            $('#subStatus').append("<option value='" + key + "'>" + value + '</option>');
        });

        $('#subStatus').val((oldStatus == status && options[oldSubStatus] !== undefined) ? oldSubStatus : defaultSubStatus).trigger('chosen:updated');

        $(this).attr('oldStatus', status);
    });

    $('#status:not(.other)').trigger('change');
});
EOT;
        $script .= '</script>';

        return $script;
    }

    /**
     * Get script by linkages of an action.
     *
     * @param  object $action
     * @param  array  $fields
     * @param  int    $ui
     * @access public
     * @return string
     */
    public function getLinkageScript($action, $fields, $ui = 0)
    {
        $script   = '';
        $linkages = zget($action->linkages, $ui, array());

        $sourceGroups = array();
        foreach($linkages as $linkage)
        {
            $sourceField = $linkage->sources[0]->field;
            $sourceGroups[$sourceField][] = $linkage;
        }

        foreach($sourceGroups as $sourceField => $linkages)
        {
            $linkage = reset($linkages);
            $source  = reset($linkage->sources);

            if(!isset($fields[$source->field])) continue;

            $field = $fields[$source->field];
            $value = str_replace('"', '\"', $source->value);

            $selector = "[name='{$source->field}']";
            $val      = "var val = $(this).val();";
            if($field->control == 'radio' && !$field->readonly)
            {
                $val = "var val = $('[name={$source->field}]:checked').val();";
            }
            elseif($field->control == 'checkbox' && !$field->readonly)
            {
                $selector = "[name='{$source->field}[]']";
                $val      = <<<EOT
var values = [];
$("[name='{$source->field}[]']:checked").each(function()
{
    values.push($(this).val());
});
var val = values.join();
EOT;
            }
            elseif($field->control == 'multi-select')
            {
                $selector = "[name='{$source->field}[]']";
            }

            $conditions = array();
            foreach($linkages as $linkage)
            {
                $sources = zget($linkage, 'sources', array());
                $targets = zget($linkage, 'targets', array());

                if(!$linkage or !$sources or !$targets) continue;

                $source = reset($linkage->sources);
                if(!isset($conditions[$source->operator][$source->value])) $conditions[$source->operator][$source->value] = array();

                $targetInit   = '';
                $targetChange = '';
                foreach($targets as $target)
                {
                    $initFunc   = 'show()';
                    $initAttr   = "removeAttr('disabled')";
                    $changeFunc = $target->status == 'show' ? 'show()' : 'hide()';
                    $changeAttr = $target->status == 'show' ? "removeAttr('disabled')" : "attr('disabled', 'disabled')";

                    $name = $fields[$target->field]->control == 'file' ? "{$target->field}[]" : $target->field;
                    if(strpos(',multi-select,checkbox,', ",{$fields[$target->field]->control},") !== false) $name .= '[]';

                    $targetInit   .= "        $(\"[name='{$name}']\").{$initAttr};\n";
                    $targetInit   .= "        $(\"[name='{$name}']\").parents('.form-group,.section,tr').{$initFunc};";
                    $targetChange .= "        window.waitDom(\"[name='{$name}']\", function(){\n";
                    $targetChange .= "            $(\"[name='{$name}']\").{$changeAttr};\n";
                    $targetChange .= "            $(\"[name='{$name}']\").parents('.form-group,.section,tr').{$changeFunc};\n";
                    $targetChange .= "        });";

                    if(!isset($conditions[$source->operator][$source->value]['targetInit'][$name]))
                    {
                        $conditions[$source->operator][$source->value]['targetInit'][$name] = $targetInit;
                    }
                    $conditions[$source->operator][$source->value]['targetChange'][$name] = $targetChange;
                }
            }

            $jsConditions  = '';
            $allTargetInit = '';
            foreach($conditions as $sourceOperator => $sourceValues)
            {
                foreach($sourceValues as $sourceValue => $targets)
                {
                    if(strpos($sourceValue, ',') !== false)
                    {
                        $sourceValue = explode(',', $sourceValue);
                        sort($sourceValue);
                        $sourceValue = join(',', $sourceValue);
                    }

                    $allTargetInit .= join("\n", $targets['targetInit']);
                    $jsConditions  .= "if(data {$sourceOperator} '$sourceValue')";
                    $jsConditions  .= "\n{\n" . join("\n", $targets['targetChange']) . "\n}\n";
                }
            }

            $script .= <<<EOT
<script>
$(function()
{
    $("form").on('change', "{$selector}", function()
    {
        {$val}

        var data = val + '';
        if(data.indexOf(',') >= 0)
        {
            data = data.replace(/^,+|,$/gm, '').split(',');
            data.sort();
            data = data.join();
        }

        {$allTargetInit}
        {$jsConditions}

        $('.chosen-controled').trigger('chosen:updated');

        $('.picker-select').each(function()
        {
            var picker = $(this).data('zui.picker');
            if(picker && !$(this).attr('disabled') && $(this).hasClass('disabled')) picker.setDisabled(false);
        });
    });
    window.waitDom("{$selector}", function(){ $(this).trigger('change'); });
});
</script>
EOT;
        }

        return $script;
    }

    /**
     * Get menu of a flow.
     *
     * @param  object $flow
     * @param  array  $labels
     * @param  array  $categories
     * @access public
     * @return void
     */
    public function getModuleMenu($flow, $labels = array(), $categories = array())
    {
        if($flow->navigator == 'primary' && isset($this->lang->{$flow->module}->menu)) $this->lang->flow->menu = $this->lang->{$flow->module}->menu;
        if($flow->navigator == 'secondary' && isset($this->lang->{$flow->app}->menu))  $this->lang->flow->menu = $this->lang->{$flow->app}->menu;
        return false;
    }

    /**
     * Get category menu settings of a flow.
     *
     * @param  string $module
     * @param  string $mode
     * @param  int    $label
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return string
     */
    public function getCategories($module, $mode, $label = 0, $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $categories = array();

        $fields = $this->loadModel('workflowfield')->getCategoryFields($module);
        if(!$fields) return $categories;

        $this->loadModel('tree');
        foreach($fields as $type => $field)
        {
            $category = new stdclass();
            $category->type     = $type;
            $category->name     = $field->name;

            $category->treeMenu = $this->tree->getTreeMenu(0, $type, 0, array('flowModel', 'createCategoryLink'), array('module'=> $module, 'mode' => $mode, 'label' => $label, 'field' => $field->field, 'orderBy' => $orderBy, 'recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'pageID' => $pageID), '');

            $categories[$type] = $category;
        }

        return $categories;
    }

    /**
     * Create category manage link.
     *
     * @param  string $type
     * @param  object $category
     * @param  string $parent
     * @param  array  $extra
     * @static
     * @access public
     * @return string
     */
    public static function createCategoryLink($type, $category, $parent = '0', $extra = array())
    {
        $data = new stdclass();
        $data->id     = (string)$category->id;
        $data->parent = (string)$category->parent;
        $data->name   = $category->name;

        if(!empty($extra)) $data->url = helper::createLink($extra['module'], 'browse', "mode={$extra['mode']}&label={$extra['label']}&category={$extra['field']}=$category->id&orderBy={$extra['orderBy']}&recTotal={$extra['recTotal']}&recPerPage={$extra['recPerPage']}&pageID={$extra['pageID']}");
        return $data;
    }

    /**
     * Get summary of dataList.
     *
     * @param  array  $dataList
     * @param  array  $fields
     * @access public
     * @return string
     */
    public function getSummary($dataList, $fields)
    {
        if(!$dataList) return '';

        $fieldValues = array();
        foreach($fields as $field)
        {
            if(!$field->show or $field->field == 'actions') continue;
            if(!$field->summary or $field->field == 'id' or !in_array($field->type, $this->config->workflowfield->numberTypes)) continue;

            foreach($dataList as $data) $fieldValues[$field->field][] = $data->{$field->field};
        }

        if(!$fieldValues) return '';

        $fieldSummary = explode(',', $field->summary);

        $summaryList = array();
        foreach($fieldValues as $field => $values)
        {
            asort($values);

            $fieldSummary = explode(',', $fields[$field]->summary);
            foreach($fieldSummary as $summaryType)
            {
                switch($summaryType)
                {
                case 'sum':
                    $summaryList[$field][$summaryType] = array_sum($values);
                    break;
                case 'average':
                    $decimalDigits = 2;
                    $fieldType     = $fields[$field]->type;
                    if($fieldType == 'decimal')
                    {
                        $fieldLength   = $fields[$field]->length;
                        $decimalDigits = substr($fieldLength, strpos($fieldLength, ',') + 1);
                    }

                    $summaryList[$field][$summaryType] = round(array_sum($values) / count($values), $decimalDigits);
                    break;
                case 'max':
                    $summaryList[$field][$summaryType] = end($values);
                    break;
                case 'min':
                    $summaryList[$field][$summaryType] = reset($values);
                    break;
                }
            }
        }

        if(!$summaryList) return '';

        $summary = '';
        foreach($summaryList as $field => $fieldSummary)
        {
            $summary .= $fields[$field]->name . $this->lang->hyphen;
            foreach($fieldSummary as $type => $value) $summary .= $this->lang->workflowlayout->summaryList[$type] . $value . $this->lang->comma;
            $summary = rtrim($summary, $this->lang->comma) . $this->lang->semicolon;
        }

        return rtrim($summary, $this->lang->semicolon);
    }

    /**
     * Get the post data list.
     *
     * @param  object $flow
     * @param  object $action
     * @access public
     * @return array
     */
    public function getPostData($flow, $action)
    {
        $fields   = $this->loadModel('workflowaction')->getFields($flow->module, $action->action);
        $postData = fixer::input('post')->get();

        $dataList = array();
        if($action->open == 'none')
        {
            /* 无页面批量动作，将id存入dataList。*/
            /* No page batch action, store ID in dataList. */
            foreach($postData->dataIDList as $id)
            {
                if(!isset($dataList[$id])) $dataList[$id] = new stdclass();
                $dataList[$id]->id = $id;
            }
        }
        else
        {
            /* 批量操作多条数据执行相同操作时，将postData的字段值转为id与值对应的键值对格式。*/
            /* Convert the field values of postData into key value pairs when batch operate multiple pieces of data to the same value. */
            if($action->batchMode == 'same')
            {
                foreach($postData as $field => $value)
                {
                    if(!isset($postData->dataIDList) || $field == 'dataIDList') continue;

                    $valueList = array();
                    if(strpos($postData->dataIDList, ',') !== false)
                    {
                        foreach(explode(',', $postData->dataIDList) as $id) $valueList[$id] = $value;
                    }
                    else
                    {
                        $valueList[$postData->dataIDList] = $value;
                    }
                    $postData->{$field} = empty($valueList) ? $value : $valueList;
                }
            }

            foreach($fields as $field)
            {
                if(!$field->show) continue;
                if(!isset($postData->{$field->field})) continue;

                /* Process ditto to real value. */
                foreach($postData->{$field->field} as $id => $value)
                {
                    if(!isset($dataList[$id])) $dataList[$id] = new stdclass();

                    if(is_string($value)) $value = trim(nl2br($value));
                    if(is_array($value))  $value = array_filter($value);
                    if(strpos(',date,datetime,', ",$field->control,") !== false && empty($value)) $value = NULL;

                    $dataList[$id]->{$field->field} = $value;
                }
            }
        }

        if($action->method != 'batchcreate')
        {
            /* Get post data id list. */
            $dataIDList = array_keys($dataList);
            if(!$dataIDList) return array();

            /* Check post datas. */
            $validDataList = $this->getDataByIDList($flow, $dataIDList);
            foreach($validDataList as $dataID => $data)
            {
                $enabled = $this->checkConditions($action->conditions, $data);
                if(!$enabled) unset($validDataList[$dataID]);
            }
            if(!$validDataList) return array();
        }
        else
        {
            foreach($dataList as $id => $data)
            {
                $emptyData = true;
                foreach($data as $field => $value)
                {
                    if($value)
                    {
                        $emptyData = false;
                        break;
                    }
                }
                if($emptyData) unset($dataList[$id]);
            }
        }

        return $dataList;
    }

    /**
     * Get data export to excel.
     *
     * @param  object $flow
     * @param  array  $flowDatas
     * @param  array  $flowFields
     * @access public
     * @return array
     */
    public function getExportData($flow, $flowDatas, $flowFields)
    {
        $fields         = array();
        $rows           = array();
        $numberFields   = array();
        $headerRowCount = 1;
        $headerRowspan  = array();
        $headerColspan  = array();
        $dataRowspan    = array();

        /* Prepare flow fields and number fields. */
        $this->loadModel('file');
        $exportFields = $this->loadModel('workflowfield', 'flow')->getExportFields($flow->module);
        foreach($flowFields as $fieldName => $field)
        {
            if(!isset($exportFields[$field->field])) continue;

            if(in_array($field->type, $this->config->workflowfield->numberTypes))
            {
                if($field->control != 'select' and $field->control != 'radio') $numberFields[] = $field->field;
            }

            if($field->control == 'file')
            {
                foreach($flowDatas as $flowData)
                {
                    $flowData->$fieldName = '';
                    $files = $this->file->getByObject($field->module, $flowData->id, false, '0', $field->field);
                    if($files)
                    {
                        foreach($files as $file)
                        {
                            $fileURL = commonModel::getSysURL() . helper::createLink('file', 'download', "fileID=$file->id");
                            $flowData->$fieldName .= baseHTML::a($fileURL, $file->title, '_blank') . '<br />';
                        }
                    }
                }
            }
        }

        $flowDatas = $this->processExportDatas($flowDatas, $flowFields, $exportFields); // Prepare flow datas export to excel.

        /* Check child modules and prepare datas of them. */
        $childDataCount = array();
        $childDatas     = array();
        $dataIDList     = array_keys($flowDatas);
        $childModules   = $this->loadModel('workflow', 'flow')->getList('browse', 'table', '', $flow->module);
        foreach($childModules as $childModule)
        {
            /* Get child datas group by the parent field. */
            $groupDatas = $this->dao->select('*')->from($childModule->table)
                ->where('deleted')->eq('0')
                ->andWhere('parent')->in($dataIDList)
                ->fetchGroup('parent');

            if($groupDatas)
            {
                $headerRowCount    = 2;
                $childExportFields = $this->workflowfield->getExportFields($childModule->module);
                $childFields       = $this->workflowfield->getList($childModule->module);

                /* Prepare child fields and number fields. */
                foreach($childFields as $field)
                {
                    if(!isset($childExportFields[$field->field])) continue;

                    if(in_array($field->type, $this->config->workflowfield->numberTypes))
                    {
                        if($field->control != 'select' and $field->control != 'radio') $numberFields[] = $childModule->module . '_' . $field->field;
                    }
                }

                /* Prepare fields export to excel. */
                foreach($exportFields as $fieldKey => $fieldLabel)
                {
                    $fields[0][$fieldKey] = $fieldLabel;
                    $fields[1][$fieldKey] = $fieldLabel;

                    $headerRowspan[0][$fieldKey] = 2;   // Prepare the header rowspan of flow fields.
                }

                /* Prepare fields export to excel. */
                $index = 1;
                foreach($childExportFields as $fieldKey => $fieldLabel)
                {
                    $fieldKey = $childModule->module . '_' . $fieldKey;

                    $fields[0][$fieldKey] = $childModule->name;
                    $fields[1][$fieldKey] = $fieldLabel;

                    if($index == 1) $headerColspan[0][$fieldKey] = count($childExportFields);   // Prepare the header colspan of child fields.

                    $index++;
                }

                /* Prepare child datas export to excel. */
                foreach($groupDatas as $parent => $datas)
                {
                    /* Prepare the child data count. */
                    $count = count($datas);
                    if(isset($childDataCount[$parent]))
                    {
                        if($count > $childDataCount[$parent]) $childDataCount[$parent] = $count;
                    }
                    else
                    {
                        $childDataCount[$parent] = $count;
                    }

                    $datas = $this->processExportDatas($datas, $childFields, $childExportFields);

                    $childDatas[$parent][$childModule->module] = $datas;
                }
            }
        }

        if(!$fields) $fields = $exportFields;

        /* Build datas export to excel according to the flow datas, the child datas and the child data count. */
        $row = 1;
        foreach($flowDatas as $dataID => $flowData)
        {
            $childCount = zget($childDataCount, $dataID, 1);

            for($index = 0; $index < $childCount; $index++)
            {
                if($index == 0)
                {
                    foreach($flowData as $field => $value)
                    {
                        $rows[$row][$field]        = $value;   // Build datas according to the flow datas.
                        $dataRowspan[$row][$field] = $childCount;   // Prepare the data rowspan.
                    }
                }

                /* If the flow data has child datas, build them. */
                if(isset($childDatas[$dataID]))
                {
                    foreach($childDatas[$dataID] as $childModule => $datas)
                    {
                        if(isset($datas[$index]))
                        {
                            foreach($datas[$index] as $childField => $childValue) $rows[$row][$childModule . '_' . $childField] = $childValue;
                        }
                        else
                        {
                            foreach($datas[0] as $childField => $childValue) $rows[$row][$childModule . '_' . $childField] = '';
                        }
                    }
                }

                $row++;
            }
        }

        $data = new stdclass();
        $data->fields         = $fields;
        $data->rows           = $rows;
        $data->kind           = $flow->module;
        $data->title          = $flow->name;
        $data->numberFields   = $numberFields;
        $data->headerRowCount = $headerRowCount;
        $data->headerRowspan  = $headerRowspan;
        $data->headerColspan  = $headerColspan;
        $data->dataRowspan    = $dataRowspan;
        $data->nocolor        = true;

        return $data;
    }

    /**
     * Get import data.
     *
     * @param  object $flow
     * @access public
     * @return array
     */
    public function getImportData($flow)
    {
        $this->loadModel('workflowfield', 'flow');

        $fields = array();
        $titles = array();

        $flowFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($flow->module)
            ->andWhere('canExport')->eq('1')
            ->orderBy('`order`')
            ->fetchAll('field', false);

        foreach($flowFields as $field) $titles[$field->field] = $field->name;

        $fields[$flow->module] = $flowFields;

        $titles['sub_tables'] = array();    // 初始化数组防止没有明细表时循环出错。

        /* 获取明细表导出的字段。*/
        $subTables = $this->loadModel('workflow', 'flow')->getList('browse', 'table', '', $flow->module);
        foreach($subTables as $subTable)
        {
            $subFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)
                ->where('module')->eq($subTable->module)
                ->andWhere('canExport')->eq('1')
                ->orderBy('`order`')
                ->fetchAll('field', false);

            if(!$subFields) continue;

            $titles['sub_tables'][$subTable->module]['name'] = $subTable->name;

            foreach($subFields as $subField) $titles['sub_tables'][$subTable->module]['fields'][$subField->field] = $subField->name;

            $fields[$subTable->module] = $subFields;
        }

        list($fields, $datas) = $this->parseExcel($flow, $fields, $titles);

        return array($fields, $titles, $datas);
    }

    /**
     * Parse excel file into array.
     *
     * @param  object $flow
     * @param  array  $fields
     * @param  array  $titles
     * @param  int    $sheetIndex
     * @access public
     * @return array
     */
    public function parseExcel($flow, $fields = array(), $titles = array(), $sheetIndex = 0)
    {
        $file = $this->session->importFile;

        $phpExcel  = $this->app->loadClass('phpexcel');
        $phpReader = new PHPExcel_Reader_Excel2007();
        if(!$phpReader->canRead($file)) $phpReader = new PHPExcel_Reader_Excel5();

        $excel      = $phpReader->load($file);
        $sheet      = $excel->getSheet($sheetIndex);            // 获取工作簿对象。
        $allRows    = $sheet->getHighestRow();                  // 获取内容区域最大行数。
        $allColumns = $sheet->getHighestColumn();               // 获取内容区域最大列数。
        $mergeCells = $sheet->getMergeCells();                  // 获取合并单元格的集合。
        $mergeCells = $this->processMergeCells($mergeCells);    // 处理合并单元格的数据。

        /* In php, 'A'++  === 'B', 'Z'++ === 'AA', 'a'++ === 'b', 'z'++ === 'aa'. */
        $allColumns++;          // 终止列设为内容区域列的下一列。
        $titleRows = 1;         // 表头行数。
        $column    = 'A';       // 起始列。
        $columnKey = array();   // 每一列和模块、字段的对应关系集合。

        /* 遍历表头区域，获取每一列对应的模块和字段。*/
        while($column != $allColumns)   // 遍历列。
        {
            $module = $flow->module;    // 默认每一列对应的流程是当前流程。

            for($row = 1; $row <= $titleRows; $row++)   // 遍历行。
            {
                if($row > 2) break;         // 表头最大支持两行。

                $cell = $column . $row;     // 当前读取的单元格。

                if(isset($mergeCells[$cell]))
                {
                    $mergeCell = $mergeCells[$cell];
                    $cell      = $mergeCell->parent;

                    if($mergeCell->rowspan > $titleRows) $titleRows = $mergeCell->rowspan;  // 如果当前读取的单元格被跨行合并到其他单元格，则更新表头行数。
                }
                $title = $sheet->getCell($cell)->getValue();    // 读取单元格内容。

                if($row == 1)
                {
                    $field = array_search($title, $titles);     // 根据读取的内容和表头字段数组获取字段代号。

                    /* 如果没有获取到字段代号则从明细表的表头字段数组中查找。*/
                    if(!$field)
                    {
                        $module = '';

                        foreach($titles['sub_tables'] as $subModule => $subTable)
                        {
                            if($title == $subTable['name'])
                            {
                                $module = $subModule;   // 如果读取的内容是明细表的名称，列对应的流程改为明细表的流程代号。
                            }
                            else
                            {
                                $field = array_search($title, $subTable['fields']);     // 根据读取的内容和明细表表头字段数组获取字段代号。

                                /* 如果获取到字段代号则把列对应的流程改为明细表的流程代号，并中断对明细表表头字段数组的循环。*/
                                if($field)
                                {
                                    $module = $subModule;

                                    break;
                                }
                            }
                        }
                    }
                }

                if($row == 2)
                {
                    if(!isset($columnKey[$column]['module'])) break;    // 列对应的流程应该在第一行已经确定，否则终止后续操作。

                    $module = $columnKey[$column]['module'];

                    if($module == $flow->module)
                    {
                        $field = array_search($title, $titles);     // 根据读取的内容和表头字段数组获取字段代号。
                    }
                    else
                    {
                        $field = array_search($title, $titles['sub_tables'][$module]['fields']);     // 根据读取的内容和明细表表头字段数组获取字段代号。
                    }

                }

                if(!$module && !$field) break;  // 如果获取不到列对应的流程代号和字段代号，终止后续操作。

                if(empty($columnKey[$column]['module'])) $columnKey[$column]['module'] = $module;
                if(empty($columnKey[$column]['field']))  $columnKey[$column]['field']  = $field;
            }

            $column++;
        }

        $dataList      = array();   // 数据集合。
        $dateFields    = array();   // 日期字段集合。
        $defaultValues = array();   // 默认值集合。

        /* 获取日期类型字段和默认值集合。*/
        foreach($fields as $flowFields)
        {
            foreach($flowFields as $field)
            {
                if($field->control == 'date' or $field->control == 'datetime') $dateFields[$field->module][$field->field] = $field->field;

                $defaultValues[$field->module][$field->field] = $field->default;
            }
        }

        /* 遍历数据区域。数据区域的起始行是表头区域的下一行，所以用表头行数加一。*/
        for($row = $titleRows + 1; $row <= $allRows; $row++)    // 遍历行。
        {
            $column = 'A';  // 起始列。
            $key    = $row; // 主数据的键名。
            $subKey = $row; // 明细表数据的键名。

            while($column != $allColumns)   // 遍历列。
            {
                /* 如果在前面没有获取到该列和模块、字段的对应关系，则跳过该列。*/
                if(empty($columnKey[$column]['module']) or empty($columnKey[$column]['field']))
                {
                    $column++;
                    continue;
                }

                $cell   = $column . $row;                   // 当前读取的单元格。
                $module = $columnKey[$column]['module'];    // 列对应的流程。
                $field  = $columnKey[$column]['field'];     // 列对应的字段。

                /* 如果当前读取的单元格被合并到其他单元格做相应处理。*/
                if(isset($mergeCells[$cell]))
                {
                    $mergeCell = $mergeCells[$cell];

                    if($mergeCell->parent != $cell)     // 当前读取的单元格是合并单元格的第一个单元格时不需要处理。
                    {
                        if($module == $flow->module)    // 当前读取的单元格对应的流程是主流程时，需要判断主流程在当前行对应的所有单元格是否都合并单元格，如果都合并则认为当前行和被合并到的单元格所在的行是同一条数据。
                        {
                            $allColumnsMerged = true;
                            foreach($columnKey as $currentColumn => $columnMap) // 遍历列和流程的对应关系。
                            {
                                if($columnMap['module'] != $module) continue;   // 列对应的流程不是主流程，跳过。

                                $currentCell = $currentColumn . $row;

                                /* 有一个单元格没有被合并，则跳出循环。*/
                                if(!isset($mergeCells[$currentCell]))
                                {
                                    $allColumnsMerged = false;
                                    break;
                                }

                                $currentMergeCell = $mergeCells[$currentCell];

                                /* 单元格是合并单元格的第一个单元格时，跳出循环。因为合并单元格的第一个单元格一定是一条独立的数据。*/
                                if($currentMergeCell->parent == $currentCell)
                                {
                                    $allColumnsMerged = false;
                                    break;
                                }
                            }

                            /* 如果当前读取的单元格对应的流程在当前行对应的所有单元格都合并了，把key改成合并后单元格的行号，并跳过后续操作。*/
                            if($allColumnsMerged)
                            {
                                preg_match('|[0-9]+|', $mergeCell->parent, $match); // 获取合并后单元格的行号。

                                $key = $match[0];   // 把key改成合并后单元格的行号。

                                $column++;
                                continue;
                            }
                        }

                        $cell = $mergeCell->parent; // 把当前读取的单元格改成合并后的单元格。
                    }
                }

                $value  = $sheet->getCell($cell)->getValue();   // 获取单元格内容。
                if(is_string($value)) $value = trim($value);

                if(empty($value)) $value = '';  // 避免显示0，0.0等类似数据。

                /* 处理日期类型的字段值。*/
                if(is_numeric($value) && isset($dateFields[$module][$field]))
                {
                    $value = date(DT_DATE1, PHPExcel_Shared_Date::ExcelToPHP($value));
                }

                if($module == $flow->module)
                {
                    $dataList[$key][$field] = $value;  // 记录主流程数据。
                }
                else
                {
                    $dataList[$key]['sub_tables'][$module][$subKey][$field] = $value;  // 记录明细表数据。
                }

                $column++;
            }
        }

        $dataList = $this->processDefaultValue($flow, $titles, $dataList, $defaultValues);
        $dataList = $this->processEmptyData($dataList);
        $fields   = $this->processImportFields($fields, $dataList);

        foreach($dataList as $key => $data)
        {
            foreach($data as $field => $value)
            {
                if($field == 'sub_tables')
                {
                    foreach($value as $subModule => $subDatas)
                    {
                        foreach($subDatas as $subKey => $subData)
                        {
                            foreach($subData as $subField => $subValue)
                            {
                                if(!isset($fields[$subModule][$subField])) continue;

                                $subField = $fields[$subModule][$subField];

                                if(!is_array($subField->options) or !$subField->options) continue;

                                /* 先把数据的导入值和字段选项数组的键匹配，如果匹配到了则取键作为数据的值，否则和字段选项数组的值匹配，如果匹配到了则取值对应的键作为数据的值，如还未匹配到则直接应用数据的导入值。 */
                                $subValue = isset($subField->options[$subValue]) ? $subValue : (in_array($subValue, $subField->options) ? array_search($subValue, $subField->options) : $subValue);

                                $dataList[$key][$field][$subModule][$subKey][$subField->field] = $subValue;
                            }
                        }
                    }
                }
                else
                {
                    if(!isset($fields[$flow->module][$field])) continue;

                    $field = $fields[$flow->module][$field];

                    if(!is_array($field->options) or !$field->options) continue;

                    /* 先把数据的导入值和字段选项数组的键匹配，如果匹配到了则取键作为数据的值，否则和字段选项数组的值匹配，如果匹配到了则取值对应的键作为数据的值，如还未匹配到则直接应用数据的导入值。 */
                    $value = isset($field->options[$value]) ? $value : (in_array($value, $field->options) ? array_search($value, $field->options) : $value);

                    $dataList[$key][$field->field] = $value;
                }
            }
        }

        return array($fields, $dataList);
    }

    /**
     * Check the import data and set default value of the empty property.
     *
     * @param  object $flow
     * @param  array  $titles
     * @param  array  $dataList
     * @param  array  $defaultValues
     * @access public
     * @return array
     */
    public function processDefaultValue($flow, $titles, $dataList, $defaultValues)
    {
        /* 检查是否有导入设置中的字段没有被赋值，如有则赋值为该字段的默认值。*/
        foreach($dataList as $key => $data)
        {
            foreach($titles as $field => $fieldName)
            {
                if($field == 'sub_tables')
                {
                    foreach($fieldName as $subModule => $subTable)
                    {
                        if(empty($subTable['fields'])) continue;
                        foreach($subTable['fields'] as $subField => $subFieldName)
                        {
                            if(!isset($data['sub_tables'])) continue;

                            foreach($data['sub_tables'][$subModule] as $subKey => $subData)
                            {
                                if(!isset($subData[$subField])) $dataList[$key]['sub_tables'][$subModule][$subKey][$subField] = $defaultValues[$subModule][$subField];
                            }
                        }
                    }
                }
                else
                {
                    if(!isset($data[$field])) $dataList[$key][$field] = $defaultValues[$flow->module][$field];
                }
            }
        }

        return $dataList;
    }

    /**
     * Check the import data and remove the empty one.
     *
     * @param  array  $dataList
     * @access public
     * @return array
     */
    public function processEmptyData($dataList)
    {
        /* 检查是否有整条数据都为空的情况，如有则删除该条数据。*/
        foreach($dataList as $key => $data)
        {
            $emptyData = true;

            foreach($data as $field => $value)
            {
                if($field == 'sub_tables')
                {
                    foreach($value as $subModule => $subDatas)
                    {
                        foreach($subDatas as $subKey => $subData)
                        {
                            foreach($subData as $subField => $subValue)
                            {
                                if($subValue)
                                {
                                    $emptyData = false;
                                    break;  // 逐层跳出循环，提高检查性能。
                                }
                            }

                            if(!$emptyData) break;  // 逐层跳出循环，提高检查性能。
                        }

                        if(!$emptyData) break;  // 逐层跳出循环，提高检查性能。
                    }
                }
                else
                {
                    if($value) $emptyData = false;
                }

                if(!$emptyData) break;  // 逐层跳出循环，提高检查性能。
            }

            if($emptyData) unset($dataList[$key]);
        }

        return $dataList;
    }

    /**
     * Process the fields of imported data.
     *
     * @param  array  $fields
     * @param  array  $dataList
     * @access public
     * @return array
     */
    public function processImportFields($fields, $dataList)
    {
        /* Construct the data array to get real options of fields. */
        $subDataList = array();
        foreach($dataList as $key => $data)
        {
            foreach($data as $field => $value)
            {
                if($field == 'sub_tables')
                {
                    foreach($value as $subModule => $subDatas)
                    {
                        if(isset($subDataList[$subModule]))
                        {
                            $subDataList[$subModule] = array_merge($subDataList[$subModule], $subDatas);
                        }
                        else
                        {
                            $subDataList[$subModule] = $subDatas;
                        }
                    }
                }
            }
        }

        /* Get the real options of fields. */
        $this->loadModel('workflowaction', 'flow');
        foreach($fields as $module => $moduleFields) $moduleFields = $this->workflowaction->processFields($moduleFields, true, zget($subDataList, $module, array()));

        return $fields;
    }

    /**
     * Process the post data.
     *
     * @param  objetc $action
     * @param  array  $fields
     * @param  string $editorFields
     * @param  object $data
     * @access public
     * @return object
     */
    public function processPostData($action, $fields, $editorFields, $data)
    {
        if($action->open != 'none')
        {
            /* Reset the show property of a field according by the linkages. */
            /* 根据界面联动设置重置字段的是否显示属性。防止界面联动把radio或者checkbox隐藏后，后面的代码又对其赋值而导致的错误。*/
            $uiID     = $this->loadModel('workflowlayout')->getUIByData($action->module, $action->action, $data);
            $linkages = zget($action->linkages, $uiID, array());
            foreach($linkages as $linkage)
            {
                $sources = zget($linkage, 'sources', array());
                $targets = zget($linkage, 'targets', array());

                if(!$linkage or !$sources or !$targets) continue;

                foreach($sources as $source)
                {
                    if(!isset($fields[$source->field])) continue;
                    if(!isset($data->{$source->field})) continue;

                    $dataValue = $data->{$source->field};
                    $condition = false;

                    if($source->operator == '==') $condition = $dataValue == $source->value;
                    if($source->operator == '!=') $condition = $dataValue != $source->value;
                    if($source->operator == '>')  $condition = $dataValue >  $source->value;
                    if($source->operator == '>=') $condition = $dataValue >= $source->value;
                    if($source->operator == '<')  $condition = $dataValue <  $source->value;
                    if($source->operator == '<=') $condition = $dataValue <= $source->value;

                    foreach($targets as $target)
                    {
                        if(!isset($fields[$target->field])) continue;

                        $targetField = $fields[$target->field];

                        if($condition)
                        {
                            if($target->status == 'show') $targetField->show = 1;
                            if($target->status == 'hide') $targetField->show = 0;
                        }

                        $fields[$target->field] = $targetField;
                    }
                }
            }

            $notEmptyRule = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');
            foreach($fields as $field)
            {
                if(!$field->show or $field->readonly)
                {
                    if(isset($data->{$field->field})) unset($data->{$field->field});
                    continue;
                }

                /* 检查附件是否为空 */
                if($field->control == 'file' && isset($field->layoutRules))
                {
                    $rules = explode(',', trim($field->layoutRules, ','));
                    $rules = array_unique($rules);
                    foreach($rules as $rule)
                    {
                        if(empty($rule)) continue;
                        $rule = $this->workflowrule->getByID($rule);

                        /* 附件比较特殊，只检查必填项，其他规则不检查，即使用户设置了。*/
                        if($rule->rule == 'notempty')
                        {
                            $files = $this->file->getUpload($field->field);
                            if(empty($files)) dao::$errors[$field->field][] = sprintf($this->lang->flow->filesNotEmpty, $field->name);
                        }
                    }
                }

                if(isset($data->{$field->field}))
                {
                    if($field->options && is_string($field->options) && strpos(',user,dept,', ",$field->options,") !== false)
                    {
                        if(!is_array($data->{$field->field})) $data->{$field->field} = explode(',', $data->{$field->field});
                        foreach($data->{$field->field} as $key => $value)
                        {
                            $data->{$field->field}[$key] = $this->workflowhook->getParamRealValue($value);
                        }
                    }

                    /* If data value is array, implode by comma. */
                    if(is_array($data->{$field->field}))
                    {
                        $dataValue = array_values(array_unique(array_filter($data->{$field->field})));
                        asort($dataValue);
                        $data->{$field->field} = implode(',', $dataValue);
                    }

                    /* 如果是数字控件并且传的值不为0并且不要求必填时设置值为'0'。*/
                    /* If post data's control is numberic and its value is not '0' and it is not required, set the value as '0'. */
                    if(empty($data->{$field->field}) && $data->{$field->field} != '0' && in_array($field->type, $this->config->workflowfield->numberTypes))
                    {
                        if($notEmptyRule && strpos(",$field->rules,", ",$notEmptyRule->id,") === false) $data->{$field->field} = '0';
                    }
                }
                else
                {
                    if(strpos(',radio,checkbox,multi-select,', ",$field->control,") !== false) $data->{$field->field} = '';
                }
            }
        }

        $skip = 'uid';
        foreach($data as $field => $value)
        {
            /* Skip files. */
            if((isset($fields[$field]) && $fields[$field]->control == 'file') || (!isset($fieldsFields[$field]) && strpos(',renameFiles,deleteFiles,', ",$field,") !== false))
            {
                $skip .= ',' . $field;
            }
            /* If the field don't show in view, skip it. */
            elseif(empty($fields[$field]->show))
            {
                unset($data->$field);
                $skip .= ',' . $field;
            }
            else
            {
                $data->$field = $value;
            }

            if(in_array($value, $this->config->flow->variables)) $data->$field = $this->workflowhook->getParamRealValue($value);
        }

        if($editorFields) $data = $this->file->processImgURL($data, $editorFields, $this->post->uid);

        return array($data, $skip);
    }

    /**
     * Process data from db.
     *
     * @param  string $module
     * @param  array  $data
     * @param  bool   $decode
     * @access public
     * @return object
     */
    public function processDBData($module, $data, $decode = true)
    {
        static $fields = array();
        if(empty($fields[$module])) $fields[$module] = $this->loadModel('workflowfield', 'flow')->getControlPairs($module);

        $editorFields = array();
        $this->loadModel('file');

        foreach($fields[$module] as $field => $control)
        {
            if($decode && $control == 'multi-select' or $control == 'checkbox') $data->$field = explode(',', $data->$field);
            if($control == 'date' or $control == 'datetime') $data->$field = formatTime($data->$field);
            if($control == 'richtext') $editorFields[] = $field;
        }
        if($editorFields) $data = $this->file->replaceImgURL($data, $editorFields);

        return $data;
    }

    /**
     * Remove the field which will not be exported to the excel and process the value of field to real.
     *
     * @param  array  $datas
     * @param  array  $fields
     * @param  array  $exportFields
     * @access public
     * @return array
     */
    public function processExportDatas($datas, $fields, $exportFields)
    {
        $fields = $this->loadModel('workflowaction', 'flow')->processFields($fields, true, $datas);
        foreach($datas as $data)
        {
            foreach($data as $key => $value)
            {
                if(!isset($exportFields[$key]))
                {
                    unset($data->$key);
                    continue;
                }

                if(!isset($fields[$key])) continue;

                $field = $fields[$key];
                if(!is_array($field->options)) continue;

                if($field->control == 'multi-select' or $field->control == 'checkbox')
                {
                    $values = explode(',', $value);
                    foreach($values as $k => $v)
                    {
                        $values[$k] = zget($field->options, $v);
                    }
                    $data->$key = implode(',', array_unique(array_filter($values)));
                }
                else
                {
                    $optionValue = zget($field->options, $value);
                    if($field->control == 'datetime' || $field->control == 'date') $optionValue = formatTime($optionValue, DT_DATETIME1);
                    $data->$key = $optionValue;
                }
            }
        }

        return $datas;
    }

    /**
     * Process merge cells.
     * 处理合并单元格的数组，把其中每一个单元格的相关信息解析出来。
     *
     * @param  array  $mergeCells
     * @access public
     * @return array
     */
    public function processMergeCells($mergeCells)
    {
        $cells = array();

        foreach($mergeCells as $key => $mergeCell)
        {
            preg_match_all('|([A-Z]+)([0-9]+)|', $mergeCell, $matches);

            $beginColumn = $matches[1][0];  // 起始列。
            $beginRow    = $matches[2][0];  // 起始行。

            $endColumn = $matches[1][1];    // 终止列。
            $endRow    = $matches[2][1];    // 终止行。

            $parent  = $matches[0][0];  // 合并单元格中的第一个单元格。
            $colspan = ord($endColumn) - ord($beginColumn) + 1; // 跨列数。
            $rowspan = $endRow - $beginRow + 1;                 // 跨行数。

            $index = 1;
            $endColumn++;

            /* 遍历合并单元格的区域，解析相关数据。*/
            while($beginColumn != $endColumn)   // 遍历列。
            {
                for($row = $beginRow; $row <= $endRow; $row++)  // 遍历行。
                {
                    $cell = new stdclass();
                    $cell->cell    = $beginColumn . $row;
                    $cell->parent  = $parent;                   // 被合并到哪个单元格。
                    $cell->colspan = $index == 1 ? $colspan : 0;
                    $cell->rowspan = $index == 1 ? $rowspan : 0;

                    $cells[$cell->cell] = $cell;

                    $index++;
                }

                $beginColumn++;
            }

        }

        return $cells;
    }

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
        $this->loadModel('action');
        $this->loadModel('file');
        $this->loadModel('workflow', 'flow');
        $this->loadModel('workflowaction', 'flow');
        $this->loadModel('workflowfield', 'flow');
        $this->loadModel('workflowhook', 'flow');
        $this->loadModel('workflowrule', 'flow');

        $uiID    = empty($dataID) ? 0 : $this->loadModel('workflowlayout')->getUIByDataID($action->module, $action->action, $dataID);
        $oldData = $this->getDataByID($flow, $dataID);
        $fields  = $this->workflowaction->getFields($flow->module, $action->action, false, null, $uiID, $flow->group);

        /* Get editor fields. */
        $editorFields = array();
        $dateFields   = array();
        foreach($fields as $field)
        {
            if($field->control == 'richtext') $editorFields[] = $field->field;
            if($field->control == 'date' || $field->control == 'datetime') $dateFields[$field->field] = $field->field;
        }
        $editorFields = implode(',', $editorFields);

        /* Get data from post by fixer.*/
        $data = fixer::input('post')
            ->stripTags($editorFields, $this->config->allowedTags)
            ->remove('file,children,approval_reviewer,approval_ccer')
            ->removeIF($action->position == 'menu', 'dataID')
            ->get();

        /* Remove files and labels field when uploading files. */
        foreach($data as $fieldName => $fieldValue)
        {
            if((strpos($fieldName, 'files') !== false) or (strpos($fieldName, 'labels') !== false)) unset($data->$fieldName);
            if(isset($dateFields[$fieldName]) && empty($fieldValue)) unset($data->$fieldName);
        }

        foreach($fields as $field)
        {
            if(!$field->show) continue;

            if(strpos(',checkbox,radio,', ",{$field->control},") !== false && $field->readonly) $data->{$field->field} = $oldData->{$field->field};
            if(strpos(',multi-select,checkbox,radio,', ",{$field->control},") !== false && !isset($data->{$field->field})) $data->{$field->field} = in_array($field->type, $this->config->workflowfield->numberTypes) ? 0 : '';
            if(strpos(',multi-select,checkbox,', ",{$field->control},") !== false && !empty($data->{$field->field} && is_array($data->{$field->field}))) $data->{$field->field} = implode(',', $data->{$field->field});
        }

        $result = $this->operate($flow, $action, $fields, $editorFields, $data, $dataID);
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        if(isset($result['result']) && $result['result'] == 'fail') return $result;

        if($action->method == 'create')
        {
            $dataID = $result['dataID'];
            $data   = $this->getDataByID($flow, $dataID);
            if(!$data)
            {
                $message = !empty($result['message']) ? $result['message'] : $this->lang->fail;
                return array('result' => 'fail', 'message' => $message);
            }
        }

        /* Link the created datas. */
        if($action->method == 'create' && $prevModule)
        {
            $relation = $this->loadModel('workflowrelation', 'flow')->getByPrevAndNext($prevModule, $flow->module);
            if($relation && strpos(",$relation->actions,", ',many2one') !== false)
            {
                $unlinkedIDList = $data->{$relation->field};
                /* If the relation field is posted in a hidden input, it had been encoded. */
                if(!is_array($unlinkedIDList)) $unlinkedIDList = explode(',', $unlinkedIDList);

                if($unlinkedIDList) $this->link($flow, $dataID, $prevModule, $unlinkedIDList);
            }
        }

        $this->file->updateObjectID($this->post->uid, $dataID, $flow->module);

        $allFiles = array();
        foreach($fields as $field)
        {
            if($field->control == 'file')
            {
                $files = $this->file->saveUpload($flow->module, $dataID, $field->field, $field->field);
                if($files) $allFiles = arrayUnion($allFiles, $files);
            }
        }
        $fileAction = $allFiles ? $this->lang->addFiles . join(',', $allFiles) : '';

        /* Create action. */
        $actionID = isset($result['actionID']) ? $result['actionID'] : 0;
        if($action->method == 'create') $actionID = $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, $action->action, $fileAction);
        if($action->method != 'create' && (!empty($result['changes']) or $fileAction))
        {
            if(strpos(',approvalsubmit,approvalcancel,approvalreview,', ',' . $action->action . ',') === false) $actionID = $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, $action->action, $fileAction);
            $this->action->logHistory($actionID, $result['changes']);
        }

        $message = !empty($result['message']) ? $result['message'] : $this->lang->saveSuccess;
        if($action->open == 'none') return array('result' => 'success', 'recordID' => $dataID, 'actionID' => $actionID, 'load' => true);

        $locate = $this->post->referer ? helper::safe64Decode($this->post->referer) : helper::createLink($flow->module, 'browse');
        return array('result' => 'success', 'message' => $message, 'recordID' => $dataID, 'actionID' => $actionID, 'load' => $locate);
    }

    /**
     * Post data of a flow.
     *
     * @param  object $flow
     * @param  object $action
     * @param  string $method   The method which called this function.
     * @access public
     * @return array
     */
    public function batchPost($flow, $action, $method = '')
    {
        $this->loadModel('action');
        $this->loadModel('file');
        $this->loadModel('workflow', 'flow');
        $this->loadModel('workflowaction', 'flow');
        $this->loadModel('workflowfield', 'flow');
        $this->loadModel('workflowhook', 'flow');
        $this->loadModel('workflowrule', 'flow');

        $errorList  = array();
        $recordList = array();
        $actionList = array();
        $fields     = $this->workflowaction->getFields($flow->module, $action->action, false);
        $dataList   = $this->getPostData($flow, $action);

        foreach($dataList as $key => $data)
        {
            $data   = (object)$data;
            $dataID = $action->method == 'batchcreate' ? 0 : $key;

            $result = $this->operate($flow, $action, $fields, '', $data, $dataID);
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
                        if($action->batchMode == 'same')      $errorKey = 'data' . $field;
                        if($action->batchMode == 'different') $errorKey = 'dataList' . $key . $field;

                        $errorList[$errorKey] = $message;
                    }
                }
                continue;
            }

            if(isset($result['result']) && $result['result'] == 'fail' && isset($result['message']))
            {
                $errorList['error' . $key] = $result['message'];
                continue;
            }

            if($action->method == 'batchcreate')
            {
                $dataID = $result['dataID'];
                $data   = $this->getDataByID($flow, $dataID);
                if(!$data)
                {
                    $message = !empty($result['message']) ? $result['message'] : $this->lang->fail;
                    $errorList['error' . $key] = $message;
                    continue;
                }
            }

            /* Create action. */
            $actionID = $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, $method ? $method : ($action->action == 'batchcreate' ? 'create' : $action->action));
            if($action->method != 'batchcreate' && !empty($result['changes']))
            {
                $this->action->logHistory($actionID, $result['changes']);
            }

            $recordList[$key] = $dataID;
            $actionList[$key] = $actionID;
        }

        if($errorList)
        {
            /* If there are errors, delete the created datas. */
            if($action->method == 'batchcreate')
            {
                /* Didn't delete the sub tables. */
                if($recordList) $this->dao->delete()->from($flow->table)->where('id')->in($recordList)->exec();
                if($actionList) $this->dao->delete()->from(TABLE_ACTION)->where('id')->in($actionList)->exec();
                if($actionList) $this->dao->delete()->from(TABLE_HISTORY)->where('action')->in($actionList)->exec();
            }

            return array('result' => 'fail', 'message' => $errorList);
        }

        $message = !empty($result['message']) ? $result['message'] : $this->lang->saveSuccess;
        $locate  = helper::createLink($flow->module, 'browse');
        if($action->open == 'none') return array('result' => 'success', 'message' => $message, 'load' => $locate, 'recordList' => $recordList, 'actionList' => $actionList);
        return array('result' => 'success', 'message' => $message, 'recordList' => $recordList, 'actionList' => $actionList, 'load' => $locate, 'locate' => $locate);
    }

    /**
     * Operate.
     *
     * @param  object $flow
     * @param  object $action
     * @param  array  $fields
     * @param  string $editorFields
     * @param  object $data
     * @param  int    $dataID
     * @access public
     * @return void
     */
    public function operate($flow, $action, $fields, $editorFields, $data, $dataID = 0)
    {
        if($action->position == 'menu' && $action->method != 'create' && $action->method != 'batchcreate' && $this->post->dataID == '')
        {
            dao::$errors['dataID'] = sprintf($this->lang->error->notempty, $flow->name);
            return false;
        }

        list($data, $skip) = $this->processPostData($action, $fields, $editorFields, $data);

        if($action->method == 'create' or $action->method == 'batchcreate')
        {
            return $this->create($flow, $action, $fields, $data, $skip);
        }
        else
        {
            return $this->update($flow, $action, $fields, $data, $skip, $dataID);
        }
    }

    /**
     * Create a record of a flow.
     *
     * @param  object $flow
     * @param  object $action
     * @param  array  $fields
     * @param  object $data
     * @param  string $skip
     * @access public
     * @return bool | array
     */
    public function create($flow, $action, $fields, $data, $skip)
    {
        $createdBy   = zget($this->config->flow->defaultFields->createdBy,   $flow->module, 'createdBy');
        $createdDate = zget($this->config->flow->defaultFields->createdDate, $flow->module, 'createdDate');

        if($createdBy)   $data->$createdBy   = $this->app->user->account;
        if($createdDate) $data->$createdDate = helper::now();

        if(!empty($action->verifications))
        {
            $canExec = true;
            if($action->verifications->type == 'sql')
            {
                $sql = $action->verifications->sql;
                foreach($action->verifications->sqlVars as $sqlVar)
                {
                    if($sqlVar->paramType == 'form')
                    {
                        if(!isset($data->{$sqlVar->param}))
                        {
                            $canExec = false;
                            break;
                        }
                        $param = $data->{$sqlVar->param};
                    }
                    elseif(!empty($sqlVar->paramType) && strpos(',today,now,actor,deptManager,', ",$sqlVar->paramType,") !== false)
                    {
                        $param = $this->workflowhook->getParamRealValue($sqlVar->paramType);
                    }
                    $sql = str_replace("'$" . $sqlVar->varName . "'", $this->dbh->quote($param), $sql);
                }
                $sql = $this->workflowfield->replaceTableNames($sql);

                try
                {
                    $sqlResult = $this->dbh->query($sql)->fetch();
                    if($action->verifications->sqlResult == 'empty')
                    {
                        $canExec = empty($sqlResult);
                    }
                    elseif($action->verifications->sqlResult == 'notempty')
                    {
                        $canExec = !empty($sqlResult);
                    }
                }
                catch(PDOException $exception)
                {
                    $canExec = false;
                }
            }
            elseif($action->verifications->type == 'data')
            {
                unset($canExec);
                foreach($action->verifications->fields as $verification)
                {
                    if(!$verification->field || !$verification->operator) continue;

                    /* 数据校验中的字段数据源不包含表单数据和当前数据，仅需要判断下列3个类型。 */
                    /* The datasource of field in verifycation doesn't contain form data and current data. Just process the flow 3 types.*/
                    if(!empty($verification->paramType) && strpos(',today,now,actor,deptManager,', ",$verification->paramType,") !== false)
                    {
                        $verification->param = $this->workflowhook->getParamRealValue($verification->paramType);
                    }

                    $checkFunc = 'check' . $verification->operator;
                    $curResult = isset($data->{$verification->field}) ? validater::$checkFunc($data->{$verification->field}, $verification->param) : false;

                    if(!isset($canExec)) $canExec = !$curResult;
                    $canExec = $verification->logicalOperator == 'and' ? ($canExec || !$curResult) : ($canExec && !$curResult);
                }
                if(!isset($canExec)) $canExec = true;
            }
            if(!$canExec) return array('result' => 'fail', 'message' => !empty($action->verifications->message) ? $action->verifications->message : $this->lang->fail);
        }

        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
        $ruleList     = $this->workflowrule->getList();

        $dao = $this->dao->insert($flow->table)->data($data, $skip);
        $dao = $this->checkRules($fields, $data, $dao, 0, $notEmptyRule, $ruleList);
        $dao->autoCheck()->exec();
        if(dao::isError()) return false;

        $dataID = $this->dao->lastInsertID();

        /* Update the children data. */
        if($this->post->children)
        {
            $postData = fixer::input('post')->get();
            $errors   = $this->updateChildrenData($flow, $action, $postData->children, $dataID, $notEmptyRule, $ruleList);
            if($errors)
            {
                $this->dao->delete()->from($flow->table)->where('id')->eq($dataID)->exec();

                dao::$errors = $errors;
                return false;
            }
        }

        $message = $this->workflowhook->execute($flow, $action, $dataID);
        if(dao::isError()) return false;

        return array('dataID' => $dataID, 'result' => empty($dataID) ? 'fail' : 'success', 'message' => $message);
    }

    /**
     * Update a record of a flow.
     *
     * @param  object $flow
     * @param  object $action
     * @param  array  $fields
     * @param  object $data
     * @param  string $skip
     * @param  int    $dataID
     * @access public
     * @return bool | array
     */
    public function update($flow, $action, $fields, $data, $skip, $dataID)
    {
        $oldData = $this->getDataByID($flow, $dataID, $decode = false);
        $account = $this->app->user->account;
        $now     = helper::now();

        $editedBy   = zget($this->config->flow->defaultFields->editedBy,   $flow->module, 'editedBy');
        $editedDate = zget($this->config->flow->defaultFields->editedDate, $flow->module, 'editedDate');

        if($editedBy)   $data->$editedBy   = $account;
        if($editedDate) $data->$editedDate = $now;
        $data->id = $oldData->id;

        /* Auto save who and when did the action. */
        if($action->action != 'edit' && $action->action != 'batchedit')
        {
            $actionFields = $this->workflowfield->getList($flow->module);

            $actionCode = $action->action;
            if($action->action == 'assign' or $action->action == 'batchassign') $actionCode = 'assigned';

            foreach($actionFields as $actionField)
            {
                if(strtolower($actionField->field) == strtolower($actionCode . 'By'))   $data->{$actionField->field} = $account;
                if(strtolower($actionField->field) == strtolower($actionCode . 'Date')) $data->{$actionField->field} = $now;
            }
        }

        if(!empty($action->verifications))
        {
            $canExec = true;
            if($action->verifications->type == 'sql')
            {
                $sql = $action->verifications->sql;
                foreach($action->verifications->sqlVars as $sqlVar)
                {
                    if($sqlVar->paramType == 'form')
                    {
                        if(!isset($data->{$sqlVar->param}))
                        {
                            $canExec = false;
                            break;
                        }
                        $param = $data->{$sqlVar->param};
                    }
                    elseif($sqlVar->paramType == 'record')
                    {
                        if(!isset($oldData->{$sqlVar->param}))
                        {
                            $canExec = false;
                            break;
                        }
                        $param = $oldData->{$sqlVar->param};
                    }
                    elseif(!empty($sqlVar->paramType) && strpos(',today,now,actor,deptManager,', ",$sqlVar->paramType,") !== false)
                    {
                        $param = $this->workflowhook->getParamRealValue($sqlVar->paramType);
                    }
                    $sql = str_replace("'$" . $sqlVar->varName . "'", $this->dbh->quote($param), $sql);
                }
                $sql = $this->workflowfield->replaceTableNames($sql);

                try
                {
                    $sqlResult = $this->dbh->query($sql)->fetch();
                    if($action->verifications->sqlResult == 'empty')
                    {
                        $canExec = empty($sqlResult);
                    }
                    elseif($action->verifications->sqlResult == 'notempty')
                    {
                        $canExec = !empty($sqlResult);
                    }
                }
                catch(PDOException $exception)
                {
                    $canExec = false;
                }
            }
            elseif($action->verifications->type == 'data')
            {
                unset($canExec);
                foreach($action->verifications->fields as $verification)
                {
                    if(!$verification->field || !$verification->operator) continue;

                    /* 数据校验中的字段数据源不包含表单数据和当前数据，仅需要判断下列3个类型。 */
                    /* The datasource of field in verifycation doesn't contain form data and current data. Just process the flow 3 types.*/
                    if(!empty($verification->paramType) && strpos(',today,now,actor,deptManager,', ",$verification->paramType,") !== false)
                    {
                        $verification->param = $this->workflowhook->getParamRealValue($verification->paramType);
                    }

                    $value = isset($data->{$verification->field}) ? $data->{$verification->field} : $oldData->{$verification->field};

                    $checkFunc = 'check' . $verification->operator;
                    $curResult = validater::$checkFunc($value, $verification->param);

                    if(!isset($canExec)) $canExec = !$curResult;
                    $canExec = $verification->logicalOperator == 'and' ? ($canExec || !$curResult) : ($canExec && !$curResult);
                }
                if(!isset($canExec)) $canExec = true;
            }
            if(!$canExec) return array('result' => 'fail', 'message' => !empty($action->verifications->message) ? $action->verifications->message : $this->lang->fail);
        }

        if(!empty($this->config->openedApproval) && $flow->approval == 'enabled' && strpos(',approvalsubmit,approvalcancel,approvalreview,', ",{$action->action},") !== false)
        {
            /* Log first for approval send message. */
            $actionID = $this->loadModel('action')->create(zget($this->config->flow->realModule, $flow->module), $dataID, $action->action);

            $approval = $action->action;
            $result   = $this->$approval($flow, $data, $dataID, $fields);

            if(dao::isError()) return false;
            if(isset($result['result']) && $result['result'] == 'fail') return $result;
        }

        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
        $ruleList     = $this->workflowrule->getList();

        $dao = $this->dao->update("`$flow->table`")->data($data, $skip);
        $dao = $this->checkRules($fields, $data, $dao, $dataID, $notEmptyRule, $ruleList);
        $dao->where('id')->eq($dataID)->autoCheck()->exec();
        if(dao::isError()) return false;

        $this->loadModel('file');
        foreach($fields as $field)
        {
            if($field->control === 'file') $this->file->processFileDiffsForObject($flow->module, $oldData, $data, '', $field->field . 'files');
        }

        /* Update the children data. */
        if($this->post->children)
        {
            $postData = fixer::input('post')->get();
            $errors   = $this->updateChildrenData($flow, $action, $postData->children, $dataID, $notEmptyRule, $ruleList);
            if($errors)
            {
                foreach($fields as $field) if($field->control === 'file') unset($oldData->{$field->field . 'files'});
                $this->dao->update($flow->table)->data($oldData)->where('id')->eq($dataID)->exec();

                dao::$errors = $errors;
                return false;
            }
        }

        $message = $this->workflowhook->execute($flow, $action, $dataID);
        if(dao::isError()) return false;

        $data = $this->getDataByID($flow, $dataID, $decode = false);
        if(!$data) return true;

        return array('changes' => commonModel::createChanges($oldData, $data), 'message' => $message, 'actionID' => isset($actionID) ? $actionID : 0);
    }

    /**
     * Submit an approval.
     *
     * @param  object     $flow
     * @param  object     $data
     * @param  int        $dataID
     * @param  array      $fields
     * @access public
     * @return bool|array
     */
    public function approvalSubmit($flow, &$data, $dataID, $fields)
    {
        $reviewers = $this->post->approval_reviewer ? $this->post->approval_reviewer : array();
        $ccers     = $this->post->approval_ccer     ? $this->post->approval_ccer     : array();
        $idList    = $this->post->approval_id       ? $this->post->approval_id       : array();

        $reviewerList = array();
        if($reviewers)
        {
            foreach($reviewers as $key => $reviewer)
            {
                $nodeID = $idList[$key];
                $reviewerList[$nodeID] = $reviewer;
            }
        }

        $ccerList = array();
        if($ccers)
        {
            foreach($ccers as $key => $ccer)
            {
                $nodeID = $idList[$key];
                $ccerList[$nodeID] = $ccer;
            }
        }

        $this->loadModel('approval')->checkReviewer(0, $flow->module, $reviewerList);
        if(dao::isError()) return false;

        $groupID = $this->loadModel('workflowgroup')->getGroupIDByDataID($flow->module, $data->id);
        $result  = $this->loadModel('approval')->createApprovalObject($groupID, $dataID, $flow->module, $reviewerList, $ccerList, $idList);
        if($result === false || empty($result['approvalID'])) return array('result' => 'fail', 'message' => $this->lang->flow->error->approval);

        $data->reviewers     = '';
        $data->reviewOpinion = '';
        $data->reviewResult  = '';
        $data->reviewStatus  = 'doing';
        $data->approval      = $result['approvalID'];

        if($result['result'] === 'pass')
        {
            $data->reviewResult = 'pass';
            $data->reviewStatus = 'pass';
        }
        if($result['result'] === 'fail')
        {
            $data->reviewResult = 'reject';
            $data->reviewStatus = 'reject';
        }

        if(!empty($result['approvalID']))
        {
            $reviewers = $this->approval->getCurrentReviewers($result['approvalID']);
            $data->reviewers = implode(',', $reviewers);
        }

        return true;
    }

    /**
     * Cancel an approval.
     *
     * @param  object     $flow
     * @param  object     $data
     * @param  int        $dataID
     * @param  array      $fields
     * @access public
     * @return bool|array
     */
    public function approvalCancel($flow, &$data, $dataID, $fields)
    {
        $data->reviewers     = '';
        $data->reviewOpinion = '';
        $data->reviewResult  = '';
        $data->reviewStatus  = 'wait';

        $this->loadModel('approval')->cancel($flow->module, $dataID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return true;
    }

    /**
     * Review an approval.
     *
     * @param  object     $flow
     * @param  object     $data
     * @param  int        $dataID
     * @param  array      $fields
     * @access public
     * @return bool|array
     */
    public function approvalReview($flow, &$data, $dataID, $fields)
    {
        $approval        = $this->loadModel('approval')->getByObject($flow->module, $dataID);
        $reviewerAccount = $this->approval->getReviewerByApprovalID($approval->id);
        if(strpos(',' . implode(',', $reviewerAccount) . ',', ',' . $this->app->user->account . ',') === false) dao::$errors['message'] = $this->lang->approval->cannotOperate;
        if(dao::isError()) return false;

        $approval = new stdclass();
        $approval->id          = $dataID;
        $approval->opinion     = $this->post->reviewOpinion;
        $approval->createdDate = helper::today();
        $approval->setReviewer = !empty($_POST['setReviewer']) ? $this->post->setReviewer : '';

        $fields = $this->loadModel('approval')->setApprovalFields($fields, $flow->module, $dataID);
        if(!empty($fields['setReviewer']->required)   && isset($_POST['setReviewer'])   && empty($approval->setReviewer)) dao::$errors['setReviewer']   = sprintf($this->lang->error->notempty, $fields['setReviewer']->name);
        if(!empty($fields['reviewOpinion']->required) && isset($_POST['reviewOpinion']) && empty($approval->opinion))
        {
            dao::$errors['reviewOpinion'] = sprintf($this->lang->error->notempty, $fields['reviewOpinion']->name);
        }
        else if($this->post->reviewResult == 'reject' && isset($_POST['reviewOpinion']) && empty($approval->opinion))
        {
            dao::$errors['reviewOpinion'] = sprintf($this->lang->error->notempty, $fields['reviewOpinion']->name);
        }
        if(dao::isError()) return false;

        $reviewResult = $this->post->reviewResult;
        $reviewFunc   = $this->post->reviewResult;
        $result       = $this->approval->$reviewFunc($flow->module, $approval);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $approval  = $this->approval->getByObject($flow->module, $dataID);
        $reviewers = $this->approval->getCurrentReviewers($approval->id);

        if(is_array($result))
        {
            $result['result'] = $result['result'] == 'fail' ? 'reject' : '';
            $data->reviewResult = $result['finished'] ? $result['result'] : '';
            $data->reviewStatus = $result['finished'] ? $result['result'] : 'doing';
        }
        else
        {
            if($result === true)
            {
                $data->reviewResult = $approval->result == 'fail' ? 'reject' : 'pass';
                $data->reviewStatus = $approval->result == 'fail' ? 'reject' : 'pass';
            }
            else
            {
                $data->reviewResult  = '';
                $data->reviewStatus  = 'doing';
            }
        }

        $data->reviewOpinion = $reviewResult == 'reject' || $this->post->reviewOpinion ? $this->post->reviewOpinion : '';
        $data->reviewers     = implode(',', $reviewers);

        return true;
    }

    /**
     * Update children's data.
     *
     * @param  object $flow
     * @param  object $action
     * @param  array  $children
     * @param  int    $parentID
     * @param  object $notEmptyRule
     * @param  array  $ruleList
     * @access public
     * @return array
     */
    public function updateChildrenData($flow, $action, $children = array(), $parentID = 0, $notEmptyRule = null, $ruleList = array())
    {
        $uiID = $this->loadModel('workflowlayout')->getUIByDataID($flow->module, $action->action, $parentID);

        $subTables       = $this->loadModel('workflow')-> getPairs($flow->module, 'table', '', 'normal');
        $subEditorFields = array();
        if($subTables) $subEditorFields = $this->dao->select('module,field')->from(TABLE_WORKFLOWFIELD)->where('module')->in(array_keys($subTables))->andWhere('control')->eq('richtext')->fetchGroup('module', 'field');

        $errors = array();
        foreach($children as $childModule => $child)
        {
            $childModule = str_replace('sub_', '', $childModule);

            $flow   = $this->workflow->getByModule($childModule, false, $flow->group);
            $fields = $this->workflowaction->getFields($flow->module, $action->action, true, null, $uiID, $flow->group);

            $datas = array();
            if($action->method != 'create')
            {
                $datas = $this->dao->select('*')->from($flow->table)->where('deleted')->eq('0')
                    ->beginIF(!$flow->buildin && $parentID)->andWhere('parent')->eq($parentID)->fi()
                    ->fetchAll('id', false);
            }

            $this->loadModel('workflowhook');

            $createIDList = array();
            $updateIDList = array();
            foreach($child['id'] as $key => $id)
            {
                $dataIsEmpty = true;

                if(isset($subEditorFields[$childModule]))
                {
                    foreach($subEditorFields[$childModule] as $editorField => $subFields) $child[$editorField][$key] = fixer::stripDataTags($this->post->children["sub_{$childModule}"][$editorField][$key], $this->config->allowedTags);
                }

                /* Get post data for each field. */
                $data = new stdclass();
                foreach($fields as $field)
                {
                    if($field->field != 'id' && (!$field->show or $field->readonly)) continue;

                    if(isset($child[$field->field][$key]))
                    {
                        $childValue = $child[$field->field][$key];
                        if(($field->control == 'date' || $field->control == 'datetime') && empty($childValue)) $childValue = null;

                        if(is_array($childValue))
                        {
                            foreach($childValue as $index => $value)
                            {
                                if(in_array($value, $this->config->flow->variables)) $childValue[$index] = $this->workflowhook->getParamRealValue($value);
                            }
                            $childValue = implode(',', array_values(array_unique(array_filter($childValue))));
                        }
                        elseif(in_array($childValue, $this->config->flow->variables))
                        {
                            $childValue = $this->workflowhook->getParamRealValue($childValue);
                        }

                        if(isset($this->config->workflowfield->typeList['integer'][$field->type])) $childValue = (int)  $childValue;
                        if(isset($this->config->workflowfield->typeList['decimal'][$field->type])) $childValue = (float)$childValue;

                        if($childValue) $dataIsEmpty = false;
                        $data->{$field->field} = $childValue;
                    }
                    else
                    {
                        if(strpos(',radio,checkbox,multi-select,', ",$field->control,") !== false) $data->{$field->field} = '';
                    }
                }

                if($dataIsEmpty)
                {
                    $deleteIDList = array_diff(array_keys($datas), $updateIDList);

                    if($deleteIDList)
                    {
                        $this->dao->update($flow->table)->set('deleted')->eq('1')->where('parent')->eq($parentID)->andWhere('id')->in($deleteIDList)->exec();
                        foreach($deleteIDList as $dataID) $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, 'deletedSubTableData', '', isset($datas[$dataID]->parent) ? $datas[$dataID]->parent : '');
                    }
                    continue;
                }

                $data->parent = $parentID;

                if($data->id)
                {
                    /* 更新数据。*/
                    $oldData = zget($datas, $data->id, null);
                    if(!$oldData) continue;

                    $dao = $this->dao->update($flow->table)->data($data);
                    $dao = $this->checkRules($fields, $data, $dao, $data->id, $notEmptyRule, $ruleList);
                    $dao->where('id')->eq($data->id)->autoCheck()->exec();

                    if(!dao::isError())
                    {
                        $changes = commonModel::createChanges($oldData, $data);

                        if($changes)
                        {
                            $actionID = $this->action->create(zget($this->config->flow->realModule, $flow->module), $data->id, 'editedSubTableData', '', $data->parent);
                            $this->action->logHistory($actionID, $changes);
                        }

                        $updateIDList[] = $data->id;
                    }
                }
                else
                {
                    unset($data->id);

                    /* 插入数据。*/
                    $dao = $this->dao->insert($flow->table)->data($data);
                    $dao = $this->checkRules($fields, $data, $dao, 0, $notEmptyRule, $ruleList);
                    $dao->autoCheck()->exec();

                    if(!dao::isError())
                    {
                        $dataID = $this->dao->lastInsertId();

                        $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, 'createdSubTableData', '', $data->parent);

                        $createIDList[] = $dataID;
                    }
                }

                if(dao::isError())
                {
                    $daoErrors = dao::getError();

                    if(is_string($daoErrors)) continue;
                    if(is_array($daoErrors))
                    {
                        foreach($daoErrors as $field => $messages)
                        {
                            /* Set error key. */
                            $errorKey = 'childrensub_' . $childModule . $field . $key;

                            $errors[$errorKey] = $messages;
                        }
                    }
                }
            }

            if(!$createIDList && !$updateIDList) continue;

            if($errors)
            {
                /* Delete the new data. */
                if($createIDList)
                {
                    $this->dao->delete()->from($flow->table)
                        ->where('parent')->eq($parentID)
                        ->andWhere('id')->in($createIDList)
                        ->exec();
                }
                /* Restore the updated data. */
                if($updateIDList)
                {
                    foreach($updateIDList as $dataID)
                    {
                        $oldData = zget($datas, $dataID, null);
                        if(!$oldData) continue;

                        $this->dao->update($flow->table)->data($oldData)->where('id')->eq($dataID)->exec();
                    }
                }
            }
            else
            {
                /* Delete other data. */
                if($datas && $updateIDList)
                {
                    $deleteIDList = array_diff(array_keys($datas), $updateIDList);

                    if($deleteIDList)
                    {
                        $this->dao->update($flow->table)->set('deleted')->eq('1')
                            ->where('parent')->eq($parentID)
                            ->andWhere('id')->in($deleteIDList)
                            ->exec();

                        foreach($deleteIDList as $dataID) $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, 'deletedSubTableData', '', $data->parent);
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Link datas to a flow.
     *
     * @param  object $flow
     * @param  int    $dataID
     * @param  string $linkType
     * @param  array  $unlinkedIDList
     * @access public
     * @return bool
     */
    public function link($flow, $dataID, $linkType, $unlinkedIDList)
    {
        if(!$unlinkedIDList) return true;

        $this->loadModel('action');

        $flowDatas  = $this->getDataPairs($flow, $dataID);
        $linkedFlow = $this->loadModel('workflow', 'flow')->getByModule($linkType);
        if($linkedFlow)
        {
            $linkedApp    = $linkedFlow->app;
            $linkedModule = $linkedFlow->module;
            $linkedName   = $linkedFlow->name;
            $linkedDatas  = $this->getDataPairs($linkedFlow, $unlinkedIDList);
        }
        else if(in_array($linkType, $this->config->flow->linkPairs))
        {
            list($linkedApp, $linkedModule) = $this->extractAppAndModule($linkType);

            $linkedDatas = $this->loadModel($linkedModule, $linkedApp)->getPairsByIDList($unlinkedIDList);
            $linkedName  = $this->lang->$linkedModule->common;
        }

        $data = new stdclass();
        $data->objectType  = $flow->module;
        $data->objectID    = $dataID;
        $data->linkedType  = $linkType;
        $data->createdBy   = $this->app->user->account;
        $data->createdDate = helper::now();

        foreach($unlinkedIDList as $unlinkedID)
        {
            $data->linkedID = $unlinkedID;

            $this->dao->replace(TABLE_WORKFLOWLINKDATA)->data($data)->exec();

            $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, 'linked', '', zget($linkedDatas, $unlinkedID));
            $this->action->create($linkType, $unlinkedID, 'linkedto', '', zget($flowDatas, $dataID));
        }

        return !dao::isError();
    }

    /**
     * Unlink datas from a flow.
     *
     * @param  object $flow
     * @param  int    $dataID
     * @param  string $linkType
     * @param  array  $linkedIDList
     * @access public
     * @return bool
     */
    public function unlink($flow, $dataID, $linkType, $linkedIDList)
    {
        if(!$linkedIDList) return true;

        $this->loadModel('action');

        $this->dao->delete()->from(TABLE_WORKFLOWLINKDATA)
            ->where('objectType')->eq($flow->module)
            ->andWhere('objectID')->eq($dataID)
            ->andWhere('linkedType')->eq($linkType)
            ->andWhere('linkedID')->in($linkedIDList)
            ->exec();

        $flowDatas  = $this->getDataPairs($flow, $dataID);
        $linkedFlow = $this->loadModel('workflow', 'flow')->getByModule($linkType);
        if($linkedFlow)
        {
            $linkedApp    = $linkedFlow->app;
            $linkedModule = $linkedFlow->module;
            $linkedName   = $linkedFlow->name;
            $linkedDatas  = $this->getDataPairs($linkedFlow, $linkedIDList);
        }
        else if(in_array($linkType, $this->config->flow->linkPairs))
        {
            list($linkedApp, $linkedModule) = $this->extractAppAndModule($linkType);

            $linkedDatas = $this->loadModel($linkedModule, $linkedApp)->getPairsByIDList($linkedIDList);
            $linkedName  = $this->lang->$linkedModule->common;
        }

        foreach($linkedIDList as $linkedID)
        {
            $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, 'unlinked', '', zget($linkedDatas, $linkedID));
            $this->action->create($linkType, $linkedID, 'unlinkedfrom', '', zget($flowDatas, $dataID));
        }

        return !dao::isError();
    }

    /**
     * Import datas.
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

        $subTables = $this->dao->select('module, `table`')->from(TABLE_WORKFLOW)
            ->where('type')->eq('table')
            ->andWhere('parent')->eq($flow->module)
            ->fetchPairs();

        /* 导入数据。*/
        foreach($dataList as $key => $data)
        {
            foreach($data as $field => $value)
            {
                if(is_string($value)) $data[$field] = trim(nl2br($value));
            }

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
            $actionID = $this->action->create(zget($this->config->flow->realModule, $flow->module), $dataID, 'import');

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
                        foreach($subData as $field => $value)
                        {
                            if(is_string($value)) $subData[$field] = trim(nl2br($value));
                        }

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
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'recordList' => $recordList, 'actionList' => $actionList, 'locate' => $locate, 'load' => $locate);
    }

    /**
     * Delete empty data.
     *
     * @param  array  $dataList
     * @access public
     * @return array
     */
    public function deleteEmpty($dataList)
    {
        /* 检查是否有空数据，如有则删除该条数据。*/
        foreach($dataList as $key => $data)
        {
            unset($dataList[$key]['id']);
            unset($dataList[$key]['editedBy']);
            unset($dataList[$key]['editedDate']);

            $emptyData = true;

            /* 检查主流程数据是否有为空的情况。*/
            foreach($data as $field => $value)
            {
                if($field == 'sub_tables')
                {
                    /* 检查明细表数据是否有为空的情况。*/
                    foreach($value as $subModule => $subDatas)
                    {
                        foreach($subDatas as $subKey => $subData)
                        {
                            unset($dataList[$key]['sub_tables'][$subModule]['id']);
                            unset($dataList[$key]['sub_tables'][$subModule]['editedBy']);
                            unset($dataList[$key]['sub_tables'][$subModule]['editedDate']);

                            $emptySubData = true;

                            foreach($subData as $subField => $subValue)
                            {
                                if($subValue)
                                {
                                    $emptySubData = false;
                                    break;
                                }
                            }

                            if($emptySubData) unset($dataList[$key]['sub_tables'][$subModule][$subKey]);
                        }
                    }
                }
                else
                {
                    if($value)$emptyData = false;
                }
            }

            if($emptyData) unset($dataList[$key]);
        }

        return $dataList;
    }

    /**
     * Extract app name and module name.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function extractAppAndModule($module)
    {
        if(strpos($module, '.') === false) return array('', $module);
        if(strpos($module, '.') === false) return explode('.', $module);
    }

    /**
     * Set search params.
     *
     * @param  object $flow
     * @param  object $action       This param is used in other extensions. Don't remove it.
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function setSearchParams($flow, $action = null, $actionURL = '')
    {
        $fieldList = $this->loadModel('workflowfield', 'flow')->getList($flow->module, 'searchOrder, `order`, id');

        $canSearch = false;
        foreach($fieldList as $field)
        {
            if($field->canSearch)
            {
                $canSearch = true;
                break;
            }
        }
        if(!$canSearch) return false;

        $searchConfig = array();
        $searchConfig['module'] = $flow->module;

        $fieldValues = array();
        $formName    = $flow->module . 'Form';
        if($this->session->$formName)
        {
            foreach($this->session->$formName as $formKey => $formField)
            {
                if(strpos($formKey, 'field') !== false)
                {
                    $fieldNO      = substr($formKey, 5);
                    if(!is_numeric($fieldNO)) continue;

                    $fieldNO      = "value" . $fieldNO;
                    $formNameList = $this->session->$formName;
                    $fieldValue   = $formNameList[$fieldNO];

                    if($fieldValue) $fieldValues[$formField][$fieldValue] = $fieldValue;
                }
            }
        }

        foreach($fieldList as $field)
        {
            if(empty($field->canSearch)) continue;

            if(in_array($field->control, $this->config->workflowfield->optionControls))
            {
                $field->options = $this->workflowfield->getFieldOptions($field, true, zget($fieldValues, $field->field, ''), '', $this->config->flowLimit);
            }
            if(in_array($field->field, array('program', 'product', 'project', 'execution'))) $field->options = arrayUnion($field->options, array('all' => $this->lang->workflowfield->all[$field->field]));

            $searchConfig['fields'][$field->field] = $field->name;
            $searchConfig['params'][$field->field] = $this->processSearchParams($field->control, $field->options);
        }

        /* Build search form. */
        if(!$actionURL) $actionURL = helper::createLink($flow->module, 'browse', "mode=bysearch");
        $searchConfig['actionURL'] = $actionURL;

        if(!isset($this->config->{$flow->module})) $this->config->{$flow->module} = new stdclass();
        $this->config->{$flow->module}->search = $searchConfig;

        $this->loadModel('search')->setSearchParams($this->config->{$flow->module}->search);
    }

    /**
     * Process search field params.
     *
     * @param  string  $control
     * @param  array   $options
     * @access public
     * @return void
     */
    public function processSearchParams($control, $options = array())
    {
        switch($control)
        {
            case 'input':
            case 'textarea':
            case 'richtext':
                return array('operator' => 'include', 'control' => 'input', 'values' => '');
            case 'select':
            case 'radio':
                return array('operator' => '=', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $options));
            case 'multi-select':
            case 'checkbox':
                return array('operator' => 'include', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $options));
            case 'date':
                return array('operator' => '>=', 'control' => 'input', 'values' => '', 'class' => 'date');
            case 'datetime':
                return array('operator' => '>=', 'control' => 'input', 'values' => '', 'class' => 'datetime');
            default:
                return array('operator' => '=', 'control' => 'input', 'values' => '');
        }
    }

    /**
     * Set editor of a action of a flow.
     *
     * @param  string $module
     * @param  string $action
     * @param  array  $fields
     * @access public
     * @return void
     */
    public function setFlowEditor($module, $action, $fields)
    {
        $editor = '';
        if(!isset($this->lang->$module)) $this->lang->$module = new stdclass();
        foreach($fields as $key => $field)
        {
            $this->lang->$module->{$field->field} = $field->name;
            if($field->control == 'richtext' && $field->show && !$field->readonly) $editor .= ',' . $field->field;
        }
        $editor = trim($editor, ',');

        if($editor)
        {
            if(!isset($this->config->flow)) $this->config->flow = new stdclass();
            $this->config->flow->editor = new stdclass();
            $this->config->flow->editor->$action = array('id' => $editor, 'tools' => 'simple');
        }
    }

    /**
     * Set fields the excel functions needed.
     *
     * @param  string $module
     * @param  array  $fields
     * @access public
     * @return void
     */
    public function setExcelFields($module, $fields)
    {
        $dateFields   = array();
        $editorFields = array();

        foreach($fields as $field)
        {
            if($field->control == 'date' or $field->control == 'datetime') $dateFields[] = $field->field;
            if($field->control == 'richtext') $editorFields[] = $field->field;
        }

        if(!isset($this->config->excel)) $this->config->excel = new stdclass();

        $this->config->excel->dateFields      = $dateFields;
        $this->config->excel->editor[$module] = $editorFields;
    }

    /**
     * Check if user can access.
     *
     * @param  object $flow
     * @param  object $action
     * @access public
     * @return void
     */
    public function checkPrivilege($flow, $action, $data = null)
    {
        /* 防止用户直接输入类似flow-browse-$module的链接绕过模块的权限检查。*/
        if(!commonModel::hasPriv($flow->module, $action->action))
        {
            $vars     = "module=$flow->module&method=$action->action";
            $denyLink = helper::createLink('user', 'deny', $vars);
            return array('load' => $denyLink, 'closeModal' => true, 'result' => 'success');
        }
        if(!empty($action->open) && $action->open != 'none' && (empty($this->config->openedApproval) || $flow->approval == 'disabled' || $action->action != 'approvalsubmit'))
        {
            $layoutFields = $this->loadModel('workflowaction')->getPageFields($flow->module, $action->action, true, null, 0, $flow->group);
            if(!$layoutFields)
            {
                $this->loadModel('index');
                $alertTip  = sprintf($this->lang->flow->error->emptyLayoutFields, $flow->name, $action->name);
                $link      = helper::createLink('workflowaction', 'browse', "module={$flow->module}");
                $isOldPage = in_array("{$action->module}-{$action->position}", $this->config->index->oldPages);
                if($isOldPage) return array('result' => 'success', 'load' => array('alert' => $alertTip, 'locate' => $link));
                return array('result' => 'success', 'load' => array('alert' => $alertTip, 'locate' => $link));
            }
        }
    }

    /**
     * Check label privilege.
     *
     * @param  object  $flow
     * @param  array   $labels
     * @param  int     $label
     * @access public
     * @return void
     */
    public function checkLabel($flow, $labels, $label)
    {
        /* Check the privilege of the label, if the label has no privilege, locate to the privileged label. */
        if(!commonModel::hasPriv($flow->module, $label))
        {
            foreach($labels as $labelID)
            {
                if(commonModel::hasPriv($flow->module, $labelID)) return helper::createLink($flow->module, 'browse', "mode=browse&label={$labelID}");
            }

            $vars = "module=$flow->module&method=browse&label=$label";
            return helper::createLink('user', 'deny', $vars);
        }
    }

    /**
     * Check if the category is valid and parse it.
     *
     * @param  string $module
     * @param  string $category
     * @access public
     * @return array
     */
    public function checkCategory($module, $category)
    {
        $params = explode('=', $category);
        if(count($params) != 2) return array('', '', '');

        $categoryField = $params[0];
        $categoryValue = (int)$params[1];

        $field = $this->loadModel('workflowfield', 'flow')->getByField($module, $categoryField);
        if(!$field) return array('', '', '');

        if($field->control == 'multi-select' or $field->control == 'checkbox')
        {
            $categoryQuery = "FIND_IN_SET('{$categoryValue}', `$categoryField`)";
        }
        else
        {
            $categoryQuery = "`$categoryField` = '{$categoryValue}'";
        }

        if($field->options == 'category')
        {
            $categoryType = $module . '_' . $field->field;

            return array($categoryType, $categoryQuery, $categoryValue);
        }
        elseif((int)$field->options > 0)
        {
            $datasource = $this->loadModel('workflowdatasource', 'flow')->getByID((int)$field->options);

            if(zget($datasource, 'type') == 'category')
            {
                $categoryType = 'datasource_' . $datasource->id;

                return array($categoryType, $categoryQuery, $categoryValue);
            }
        }

        return array('', '', '');
    }

    /**
     * Check rules.
     *
     * @param  array  $fields
     * @param  object $data
     * @param  object $dao
     * @param  int    $dataID
     * @param  object $notEmptyRule
     * @param  array  $ruleList
     * @access public
     * @return object
     */
    public function checkRules($fields, $data, $dao, $dataID = 0, $notEmptyRule = null, $ruleList = array())
    {
        $tmpData = clone $data;

        foreach($fields as $field)
        {
            /* If the field don't show in view, don't check it. */
            if(empty($field->show) || empty($field->rules) || $field->control == 'file') continue;

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
                        $dao->check($field->field, $rule->rule, $condition);
                    }
                    else
                    {
                        /* 如果传值是0，并且不是下拉选择控件，不验证必填。*/
                        /* If the value passed is 0, and not is select control, it is not required to verify. */
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
     * Check condition is available.
     *
     * @param  array|string  $conditions
     * @param  object        $data
     * @access public
     * @return bool
     */
    public function checkConditions($conditions, $data)
    {
        if(is_string($conditions)) $conditions = json_decode($conditions);
        if(empty(array_filter($conditions))) return true;

        $this->loadModel('workflowfield', 'flow');
        $this->loadModel('workflowhook', 'flow');

        foreach($conditions as $condition)
        {
            $enabled = true;
            if($condition->conditionType == 'data')
            {
                $index = 1;
                foreach($condition->fields as $field)
                {
                    if(!isset($data->{$field->field})) return false;

                    $checkFunc = 'check' . $field->operator;
                    $var       = $data->{$field->field};
                    $param     = $field->param;


                    if(is_array($var) && !is_array($param))
                    {
                        $param = explode(',', $param);
                        foreach($param as $paramKey => $paramValue)
                        {
                            if(in_array($paramValue, $this->config->flow->variables)) $param[$paramKey] = $this->workflowhook->getParamRealValue($paramValue);
                        }
                    }
                    else
                    {
                        if(in_array($param, $this->config->flow->variables)) $param = $this->workflowhook->getParamRealValue($param);
                    }

                    if($field->operator == 'include' || $field->operator == 'notinclude')
                    {
                        if(is_array($var))
                        {
                            $result = true;
                            foreach($param as $paramValue)
                            {
                                if(!in_array($paramValue, $var))
                                {
                                    $result = false;
                                    break;
                                }
                            }
                        }
                        else
                        {
                            $result = strpos($var, $param) !== false;
                        }

                        if($field->operator == 'notinclude') $result = !$result;
                    }
                    else
                    {
                        $result = validater::$checkFunc($var, $param);
                    }

                    if($index == 1) $enabled = $result;
                    if($index > 1)
                    {
                        $logicalOperator = zget($field, 'logicalOperator', 'and');

                        if($logicalOperator == 'and') $enabled = $enabled && $result;
                        if($logicalOperator == 'or')  $enabled = $enabled || $result;
                    }

                    $index++;
                }
            }
            elseif($condition->conditionType == 'sql')
            {
                $sql = $this->workflowfield->replaceTableNames($condition->sql);
                try
                {
                    $sqlResult = $this->dbh->query($sql)->fetch();
                    if($condition->sqlResult == 'empty')
                    {
                        $enabled = empty($sqlResult);
                    }
                    elseif($condition->sqlResult == 'notempty')
                    {
                        $enabled = !empty($sqlResult);
                    }
                }
                catch(PDOException $exception)
                {
                    $enabled = false;
                }
            }

            if($enabled) return true;
        }

        return false;
    }

    /**
     * Add ditto to field options.
     *
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function addDitto($fields)
    {
        foreach($fields as $field)
        {
            if(!$field->show) continue;
            if($field->control != 'select' or empty($field->options)) continue;

            $ditto  = array('ditto' => $this->lang->flow->ditto);
            $option = reset($field->options);
            if($option == '')
            {
                $key = key($field->options);
                unset($field->options[$key]);
                $field->options = arrayUnion(array($key => $option), $ditto, $field->options);
            }
            else
            {
                $field->options = arrayUnion($ditto, $field->options);
            }
        }

        return $fields;
    }

    /**
     * Build control.
     *
     * @param  object $field
     * @param  string $fieldValue
     * @param  string $element
     * @param  string $childModule
     * @param  bool   $emptyValue
     * @param  bool   $preview
     * @access public
     * @return string
     */
    public function buildControl($field, $fieldValue, $element = '', $childModule = '', $emptyValue = false, $preview = false)
    {
        $isMobile = $this->app->viewType === 'mhtml';

        /* If the value is not 0 or empty. */
        if((!is_numeric($fieldValue) and empty($fieldValue)) or ($field->control == 'date' && $fieldValue == '0000-00-00') or ($field->control == 'datetime' && $fieldValue == '0000-00-00 00:00:00'))
        {
            $fieldValue = (isset($field->defaultValue) && ($field->defaultValue || $field->defaultValue === 0)) ? $field->defaultValue : zget($field, 'default', '');
        }

        if(in_array($fieldValue, $this->config->flow->variables)) $fieldValue = $this->loadModel('workflowhook', 'flow')->getParamRealValue($fieldValue, 'control');
        if($field->control == 'date') $fieldValue = substr($fieldValue, 0, 10);

        if($emptyValue) $fieldValue = '';

        $options = $this->loadModel('workflowfield', 'flow')->getFieldOptions($field, true, $fieldValue, '', $this->config->flowLimit);

        /* If field type is number, set empty value with key as 0. */
        $newOptions = array();
        if(in_array($field->type, $this->config->workflowfield->numberTypes))
        {
            $newOptions[0] = '';
            $field = $this->workflowfield->processNumberField($field);
        }
        elseif($field->control != 'radio' && $field->control != 'checkbox')
        {
            $newOptions[''] = '';
        }

        foreach($options as $optionKey => $optionValue)
        {
            if(empty($optionKey) && empty($optionValue)) continue;
            $newOptions[$optionKey] = $optionValue;
        }
        $options = $newOptions;

        if(!$element) $element = $field->field;

        $placeholder = '';
        if($childModule)
        {
            if(!$element) $element = "children[$childModule][{$field->field}][KEY]";
            if($field->control == 'radio')    $field->control = 'select';
            if($field->control == 'checkbox') $field->control = 'multi-select';

            $placeholder = ($field->control == 'select' || $field->control == 'multi-select') ? "data-placeholder='{$field->name}'" : "placeholder='{$field->name}'";
        }

        if($field->control == 'multi-select' && substr($element, -2) != '[]' ) $element .= '[]';

        $control = '';
        if($isMobile)  $control = $this->buildMobileControl($field, $fieldValue, $options, $element, $placeholder);
        if(!$isMobile) $control = $this->buildDesktopControl($field, $fieldValue, $childModule, $options, $element, $placeholder, $preview);

        if($field->field == 'subStatus')
        {
            $flow = $this->loadModel('workflow', 'flow')->getByModule($field->module);

            if($flow->type == 'flow') $control .= $this->getSubStatusScript($field);
        }

        return $control;
    }

    /**
     * Build control for desktop.
     *
     * @param  object $field
     * @param  string $value
     * @param  string $childModule
     * @param  array  $options
     * @param  string $element
     * @param  string $placeholder
     * @param  bool   $preview
     * @access public
     * @return string
     */
    public function buildDesktopControl($field, $value, $childModule, $options, $element, $placeholder, $preview = false)
    {
        $required     = '';
        $notEmptyRule = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');
        if(!$childModule && $notEmptyRule && strpos(",$field->rules,", ",$notEmptyRule->id,") !== false)
        {
            $required = "<div class='required required-wrapper'></div>";
        }

        $dataValue = is_array($value) ? implode(',', $value) : $value;
        if($field->control == 'richtext' or $field->control == 'textarea') $dataValue = '';

        $elementID = str_replace(array('[', ']'), '', $element);
        $data      = "data-module='$field->module' data-field='$field->field' data-value='$dataValue'";
        $class     = $preview ? 'chosen' : 'picker-select';

        /* Display 3 datas in preview mode. */
        if($preview)
        {
            $tmpOptions = array();

            $index = 1;
            foreach($options as $k => $v)
            {
                if($index > 3) break;

                $tmpOptions[$k] = $v;

                $index++;
            }

            $options = $tmpOptions;
        }

        switch($field->control)
        {
            case 'label':
                return "<label>$value</label>" . html::hidden($element, $value);
            case 'input':
                return $required . html::input($element, $value, "id='$elementID' class='form-control' $placeholder $data");
            case 'decimal':
            case 'integer':
            case 'formula':
                return $required . html::number($element, $value, "id='$elementID' class='form-control' $placeholder max='{$field->max}' min='{$field->min}' step='{$field->step}' $data");
            case 'textarea':
            case 'richtext':
                return $required . html::textarea($element, htmlSpecialString($value), "id='$elementID' rows='3' class='form-control' $placeholder $data");
            case 'select':
                return $required . html::select($element, $options, $value, "id='$elementID' class='form-control $class' $placeholder $data");
            case 'multi-select':
                return $required . html::select($element, $options, $value, "id='$elementID' class='form-control $class' multiple $placeholder $data");
            case 'radio':
                return $required . "<div id='$elementID'  class='checkboxDIV'>" . html::radio($element, $options, $value, $data) . '</div>';
            case 'checkbox':
                return $required . "<div id='$elementID' class='radioDIV'>" . html::checkbox($element, $options, $value, $data) . '</div>';
            case 'date':
                return $required . html::input($element, $value, "id='$elementID' class='form-control form-date' $placeholder $data");
            case 'datetime':
                return $required . html::input($element, formatTime($value, DT_DATETIME1), "id='$elementID' class='form-control form-datetime' $placeholder $data");
            case 'time':
                return $required . html::input($element, $value, "id='$elementID' class='form-control form-time' $placeholder $data");
        }

        return "<label>$value</label>" . html::hidden($element, $value);
    }

    /**
     * Build control for mobile.
     *
     * @param  object $field
     * @param  string $value
     * @param  array  $options
     * @param  string $element
     * @param  string $placeholder
     * @access public
     * @return string
     */
    public function buildMobileControl($field, $value, $options, $element, $placeholder)
    {
        switch($field->control)
        {
            case 'label':
                return "<label>$value</label>" . html::hidden($element, $value);
            case 'input':
                return html::input($element, $value, "class='input' $placeholder");
            case 'decimal':
            case 'integer':
                return html::number($element, $value, "class='input' $placeholder max='{$field->max}' min='{$field->min}' step='{$field->step}'");
            case 'textarea':
            case 'richtext':
                return html::textarea($element, $value, "rows='3' class='textarea' $placeholder");
            case 'select':
                return "<div class='select'>" . html::select($element, $options, $value, "$placeholder") . "</div>";
            case 'multi-select':
                return "<div class='select'>" . html::select($element, $options, $value, "multiple='multiple' $placeholder") . "</div>";
            case 'radio':
                $html = '';
                foreach($options as $key => $option)
                {
                    $checked = ($key == $value) ? "checked ='checked'" : '';
                    $html .= "<div class='radio inline-block'>";
                    $html .= "<input type='radio' name='{$element}' value='{$key}' {$checked}>";
                    $html .= "<label for='{$element}'>{$option}</label>";
                    $html .= "</div>";
                }
                return $html;
            case 'checkbox':
                if(is_array($value)) $value = implode(',', $value);
                $value = ",$value,";
                $html = '';
                foreach($options as $key => $option)
                {
                    $checked = (strpos($value, ",$key,") !== false) ? "checked ='checked'" : '';

                    $html .= "<div class='checkbox inline-block'>";
                    $html .= "<input type='checkbox' name='{$element}[]' id='{$element}{$key}' value='{$key}' {$checked}>";
                    $html .= "<label for='{$element}[]'>{$option}</label>";
                    $html .= "</div>";
                }
                return $html;
            case 'date':
                return "<input type='date' class='input' id='$element' name='$element' value='{$value}' $placeholder>";
            case 'datetime':
                if($value && $value != '0000-00-00 00:00:00') $value = date('Y-m-d\TH:i', strtotime($value));
                return "<input type='datetime-local' class='input' id='$element' name='$element' value='{$value}' $placeholder>";
            case 'time':
                return "<input type='time' class='input' id='$element' name='$element' value='{$value}' $placeholder>";
        }

        return "<label>$value</label>" . html::hidden($element, $value);
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

        $this->loadModel('workflow', 'flow');
        $this->loadModel('workflowaction', 'flow');
        $this->loadModel('workflowfield', 'flow');
        $this->loadModel('workflowrelation', 'flow');

        $isMobile     = $this->app->viewType === 'mhtml';
        $btn          = $type == 'menu' ? 'btn btn-primary' : ($type == 'view' ? 'btn' : '');
        $menu         = ($type == 'view' && !$isMobile) ? "<div class='main-actions'><div class='btn-toolbar'>" : '';
        $dropdownMenu = '';

        if($type == 'view' && $flow->buildin) $menu .= "<div class='divider'></div>";

        if($type == 'view' && !$isMobile && !$flow->buildin)
        {
            $menu .= $this->session->flowList ? baseHTML::a($this->session->flowList, $this->lang->goback, "class='btn btn-back'") : html::backButton();
            $menu .= "<div class='divider'></div>";
        }

        $relations = $this->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($flow->module)->fetchPairs();
        $actions   = $this->workflowaction->getList($flow->module);
        foreach($actions as $action)
        {
            $flowMenu = $this->buildActionMenu($flow->app . '.' . $flow->module, $action, $data, $type, $relations);

            if($type == 'browse' && $action->show == 'dropdownlist')
            {
                $dropdownMenu .= $flowMenu;
            }
            else
            {
                $menu .= $flowMenu;
            }
        }

        if($type == 'view' && !empty($this->config->openedApproval) && $flow->approval == 'enabled' && commonModel::hasPriv('approval', 'progress') && !empty($data->approval))
        {
            $menu .= "<div class='divider'></div>";
            $menu .= baseHTML::a(helper::createLink('approval', 'progress', "approvalID={$data->approval}"), $this->lang->flow->approvalProgress, "class='btn' data-toggle='modal'");
        }

        if($type == 'browse' && $dropdownMenu != '')
        {
            $dropdownBefore  = "<div class='dropdown'><a href='javascript:;' data-toggle='dropdown'>{$this->lang->more}<span class='caret'> </span></a>";
            $dropdownBefore .= "<ul class='dropdown-menu pull-right'>";
            $dropdownMenu    = $dropdownBefore . $dropdownMenu . "</ul></div>";
        }

        $menu .= $dropdownMenu;

        if($type == 'view' && !$isMobile) $menu .= '</div></div>';

        return $menu;
    }

    /**
     * Build menu actions of a flow.
     *
     * @param  object $flow
     * @access public
     * @return string
     */
    public function buildMenuActions($flow)
    {
        $menuActions       = "<div id='menuActions'>";
        $canImport         = $this->isClickable($flow->module, 'import');
        $canExportData     = $this->isClickable($flow->module, 'export');
        $canExportTemplate = $this->isClickable($flow->module, 'exporttemplate');
        if($canImport or $canExportTemplate)
        {
            $menuActions .= "<div class='btn-toolbar'>";
            $menuActions .= baseHTML::a('javascript:;', $this->lang->importIcon . $this->lang->import . " <span class='caret'></span>", "class='btn btn-secondary dropdown-toggle' data-toggle='dropdown'");
            $menuActions .= "<ul class='dropdown-menu'>";
            if($canImport)         $menuActions .= '<li>' . baseHTML::a(helper::createLink($flow->module, 'import'), $this->lang->workflowaction->default->actions['import'], "data-toggle='modal'") . '</li>';
            if($canExportTemplate) $menuActions .= '<li>' . baseHTML::a(helper::createLink($flow->module, 'exporttemplate'), $this->lang->workflowaction->default->actions['exporttemplate'], "class='iframe'") . '</li>';
            $menuActions .= '</ul></div>';
        }
        if($canExportData)
        {
            $menuActions .= "<div class='btn-toolbar'>";
            $menuActions .= baseHTML::a('javascript:;', $this->lang->exportIcon . $this->lang->export . " <span class='caret'></span>", "class='btn btn-secondary dropdown-toggle' data-toggle='dropdown'");
            $menuActions .= "<ul class='dropdown-menu'>";
            $menuActions .= '<li>' . baseHTML::a(helper::createLink($flow->module, 'export', 'mode=all'), $this->lang->exportAll, "class='iframe'") . '</li>';
            $menuActions .= '<li>' . baseHTML::a(helper::createLink($flow->module, 'export', 'mode=thisPage'), $this->lang->exportThisPage, "class='iframe'") . '</li>';
            $menuActions .= '</ul></div>';
        }
        $menuActions .= $this->buildOperateMenu($flow, $data = null, $type = 'menu');
        $menuActions .= '</div>';

        return $menuActions;
    }

    /**
     * Build batch actions of a flow.
     *
     * @param  string $moduleName
     * @access public
     * @return string
     */
    public function buildBatchActions($moduleName)
    {
        $batchActions = '';

        $index     = 1;
        $relations = $this->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($moduleName)->fetchPairs();
        $actions   = $this->loadModel('workflowaction', 'flow')->getList($moduleName);
        foreach($actions as $action)
        {
            if($action->virtual != '1' && $action->status   != 'enable') continue;
            if($action->virtual != '1' && $action->type     != 'batch')  continue;
            if($action->virtual != '1' && $action->position != 'browse' && $action->position != 'browseandview') continue;

            $appName    = $this->appName;
            $methodName = $action->action;
            $module     = '';
            $method     = '';
            $params     = 'step=form';

            if(strpos($moduleName, '.') !== false) list($appName, $moduleName) = explode('.', $moduleName);
            if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($module, $method) = explode('_', $methodName);

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

            if(!commonModel::hasPriv($appName . '.' . $module, $method)) continue;

            $actionLink = helper::createLink($appName . '.' . $module, $method, $params);
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

        $appName    = $this->appName;
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

        if(!commonModel::hasPriv($appName . '.' . $module, $method)) return '';

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
            $reload   = ($type != 'menu' && $action->open == 'none' && $action->method != 'delete') ? 'reloadPage' : '';
            $isMobile = $this->app->viewType === 'mhtml';
            if($isMobile)
            {
                $url = helper::createLink($appName . '.' . $module, $method, $params);
                if($action->method == 'delete') return "<a data-remote='$url' data-display='ajaxAction' data-ajax-delete='true' data-locate=''>{$label}</a>";
                if($reload)  return "<a data-remote='{$url}' data-display='ajaxAction' data-locate='self'>{$label}</a>";
                if(!$reload) return "<a data-remote='{$url}' data-display='modal' data-placement='bottom'>{$label}</a>";
            }
            else
            {
                $btn         = $type == 'menu' ? 'btn btn-primary' : ($type == 'view' ? 'btn' : '');
                $viewAction  = $this->loadModel('workflowaction', 'flow')->getByModuleAndAction($moduleName, 'view');
                $class       = ($type == 'view' && $viewAction->open == 'modal' && $action->open == 'modal') ? "loadInModal iframe iframe" : '';
                $attr        = ($type != 'menu' && $action->open == 'modal' && $class == '') ? "data-toggle='modal'" : '';
                $deleter     = $action->method == 'delete' ? 'deleter' : '';
                $link        = $this->createLink($appName . '.' . $module, $method, $params, $label, "{$class} {$reload} {$btn} {$deleter}", $attr);

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
     * Create open entry link.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $params
     * @param  string $label
     * @param  string $class
     * @param  string $attr
     * @access public
     * @return string
     */
    public function createLink($module, $method, $params, $label, $class = '', $attr = '')
    {
        static $flowApps;

        $app = '';
        if(strpos($module, '.') === false)
        {
            if(empty($flowApps)) $flowApps = $this->dao->select('module, app')->from(TABLE_WORKFLOW)->where('type')->eq('flow')->fetchPairs();
            $app = zget($flowApps, $module, '');
        }
        else
        {
            list($app, $module) = explode('.', $module);
        }

        if(!empty($this->app->rawApp) && $this->app->rawApp != $app)
        {
            $module = isset($this->lang->apps->$app) ? $app . '.' . $module : 'sys' . '.' . $module;
            $class .= ' openEntry';
            $attr  .= " data-app='{$app}'";
        }

        return baseHTML::a(helper::createLink($module, $method, $params), $label, "class='{$class}' {$attr}");
    }

    /**
     * Check if an action is clickable.
     *
     * @param  object $data
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($data, $action)
    {
        if($action == 'approvalsubmit') return empty($data->reviewStatus) || in_array($data->reviewStatus, array('wait', 'reject', 'reverting'));

        $module  = $this->app->rawModule;
        $groupID = $this->loadModel('workflowgroup')->getGroupIDByData($module, $data);
        $action  = $this->loadModel('workflowaction', 'flow')->getByModuleAndAction($module, $action, $groupID);

        if(!$action || $action->status != 'enable') return false;
        if(!commonModel::hasPriv($module, $action->action)) return false;

        return $this->checkConditions($action->conditions, $data);
    }

    /**
     * Process field value displayed on view page.
     *
     * @param  object $field
     * @param  object $relation
     * @param  mixed  $value
     * @access public
     * @return string
     */
    public function processFieldValue($field, $relation, $value)
    {
        if($field->control == 'datetime') $value = formatTime($value, DT_DATETIME1);

        if($relation && commonModel::hasPriv($relation->prev, 'view') && $value) return $this->createLink($relation->prev, 'view', "dataID={$value}", zget($field->options, $value));

        return zget($field->options, $value);
    }

    /**
     * Process blocks.
     *
     * @param  array  $blocks
     * @param  array  $fields
     * @param  bool   $hasInfo
     * @access public
     * @return array
     */
    public function processBlocks($blocks, $fields, $hasInfo = false)
    {
        $positionFields = array();
        foreach($fields as $field)
        {
            if(!isset($positionFields[$field->position])) $positionFields[$field->position] = array();
            $positionFields[$field->position][] = $field;
        }

        $processBlocks = array();

        if($hasInfo)
        {
            $infoBlock = new stdclass();
            $infoBlock->name   = $this->lang->workflowlayout->positionList['view']['info'];
            $infoBlock->fields = zget($positionFields, 'info', array());
            $processBlocks['info'] = $infoBlock;
        }

        if(empty($blocks))
        {
            $basicBlock = new stdclass();
            $basicBlock->name   = $this->lang->workflowlayout->positionList['view']['basic'];
            $basicBlock->fields = zget($positionFields, 'basic', array());
            $processBlocks['basic'] = $basicBlock;
        }
        else
        {
            foreach($blocks as $blockKey => $block)
            {
                $blockData = new stdclass();
                if(!empty($block->tabs))
                {
                    $blockData->tabs = array();
                    foreach($block->tabs as $tabKey => $tabName)
                    {
                        $tabData = new stdclass();
                        $tabData->name   = $tabName;
                        $tabData->fields = zget($positionFields, "block{$blockKey}_tab{$tabKey}", array());

                        $blockData->tabs["tab{$tabKey}"] = $tabData;
                    }
                }
                else
                {
                    $blockData->name   = $block->showName ? $block->name : '';
                    $blockData->fields = zget($positionFields, "block{$blockKey}", array());
                }
                $processBlocks["block{$blockKey}"] = $blockData;
            }
        }

        return $processBlocks;
    }

    /**
     * Process dimension and fileds for report object,and add legend property.
     *
     * @param  string   $module
     * @param  array    $reportList
     * @access public
     * @return array
     */
    public function processReportList($module, $reportList)
    {
        foreach($reportList as $key => $report)
        {
            /* Only process user chosen reports. */
            if(!in_array($report->id, $this->post->reports) || empty($report->dimension))
            {
                unset($reportList[$key]);
                continue;
            }

            $report->dimension = json_decode($report->dimension);
            if($report->countType == 'count')
            {
                $report->legend = false;
                continue;
            }
            $report->fields = json_decode($report->fields);
            $report->legend = count($report->fields) > 1;
        }

        return $reportList;
    }

    /**
     * Query data by session select confition and query data of subTabls.
     *
     * @param  string $module
     * @param  array  $charts
     * @param  array  $fieldList
     * @param  array  $chartModules
     * @access public
     * @return array
     */
    public function getDataByCondition($module, $charts, $fieldList, $chartModules)
    {
        $records = array();

        /* Query data by session sql. */
        $records[$module] = array();
        $queryCondition = $this->session->{$module . 'QuerySQL'};
        if(strpos($queryCondition, 'LIMIT') !== false) $queryCondition = substr($queryCondition, 0, strpos($queryCondition, 'LIMIT'));
        $stmt = $this->dbh->query($queryCondition);
        while($row = $stmt->fetch()) $records[$module][$row->id] = $row;

        /* Select all data from subTables. */
        $childTables   = $this->loadModel('workflow', 'flow')->getList('browse', 'table', '', $module);
        $defaultFields = $this->config->workflowfield->default->fields;

        foreach($childTables as $childTable)
        {
            if(!isset($chartModules[$childTable->module])) continue;

            $childDatas = $this->getDataList($childTable);

            foreach($childDatas as $childKey => $childData)
            {
                /* If record in the child table but not in main table, then continue. */
                if(!isset($records[$module][$childData->parent]))
                {
                    unset($childDatas[$childKey]);
                    continue;
                }

                $data = new stdclass();
                /* Change the field of child table, because different tables may have the same field name. */
                foreach($childData as $field => $value)
                {
                    if(isset($defaultFields[$field])) continue;

                    $key = $childTable->module . '_' . $field;
                    $data->$key = $value;
                }

                /* Query related main table data of sub table. */
                $parent = $records[$module][$childData->parent];
                foreach($parent as $field => $value)
                {
                    $key = $module . '_' . $field;
                    $data->$key = $value;
                }

                $childDatas[$childKey] = $data;
            }

            $records[$childTable->module] = $childDatas;
        }

        foreach($records as $module => $datas)
        {
            foreach($datas as $key => $data)
            {
                foreach($data as $field => $value)
                {
                    if(!isset($fieldList[$module][$field])) continue;

                    $fieldObject = $fieldList[$module][$field];
                    if($fieldObject->control == 'multi-select' or $fieldObject->control == 'checkbox') $data->$field = explode(',', $value);
                    if($fieldObject->control == 'date' or $fieldObject->control == 'datetime')
                    {
                        $data->$field = formatTime($value);
                    }
                }

                $records[$module][$key] = $data;
            }
        }

        return $records;
    }

    /**
     * Processing into statistical data with records.
     *
     * @param  string   $module
     * @param  array    $charts
     * @access public
     * @return array
     */
    public function getChartData($module, $charts)
    {
        $this->loadModel('workflowfield', 'flow');
        /* Need query modules array. */
        $chartModules = array();
        foreach($charts as $chart)
        {
            $chartModules[$chart->dimension->module][$chart->dimension->field] = $chart->dimension->field;
            if(isset($chart->fields) && is_array($chart->fields))
            {
                foreach($chart->fields as $field) $chartModules[$field->module][$field->field] = $field->field;
            }
        }

        /* Generate field of need modules ,include options、name...*/
        $fieldList = array();
        foreach($chartModules as $chartModule => $chartFields)
        {
            $fields = $this->workflowfield->getList($chartModule);
            foreach($fields as $field)
            {
                if(!isset($chartFields[$field->field])) continue;

                $fieldList[$chartModule][$field->field] = $field;
            }
        }

        /* Query and process data of child table. */
        $records = $this->getDataByCondition($module, $charts, $fieldList, $chartModules);
        foreach($fieldList as $fieldModule => $fields) $fields = $this->loadModel('workflowaction', 'flow')->processFields($fields, true, zget($records, $fieldModule, array()));

        $chartData = array();
        foreach($charts as $chart)
        {
            if(!isset($fieldList[$chart->dimension->module][$chart->dimension->field])) continue;

            $dimenField = $fieldList[$chart->dimension->module][$chart->dimension->field];

            $data = array();
            foreach($records as $recordModule => $moduleRecords)
            {
                $dimensionField = $recordModule == $module ? $chart->dimension->field : $chart->dimension->module . '_' . $chart->dimension->field;

                foreach($moduleRecords as $record)
                {
                    if(!isset($record->$dimensionField)) continue;

                    $dimension = $record->$dimensionField;

                    if(isset($dimenField->options))
                    {
                        if(is_array($dimension))
                        {
                            /* Show option value if field is multi-select or checkbox. */
                            $dimensionValues = array();
                            foreach($dimension as $value) $dimensionValues[] = zget($dimenField->options, $value);
                            $dimension = implode(',', $dimensionValues);
                        }
                        else
                        {
                            /* Show option value if field is select or radio. */
                            $dimension = zget($dimenField->options, $dimension);
                        }
                    }
                    /* Set dimension granularity if field is date or datetime. */
                    if($dimension && isset($chart->dimension->granularity)) $dimension = $this->processDimension($chart->dimension->granularity, $dimension);

                    if(!$dimension) $dimension = $this->lang->flow->notset;

                    /* Use it as key to count sum. */
                    if($chart->countType == 'count')
                    {
                        /* dimension module == main table module and dimension == record module. */
                        if($chart->dimension->module == $module)
                        {
                            if($module != $recordModule) continue;
                        }
                        else
                        {
                            if($chart->dimension->module != $recordModule) continue;
                        }

                        if(isset($data[$dimension]))
                        {
                            $data[$dimension]++;
                        }
                        else
                        {
                            $data[$dimension] = 1;
                        }
                    }
                    else
                    {
                        /* Total statistics. */
                        foreach($chart->fields as $field)
                        {
                            /* Set default value of no dimension in data when dimension is main table and fields is sub table. */
                            if($dimension != $this->lang->flow->notset && !isset($data[$dimension][$field->field])) $data[$dimension][$field->field] = 0;

                            /* When have sub tables, skip useless fields in this record. */
                            if($chart->dimension->module == $module && $field->module == $module && $recordModule != $module) continue;

                            $fieldKey = $recordModule == $module ? $field->field : $field->module . '_' . $field->field;
                            if(!isset($record->$fieldKey)) continue;

                            if(isset($data[$dimension][$field->field]))
                            {
                                $data[$dimension][$field->field] += (float)$record->$fieldKey;
                            }
                            else
                            {
                                $data[$dimension][$field->field] = (float)$record->$fieldKey;
                            }
                        }
                    }
                }
            }

            /* Generate default value for no result of the dimension. */
            if($chart->countType == 'sum')
            {
                foreach($data as $dimension => $fieldValue)
                {
                    foreach($chart->fields as $field)
                    {
                        if(!isset($data[$dimension][$field->field])) $data[$dimension][$field->field] = 0;
                    }
                }
            }

            /* Count percent. */
            if($chart->displayType == 'percent') $data = $this->countPercent($chart->countType, $data);

            /* Convert the value of data to a string type to avoid display errors caused by floating-point precision problems. */
            if($chart->countType == 'sum') $data = $this->convertData2String($data);

            ksort($data);

            $dataKey = 'chart-' . $chart->id;
            $chartData[$dataKey] = $this->formatDataByType($chart, $data, $fieldList);
        }

        return $chartData;
    }

    /**
     * Process dimension if dimension is date or datetime.
     *
     * @param  string $granularity
     * @param  string $dimension
     * @access public
     * @return void
     */
    public function processDimension($granularity, $dimension)
    {
        switch($granularity)
        {
            case 'year': return date('Y', strtotime($dimension));
            case 'month': return date('Y-m', strtotime($dimension));
            case 'week':
                $year       = date('Y', strtotime($dimension));
                $week       = date('W', strtotime($dimension));
                $clientLang = $this->app->getClientLang();

                if($clientLang == 'zh-cn' or $clientLang == 'zh-tw') return sprintf($this->lang->flow->reportGranularity['week'], $year, $week);
                return sprintf($this->lang->flow->reportGranularity['week'], $week, $year);
            case 'day': return date('Y-m-d', strtotime($dimension));
            case 'quarter':
                $year  = date('Y', strtotime($dimension));
                $month = date('m', strtotime($dimension));

                if($month < 4) return sprintf($this->lang->flow->reportGranularity['Q1'], $year);
                if($month < 7) return sprintf($this->lang->flow->reportGranularity['Q2'], $year);
                if($month < 10) return sprintf($this->lang->flow->reportGranularity['Q3'], $year);
                return sprintf($this->lang->flow->reportGranularity['Q4'], $year);
        }

        return $dimension;
    }

    /**
     * Count percent.
     *
     * @param  string   $countType
     * @param  array    $data
     * @access public
     * @return array
     */
    public function countPercent($countType, $data)
    {
        if($countType == 'count')
        {
            $sum = array_sum($data);
            foreach($data as $dimension => $count) $data[$dimension] = $sum ? round($count / $sum * 100, 2) : 0;
        }

        if($countType == 'sum')
        {
			/* Count sum. */
			$sum = array();
			foreach($data as $fieldValues)
			{
				foreach($fieldValues as $field => $value) $sum[$field] = isset($sum[$field]) ? $sum[$field] + $value : $value ;
			}

			/* Count percent. */
            foreach($data as $dimension => $fieldValues)
            {
                foreach($fieldValues as $field => $value) $data[$dimension][$field] = $sum[$field] ? round($value / $sum[$field] * 100, 2) : 0;
            }
        }

        return $data;
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
        if($chart->type == 'pie')
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
        else
        {
            $chartData = new stdClass();
            $chartData->labels   = array_keys($datas);
            $chartData->datasets = array();

            if($chart->countType == 'count')
            {
				$dataset = new stdClass();
				$dataset->data = array_values($datas);
                $chartData->datasets[] = $dataset;
                return $chartData;
            }

            $datasets = array();
            foreach($chart->fields as $field)
            {
                $field = $fieldList[$field->module][$field->field];

                $data = new stdclass();
                $data->label = $field->name;

                $datasets[$field->field] = $data;
            }
            foreach($datas as $fieldsData)
            {
                foreach($fieldsData as $field => $value)
                {
                    $datasets[$field]->data[] = $value;
                }
            }

            $chartData->datasets = array_values($datasets);

            return $chartData;
        }
    }

    /**
     * Convert the value of data to a string type to avoid display errors caused by floating-point precision problems.
     *
     * @param  array  $data
     * @access public
     * @return array
     */
    public function convertData2String($data)
    {
        foreach($data as $dimension => $pairs)
        {
            foreach($pairs as $field => $value) $data[$dimension][$field] = (string)$value;
        }

        return $data;
    }

    /**
     * Print workflow defined fields for view and form page.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  object $object       order | contract | purchasecontract | customer | provider | contact | leads | product | invoice | feedback
     * @param  string $type         The parent component which fields displayed in. It should be table or div.
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
        $action  = $this->loadModel('workflowaction', 'flow')->getByModuleAndAction($moduleName, $methodName, $groupID);
        if(empty($action) or $action->extensionType == 'none') return null;

        parse_str(str_replace(array(',', ' '), array('&', ''), $extras), $params);

        $html    = '';
        $flow    = $this->loadModel('workflow', 'flow')->getByModule($moduleName, false, $groupID);
        $uiID    = empty($object) ? 0 : $this->loadModel('workflowlayout')->getUIByData($moduleName, $methodName, $object);
        $fields  = $this->workflowaction->getFields($moduleName, $methodName, true, $object, $uiID, $groupID);
        $layouts = $this->loadModel('workflowlayout', 'flow')->getFields($moduleName, $methodName, $uiID, $groupID);

        static $loadedResource = array();
        if(empty($loadedResource[$moduleName][$methodName]['css']))
        {
            if(!empty($flow->css))   $html .= "<style>$flow->css</style>";
            if(!empty($action->css)) $html .= "<style>$action->css</style>";

            $loadedResource[$moduleName][$methodName]['css'] = true;
        }

        if($layouts)
        {
            $allFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($moduleName)->fetchAll('field', false);
            foreach($fields as $fieldName => $field)
            {
                if(isset($allFields[$fieldName])) $field->default = $allFields[$fieldName]->default;
            }

            $function = "printFieldsIn" . $type;
            $html    .= $this->$function($object, $layouts, $fields, $params);
        }

        if($html && empty($loadedResource[$moduleName][$methodName]['js']))
        {
            $html .= $this->getFormulaScript($moduleName, $action, $fields);
            if($action->linkages)   $html .= $this->getLinkageScript($action, $fields, $uiID);
            if(!empty($flow->js))   $html .= "<script>$flow->js</script>";
            if(!empty($action->js)) $html .= "<script>$action->js</script>";

            $loadedResource[$moduleName][$methodName]['js'] = true;
        }

        return htmlspecialchars_decode($html);
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
        $html     = '';
        $position = zget($params, 'position', 'right');
        $columns  = zget($params, 'columns', 1);
        $inForm   = zget($params, 'inForm', 1);
        $picker   = zget($params, 'picker', 'picker-select');
        $colspan  = $columns > 1 ? "colspan='$columns'" : '';

        $notEmptyRule = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');

        if(!is_object($object)) $object = (object)$object;
        if(!$object) $object = new stdclass();

        foreach($fields as $field)
        {
            if($field->buildin or !$field->show or !isset($layouts[$field->field]) or (!empty($field->position) && (($position == 'left' && $field->position != 'info') or ($position == 'right' && $field->position != 'basic')))) continue;

            if((is_numeric($field->default) || $field->default) and empty($field->defaultValue)) $field->defaultValue = $field->default;
            if(empty($object->{$field->field})) $object->{$field->field} = $field->defaultValue;

            $require = '';
            if($inForm && !$field->readonly && $notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false) $require = "class='required'";

            if(($field->control == 'textarea' or $field->control == 'richtext') and empty($colspan) and $inForm) $colspan = "colspan='2'";

            $content = $inForm ? $this->getFieldControl($field, $object, '', $picker) : $this->getFieldValue($field, $object);
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
        $picker   = zget($params, 'picker', 'picker-select');

        $notEmptyRule = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');

        if($position == 'right')
        {
            $html .= "<div class='panel panel-block'>";
            $html .= "<div class='panel-heading'><strong>{$this->lang->extInfo}</strong></div>";
            $html .= "<div class='panel-body'>";
            $html .= $inCell ? "<table class='table table-data'>" : "<table class='table table-form'>";

            $tableContent = '';
            foreach($fields as $field)
            {
                if($field->buildin or !$field->show or !isset($layouts[$field->field]) or $field->position != 'basic') continue;

                $require = '';
                if($inForm && !$field->readonly && $notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false) $require = "class='required'";

                $content = $inForm ? $this->getFieldControl($field, $object, '', $picker) : $this->getFieldValue($field, $object);

                $tableContent .= "<tr class='field-row'><th class='thWidth'>{$field->name}</th>";
                $tableContent .= "<td $require>{$content}</td></tr>";
            }

            if(!$tableContent) return false;

            $html .= $tableContent;
            $html .= '</table>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if($position == 'left')
        {
            foreach($fields as $field)
            {
                if($field->buildin or !$field->show or !isset($layouts[$field->field]) or $field->position != 'info') continue;

                $require = '';
                if($inForm && !$field->readonly && $notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false) $require = "required";

                $content = $inForm ? $this->getFieldControl($field, $object, '', $picker) : $this->getFieldValue($field, $object);

                $html .= "<div class='panel panel-block field-row'>";
                $html .= "<div class='panel-heading'><strong>{$field->name}</strong></div>";
                $html .= "<div class='panel-body $require'>{$content}</div>";
                $html .= '</div>';
            }
        }

        return $html;
    }

    /**
     * Get control of a field.
     *
     * @param  object    $field
     * @param  object    $object
     * @access public
     * @return string
     */
    public function getFieldControl($field, $object, $controlName = '', $picker = 'picker-select')
    {
        $this->loadModel('workflowfield', 'flow');
        /* If field type is number, set empty value with key as 0. */
        if(in_array($field->type, $this->config->workflowfield->numberTypes)) $field = $this->workflowfield->processNumberField($field);

        $control      = '';
        $readonly     = $field->readonly ? 'disabled' : '';
        $defaultValue = !empty($field->defaultValue) ? $field->defaultValue : $field->default;
        $value        = !empty($object) ? zget($object, $field->field, '') : $defaultValue;
        $data         = "data-module='$field->module' data-field='$field->field' data-value='$value'";
        $width        = $field->width != 'auto' ? "style='width:{$field->width}px'" : '';
        if(!$controlName) $controlName = $field->field;

        if(in_array($value, $this->config->flow->variables)) $value = $this->loadModel('workflowhook', 'flow')->getParamRealValue($value, 'control');
        if($field->control == 'date') $value = substr($value, 0, 10);

        if($field->control == 'checkbox' or $field->control == 'radio' and isset($field->options[''])) unset($field->options['']);
        if($field->control == 'decimal' or $field->control == 'integer' or $field->control == 'formula')
        {
            $control = html::number($controlName, $value, "class='form-control' max='{$field->max}' min='{$field->min}' step='{$field->step}' $readonly $width");
        }
        if($field->control == 'file')         $control = html::file($controlName, "class='form-control' $readonly $width");
        if($field->control == 'input')        $control = html::input($controlName, $value, "class='form-control' $readonly $width");
        if($field->control == 'select')       $control = html::select($controlName, $field->options, $value, "class='form-control $picker' $data $readonly");
        if($field->control == 'multi-select') $control = html::select($controlName . '[]', $field->options, $value, "class='form-control $picker' $data multiple $readonly");
        if($field->control == 'checkbox')     $control = "<div id='{$field->field}'>" . html::checkbox($controlName, $field->options, $value, "$readonly") . '</div>';
        if($field->control == 'radio')        $control = "<div id='{$field->field}'>" . html::radio($controlName, $field->options, $value, "$readonly") . '</div>';
        if($field->control == 'date')         $control = html::input($controlName, formatTime($value), "class='form-control form-date' $readonly $width");
        if($field->control == 'datetime')     $control = html::input($controlName, formatTime($value, DT_DATETIME1), "class='form-control form-datetime' $readonly $width");
        if($field->control == 'richtext' or $field->control == 'textarea')
        {
            if(!empty($object))
            {
                $object = $this->loadModel('file')->replaceImgURL($object, $field->field);
                $value  = $object->{$field->field};
            }
            $control = html::textarea($controlName, $field->control == 'richtext' ? htmlspecialchars($value) : $value, "class='form-control' $readonly");
            if($readonly) $control = $field->control == 'richtext' ? str_replace("'", '&#039;', htmlspecialchars($value)) : $value;
        }

        if($field->field == 'subStatus') $control .= $this->getSubStatusScript($field);

        return $control;
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
        if($field->control == 'richtext')
        {
            $object = $this->loadModel('file')->replaceImgURL($object, $field->field);
            return $object->{$field->field};
        }
        if($field->control == 'file')
        {
            $files = $this->loadModel('file')->getByObject($field->module, $object->id, false, 0, $field->field);
            return $this->file->printFiles($files, false, $field, $object);
        }
        if($field->control == 'date' && isset($object->{$field->field}) && $object->{$field->field} == '0000-00-00') return '';
        if($field->control == 'datetime' && isset($object->{$field->field}) && $object->{$field->field} == '0000-00-00 00:00:00') return '';
        if($field->control != 'checkbox' and $field->control != 'multi-select') return zget($field->options, $object->{$field->field});

        $content = '';
        $values  = json_decode($object->{$field->field}, true);
        if(empty($values)) $values = explode(',', str_replace(' ', '', $object->{$field->field}));
        foreach($values as $value) $content .= ' ' . zget($field->options, $value);

        return $content;
    }

    /**
     * Get extend fields.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getExtendFields($moduleName, $methodName, $objectID = 0)
    {
        $groupID = $this->loadModel('workflowgroup')->getGroupIDByDataID($moduleName, $objectID);
        $action = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName, $groupID);
        if(empty($action) or $action->extensionType == 'none') return array();

        $uiID    = $this->loadModel('workflowlayout')->getUIByDataID($moduleName, $methodName, $objectID);
        $fields  = $this->workflowaction->getFields($moduleName, $methodName, true, null, $uiID, $groupID);
        $layouts = $this->loadModel('workflowlayout', 'flow')->getFields($moduleName, $methodName, $uiID, $groupID);

        $extendFields = array();
        foreach($fields as $field)
        {
            if($field->buildin or !$field->show or !isset($layouts[$field->field])) continue;
            $extendFields[] = $field;
        }

        return $extendFields;
    }

    /**
     * 生成表单字段的控件。
     * Generate the control of the form field.
     *
     * @param  object $field 字段对象。
     * @param  string $mode  页面模式。single: 单体操作页面；batch: 批量操作页面。
     * @access public
     * @return string|array
     */
    public function buildFormControl($field, $mode = 'single')
    {
        $controlID     = $field->module . '_' . $field->field;
        $numberControl = array('integer', 'decimal', 'formula');

        if($field->control == 'date') return array('control' => 'datePicker', 'id' => $controlID);

        if($field->control == 'datetime') return array('control' => 'datetimePicker', 'id' => $controlID);

        if($field->control == 'select' || ($field->control == 'radio' && $mode == 'batch')) return array('control' => 'picker', 'id' => $controlID);

        if($field->control == 'multi-select' || ($field->control == 'checkbox' && $mode == 'batch')) return array('control' => 'picker', 'id' => $controlID, 'multiple' => true);

        if($field->control == 'radio') return array('control' => 'radioList', 'id' => $controlID, 'inline' => true);

        if($field->control == 'checkbox') return array('control' => 'checkList', 'id' => $controlID, 'inline' => true, 'name' => $field->field . '[]');

        if($field->control == 'textarea') return array('control' => 'textarea', 'id' => $controlID, 'rows' => 5);

        if($field->control == 'richtext') return array('control' => 'editor', 'id' => $controlID, 'rows' => 5);

        if($field->control == 'file') return array('control' => 'fileSelector', 'id' => $controlID);

        if(in_array($field->control, $numberControl))
        {
            $field = $this->loadModel('workflowfield')->processNumberField($field);
            return array('control' => 'input', 'id' => $controlID, 'type' => 'number', 'class' => 'text-right', 'max' => (string)$field->max, 'min' => (string)$field->min, 'step' => (string)$field->step);
        }

        return array('control' => $field->control, 'id' => $controlID);
    }

    /**
     * 生成表单字段。
     * Generate the form fields.
     *
     * @param  zin\fieldList $fieldList   表单字段列表。
     * @param  array         $fields      主表字段配置。
     * @param  array         $childFields 子表字段配置。
     * @param  object        $data        主表数据对象。
     * @param  array         $childDatas  子表数据对象。
     * @access public
     * @return zin\fieldList
     */
    public function buildFormFields($fieldList, $fields, $childFields = [], $data = null, $childDatas = [])
    {
        $this->loadModel('workflowhook');
        $wrapControl  = array('textarea', 'richtext', 'file');
        $notEmptyRule = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');

        foreach($fields as $key => $field)
        {
            if(!$field->show) continue;

            $required = ($notEmptyRule && (strpos(",{$field->layoutRules},", ",{$notEmptyRule->id},") !== false) || strpos(",{$field->rules},", ",{$notEmptyRule->id},") !== false) || !empty($field->required);

            if(isset($childFields[$field->field]))
            {
                $items = array_filter(array_map(function($subField)
                {
                    if($subField->show) return $subField;
                }, $childFields[$field->field]));

                $fieldList->field($field->field)
                    ->label($field->name, $field->control == 'picker' ? array('required' => $required) : null)
                    ->className('table-child')
                    ->control('flowSubTable')
                    ->required($field->control != 'picker' ? $required : false)
                    ->readonly((bool)$field->readonly)
                    ->disabled((in_array($field->control, array('checkbox', 'radio')) && (bool)$field->readonly))
                    ->items($items)
                    ->width('full')
                    ->wrapBefore(true)
                    ->value(zget($childDatas, $field->field, []))
                    ->placeholder(isset($field->placeholder) ? $field->placeholder : '');
            }
            else
            {
                $control = $this->buildFormControl($field);
                $value   = isset($data->{$field->field}) ? $data->{$field->field} : (isset($field->defaultValue) && $field->defaultValue != '' ? $field->defaultValue : $field->default);
                if($field->control == 'file' && !empty($data->{$field->field . 'files'}))
                {
                    if(!isset($data->{$field->field . 'files'}))
                    {
                        $files  = $this->loadModel('file')->getByObject($field->module, $data->id);
                        $fileFields = $this->dao->select('field')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($field->module)->andWhere('control')->eq('file')->fetchPairs();
                        foreach($fileFields as $fileField)
                        {
                            $filesName = "{$fileField}files";
                            $data->$filesName = array();
                            foreach($files as $file)
                            {
                                $file->size = (int)$file->size;
                                if($file->extra == $fileField)
                                {
                                    if(!isset($file->name)) $file->name = $file->title;
                                    $data->{$filesName}[] = $file;
                                }
                            }
                        }
                    }
                    $control['defaultFiles'] = $data->{$field->field . 'files'};
                    $control['extra']        = $field->field;
                }
                if($field->field == 'reviewResult')  $value = $field->defaultValue;
                if(!$value && $field->defaultValue)  $value = $field->defaultValue;

                if(in_array($value, $this->config->flow->variables)) $value = $this->workflowhook->getParamRealValue($value);

                $options     = zget($field, 'options', array());
                $setRequired = $field->control != 'select' ? $required : false;
                if($field->readonly) $setRequired = true;

                $fieldList->field($field->field)
                    ->label($field->name, $field->control == 'select' ? array('required' => $required) : null)
                    ->control($control)
                    ->required($setRequired)
                    ->readonly((bool)$field->readonly)
                    ->disabled((in_array($field->control, array('checkbox', 'radio')) && (bool)$field->readonly))
                    ->items(array_filter($options))
                    ->width(in_array($field->control, $wrapControl) ? 'full' : null)
                    ->wrapBefore(in_array($field->control, $wrapControl))
                    ->value($value)
                    ->placeholder(isset($field->placeholder) ? $field->placeholder : '');
            }
        }

        return $fieldList;
    }

    /**
     * 生成数据表格的列配置。
     * Generate the column configuration of the data table.
     *
     * @param  array $fields  字段列表。
     * @param  array $menu    菜单列表。
     * @param  array $actions 动作列表。
     * @param  bool  $show    是否显示。
     * @access public
     * @return array
     */
    public function buildDtableCols($fields, $menu = array(), $actions = array(), $show = true)
    {
        $this->loadModel('datatable');
        $datatableId = $this->app->rawModule . 'Browse';
        if(isset($this->config->datatable->$datatableId->cols)) $setting = json_decode($this->config->datatable->$datatableId->cols, true);

        $cols = array();
        foreach($fields as $field)
        {
            if(isset($field->show) && !$field->show) continue;
            if($field->control == 'file') continue;
            if(!$actions && $field->field == 'actions') continue;
            if(isset($setting[$field->field]) && empty($setting[$field->field]['show'])) continue;

            $cols[$field->field]['name']     = $field->field;
            $cols[$field->field]['title']    = $field->name;
            $cols[$field->field]['width']    = (empty($field->width) || $field->width == 'auto') ? '120' : $field->width;
            $cols[$field->field]['sortType'] = true;
            $cols[$field->field]['show']     = $show;
            $cols[$field->field]['align']    = in_array($field->position, array('left', 'center', 'right')) ? $field->position : 'left';

            if(isset($setting[$field->field]))
            {
                $cols[$field->field]['width'] = $setting[$field->field]['width'];
                if(isset($setting[$field->field]['extraWidth'])) $cols[$field->field]['extraWidth'] = $setting[$field->field]['extraWidth'];
                if(isset($setting[$field->field]['order']))      $cols[$field->field]['order']      = $setting[$field->field]['order'];
            }

            if(!empty($field->options)) $cols[$field->field]['map'] = $field->options;

            switch($field->control)
            {
                case 'select':
                case 'radio':
                    if(empty($col[$field->field]['map'])) $cols[$field->field]['map'] = $this->loadModel('workflowfield')->getFieldOptions($field);
                    break;
                case 'multi-select':
                case 'checkbox':
                    if(empty($col[$field->field]['map'])) $cols[$field->field]['map'] = $this->loadModel('workflowfield')->getFieldOptions($field);
                    $cols[$field->field]['type'] = 'category';
                    break;
                case 'richtext':
                    $cols[$field->field]['modifier'] = 'strip_tags';
                    break;
                case 'date':
                case 'datetime':
                    $cols[$field->field]['type'] = $field->control;
                    break;
                case 'int':
                case 'decimal':
                    $cols[$field->field]['type'] = 'number';
                    break;
                default:
                    $cols[$field->field]['type'] = 'text';
                    break;
            }

            if($field->field == 'id')
            {
                $cols[$field->field]['type'] = 'checkID';
                if(common::hasPriv($field->module, 'view'))
                {
                    $cols[$field->field]['link']     = array('url' => array('module' => $field->module, 'method' => 'view', 'params' => 'taskID={id}'));
                    $cols[$field->field]['data-app'] = $this->app->tab;
                }
            }

            if($field->field == 'actions')
            {
                if((int)$field->width > 0)
                {
                    $width = $field->width;
                }
                else
                {
                    $width = 0;
                    foreach($menu as $menuKey => $action)
                    {
                        if($menuKey === 'more')
                        {
                            $width += 20;
                        }
                        else
                        {
                            if(strpos($action, '|') !== false)
                            {
                                $maxStrLength = 0;
                                $multipleActions = explode('|', $action);
                                foreach($multipleActions as $multipleAction)
                                {
                                    $actionConfig = zget($actions, $multipleAction, '');
                                    $strLength    = !empty($actionConfig['text']) ? (int)mb_strlen($actionConfig['text'], 'utf-8') : 0;
                                    if($maxStrLength < $strLength) $maxStrLength = $strLength;
                                }

                                $width += $maxStrLength > 3 ? 60 : $strLength * 20;
                            }
                            else
                            {
                                $actionConfig = zget($actions, $action, '');
                                $strLength    = !empty($actionConfig['text']) ? (int)mb_strlen($actionConfig['text'], 'utf-8') : 0;
                                $width       += $strLength > 3 ? 60 : $strLength * 20;
                            }
                        }
                    }

                    $width += 24;
                }

                $cols[$field->field]['type']     = 'actions';
                $cols[$field->field]['menu']     = $menu;
                $cols[$field->field]['list']     = $actions;
                $cols[$field->field]['width']    = $width ? $width : 120;
                $cols[$field->field]['sortType'] = false;
            }
        }

        return $cols;
    }

    /**
     * Build extend fields for view page use zin setting.
     *
     * @param  array   $fields
     * @param  object  $object
     * @param  string  $position  basic|info
     * @access public
     * @return array
     */
    public function buildExtendZinValue($fields, $object = null, $position = 'basic')
    {
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;

        if($moduleName == 'projectstory' && $object)
        {
            $moduleName = $object->type;
        }
        elseif($moduleName == 'execution' && $methodName == 'storyview' && $object)
        {
            $moduleName = $object->type;
            $methodName = 'view';
        }

        $groupID = $this->loadModel('workflowgroup')->getGroupIDByData($moduleName, $object);
        $action  = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName, $groupID);
        if(empty($action) or $action->extensionType == 'none') return $fields;

        $flow         = $this->loadModel('workflow')->getByModule($moduleName);
        $uiID         = empty($object) ? 0 : $this->loadModel('workflowlayout')->getUIByData($flow->module, $action->action, $object);
        $extendFields = $this->workflowaction->getPageFields($flow->module, $action->action, true, $object, $uiID, $groupID);
        $layouts      = $this->loadModel('workflowlayout')->getFields($moduleName, $methodName, $uiID, $groupID);

        $items = '';
        foreach($layouts as $fieldKey => $fieldName)
        {
            $field = $extendFields[$fieldKey];
            if($field->buildin or !$field->show) continue;
            if($position != 'all' and $field->position != $position) continue;

            if($position == 'basic') $items   .= '<div class="datalist-item"><div class="datalist-item-label">' . $field->name . '</div><div class="datalist-item-content">' . $this->getFieldValue($field, $object). '</div></div>';
            if($position == 'info')  $fields[] = zin\setting()->title($field->name)->control('html')->content($this->getFieldValue($field, $object));
        }

        if($items && $position == 'basic') $fields[] = zin\setting()->group('sideExtends')->title($this->lang->extInfo)->control('html')->content("<div class='datalist'>{$items}</div>");

        return $fields;
    }

    /**
     * Build extend fields for view page use html.
     *
     * @param  object $object
     * @param  string $position  basic|info
     * @access public
     * @return string
     */
    public function buildExtendHtmlValue($object, $position = 'basic')
    {
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;

        if($moduleName == 'projectstory' && $object)
        {
            $moduleName = $object->type;
        }
        elseif($moduleName == 'execution' && $methodName == 'storyview' && $object)
        {
            $moduleName = $object->type;
            $methodName = 'view';
        }
        elseif($moduleName == 'projectbuild')
        {
            $moduleName = 'build';
        }
        elseif($moduleName == 'projectrelease')
        {
            $moduleName = 'release';
        }

        $groupID = $this->loadModel('workflowgroup')->getGroupIDByData($moduleName, $object);
        $action  = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName, $groupID);
        if(empty($action) or $action->extensionType == 'none') return '';

        $flow         = $this->loadModel('workflow')->getByModule($moduleName);
        $uiID         = empty($object) ? 0 : $this->loadModel('workflowlayout')->getUIByData($flow->module, $action->action, $object);
        $extendFields = $this->workflowaction->getPageFields($flow->module, $action->action, true, $object, $uiID, $groupID);
        $layouts      = $this->loadModel('workflowlayout')->getFields($moduleName, $methodName, $uiID, $groupID);

        $headingStyle = '';
        if($position == 'info') $headingStyle = 'background-color:var(--color-gray-100); color:var(--color-gray-900);';

        $items = '';
        foreach($layouts as $fieldKey => $fieldName)
        {
            $field = $extendFields[$fieldKey];
            if($field->buildin or !$field->show) continue;
            if($position != 'all' and $field->position != $position) continue;

            $content = $this->getFieldValue($field, $object);
            $items  .= "<div class='item mb-3'><span class='text-gray'>{$field->name}</span><span class='ml-2'>{$content}</span></div>";
        }
        if(empty($items)) return '';

        $html  = '';
        $html .= "<div class='panel rounded ring-0 bg-canvas mt-4 extendBox shadow'>";
        $html .= "<div class='panel-heading' style='{$headingStyle}'><div class='panel-title'>{$this->lang->extInfo}</div></div><div class='panel-body'>";
        $html .= $items;
        $html .= '</div></div>';

        return $html;
    }

    /**
     * Get approval reviewer datas.
     *
     * @param  string $object
     * @param  object $data
     * @access public
     * @return array
     */
    public function getApprovalReviewerDatas($object = '', $data = null)
    {
        $extra       = '';
        $productID   = !empty($data->product) ? $data->product : 0;
        $projectID   = !empty($data->project) ? $data->project : 0;
        $executionID = !empty($data->execution) ? $data->execution : 0;

        if($object == 'execution') $executionID = $data->id;
        if($object == 'project')   $projectID   = $data->id;
        if($object == 'product')   $productID   = $data->id;
        if($object == 'charter')   $extra       = $this->loadModel('charter')->getApprovalFlowExtra($data);

        $groupID = $this->loadModel('workflowgroup')->getGroupIDByData($object, $data);
        $flowID  = $this->loadModel('approvalflow')->getFlowIDByObject($groupID, $object, $extra);

        $users = $this->loadModel('user')->getDeptPairs('nodeleted|noclosed');
        $nodes = $this->loadModel('approval')->getNodesToConfirm($flowID, 0, $projectID, $productID, $executionID, $data);

        $datas = array();
        foreach($nodes as $node)
        {
            $rangeUsers = $users;
            if(isset($node['range']['reviewer']))
            {
                $rangeUsers = array();
                foreach($node['range']['reviewer'] as $range) $rangeUsers[$range] = zget($users, $range, array());
            }

            $reviewers = array();
            foreach(array('appointees', 'role', 'self', 'position', 'upLevel', 'superior', 'productRole', 'projectRole', 'executionRole') as $key)
            {
                if(isset($node[$key]['reviewer']))
                {
                    foreach($node[$key]['reviewer'] as $reviewer) $reviewers[$reviewer] = zget($users, $reviewer);
                }
            }

            $data = new stdclass();
            $data->approval_node     = $node['title'];
            $data->approval_id       = $node['id'];
            $data->approval_reviewer = '';
            $data->approval_ccer     = '';
            $data->types             = $node['types'];
            $data->reviewers         = '';
            $data->ccers             = '';
            $data->reviewerItems     = array();
            $data->ccerItems         = array();

            if(in_array('reviewer', $node['types']))
            {
                if($reviewers) $data->reviewers .= $this->lang->approval->otherReviewer;

                $reviewerItems = array();
                foreach(array_diff(array_intersect_key($users, $rangeUsers), $reviewers) as $account => $realname)
                {
                    $reviewerItems[] = array('text' => $realname, 'value' => $account);
                }

                $data->reviewerItems = $reviewerItems;
            }
            if($reviewers) $data->reviewers .= join(',', $reviewers);

            $ccRangeUsers = $users;
            if(isset($node['range']['ccer']))
            {
                $ccRangeUsers = array();
                foreach($node['range']['ccer'] as $range) $ccRangeUsers[$range] = zget($users, $range, array());
            }

            $ccers = array();
            foreach(array('appointees', 'role', 'position', 'upLevel', 'superior') as $key)
            {
                if(isset($node[$key]['ccer']))
                {
                    foreach($node[$key]['ccer'] as $ccer) $ccers[$ccer] = zget($users, $ccer);
                }
            }

            if(in_array('ccer', $node['types']))
            {
                if($ccers) $data->ccers .= $this->lang->approval->otherCcer;

                $ccerItems = array();
                foreach(array_diff(array_intersect_key($users, $ccRangeUsers), $ccers) as $account => $realname) $ccerItems[] = array('text' => $realname, 'value' => $account);
                $data->ccerItems = $ccerItems;
            }
            if($ccers) $data->ccers .= join(',', $ccers);

            $datas[] = $data;
        }

        return $datas;
    }
}
