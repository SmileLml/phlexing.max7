<?php
/**
 * The control file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class chart extends control
{

    public function ajaxGetWaterpoloForm($chartID)
    {
        $chart = $this->chart->getByID($chartID);

        $defaultValues = isset($_POST['values'])        ? $this->post->values             : array();
        $fieldSettings = isset($_POST['fieldSettings']) ? $this->post->fieldSettings      : array();
        $langs         = isset($_POST['langs'])         ? $this->post->langs              : array();
        $sql           = isset($_POST['sql'])           ? base64_decode($this->post->sql) : array();

        $fieldPairs = array();
        $clientLang = $this->app->getClientLang();
        foreach($fieldSettings as $field => $fieldList)
        {
            $fieldObject  = $fieldList['object'];
            $relatedField = $fieldList['field'];

            $this->app->loadLang($fieldObject);
            $fieldPairs[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;

            if(!isset($langs[$field])) continue;
            if(!empty($langs[$field][$clientLang])) $fieldPairs[$field] = $langs[$field][$clientLang];
        }
        $fieldList = !empty($fieldPairs) ? $fieldPairs : $this->chart->getChartFieldList($chartID);
        $options   = arrayUnion(array('' => ''), $fieldList);

        $formHtml  = "<div class='table table-form table-waterpolo'><div class='hidden'>";
        $formHtml .= html::input('type', 'waterpolo', "class='form-control'");
        $formHtml .= "</div>";

        // molecule
        $formHtml .= "<div><div class='text-left text-molecule'>{$this->lang->chart->molecule}</div>";

        // goal and operate
        $goal = isset($defaultValues['goal']) ? $defaultValues['goal'] : '';
        $calc = isset($defaultValues['calc']) ? $defaultValues['calc'] : '';
        $calcList = $this->lang->chart->calcList;
        if(!isset($fieldSettings[$goal]) || $fieldSettings[$goal]['type'] != 'number') unset($calcList['sum']);

        $goalName = $goal ? (isset($langs[$goal][$clientLang]) ? $langs[$goal][$clientLang] : $fieldSettings[$goal]['name']) : '';
        $calcName = zget($this->lang->chart->calcList, $calc, '');

        $formHtml .= "<div class='background-grey'><div class='molecule-div'>";
        $formHtml .= "<div class='flex-div-15'>" . $this->lang->chart->goal . "</div>";
        $formHtml .= "<div class='flex-div-35'><div>" . html::select('goal', $options, $goal, "class='form-control picker-select required' onchange='waterpoloChange(this)' data-placeholder='{$this->lang->chart->chooseField}'") . "</div></div>";
        $formHtml .= "<div class='flex-div-10'>" . $this->lang->chart->operate."</div>";
        $formHtml .= "<div class='flex-div-35'><div>" . html::select('calc', $calcList, $calc, "class='form-control picker-select required' onchange='waterpoloChange(this)' data-placeholder='{$this->lang->chart->chooseCalc}'") . "</div></div>";
        $formHtml .= "</div>";

        // field,condition and value
        $rowCount = isset($defaultValues['conditions']) ? count($defaultValues['conditions']) : 1;
        for($index = 0; $index < $rowCount; $index ++)
        {
            $hidden        = $index == 0 ? 'hidden' : '';
            $conditionInfo = isset($defaultValues['conditions'][$index]) ? $defaultValues['conditions'][$index] : array('field' => '', 'condition' => 'eq', 'value' => '');

            $field         = $conditionInfo['field'];
            $condition     = $conditionInfo['condition'];
            $value         = $conditionInfo['value'];
            $fieldSetting  = $field ? $fieldSettings[$field] : array();
            $valueOptions  = $this->chart->getSQLFieldOptions($sql, $fieldSetting);

            $formHtml .= "<div class='molecule-div condition-div'>";
            $formHtml .= "<div class='text-condition flex-div-15'>" . $this->lang->chart->condition . "</div>";
            $formHtml .= "<div class='flex-div-30'><div>" . html::select('field[]', $options, $field, "class='form-control multi-field picker-select required' onchange='waterpoloChange(this)' data-placeholder='{$this->lang->chart->chooseCondition}'") . "</div></div>";
            $formHtml .= "<div class='hidden'>" . html::input('condition[]', $condition, "class='form-control'") . "</div>";
            $formHtml .= "<div class='flex-div-5'>" . $this->lang->chart->eq."</div>";
            $formHtml .= "<div class='flex-div-30'><div>" . html::select('value[]', $valueOptions, $value, "class='form-control multi-value picker-select required' onchange='waterpoloChange(this)'") . "</div></div>";

            $formHtml .= "<div class='btn-list text-left flex-div-20'>";
            $formHtml .= "<a href='javascript:;' onclick='addWaterpoloRow(this)' class='btn btn-link add-condition addRow'><i class='icon-plus'></i></a>";
            $formHtml .= "<a href='javascript:;' onclick='deleteWaterpoloRow(this)' class='btn btn-link $hidden del-condition delRow'><i class='icon-close'></i></a>";
            $formHtml .= "</div>";
            $formHtml .= "</div>";
        }

        // hidden condition tpl
        $formHtml .= "<div id='conditionTpl' class='hidden'>";
        $formHtml .= "<div class='text-condition flex-div-15'>" . $this->lang->chart->condition . "</div>";
        $formHtml .= "<div class='flex-div-30'><div>" . html::select('field[]', $options, '', "class='form-control multi-field required' onchange='waterpoloChange(this)'") . "</div></div>";
        $formHtml .= "<div class='hidden'>" . html::input('condition[]', 'eq', "class='form-control'") . "</div>";
        $formHtml .= "<div class='flex-div-5'>" . $this->lang->chart->eq."</div>";
        $formHtml .= "<div class='flex-div-30'><div>" . html::select('value[]', array(), '', "class='form-control multi-value required' onchange='waterpoloChange(this)'") . "</div></div>";

        $formHtml .= "<div class='btn-list text-left flex-div-20'>";
        $formHtml .= "<a href='javascript:;' onclick='addWaterpoloRow(this)' class='btn btn-link add-condition addRow'><i class='icon-plus'></i></a>";
        $formHtml .= "<a href='javascript:;' onclick='deleteWaterpoloRow(this)' class='btn btn-link del-condition delRow'><i class='icon-close'></i></a>";
        $formHtml .= "</div>";
        $formHtml .= "</div>";

        // end div with <div class='background-grey'>
        $formHtml .= "</div>";

        // denominator
        $formHtml .= "<div class='text-left text-denominator'>{$this->lang->chart->denominator}";
        $formHtml .= "<i class='icon icon-help denominator-tip' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='{$this->lang->chart->denominatorTip['tip']}' data-delay='100'></i>";
        $formHtml .= "</div>";

        // denominatorTip
        $denominatorTip = ($goalName and $calcName) ? $this->chart->getDenominatorTip($goalName, $calcName) : $this->lang->chart->denominatorTip['empty'];
        $formHtml .= "<div class='background-grey'><div class='molecule-div'>";
        $formHtml .= "<div class='text-left text-denominatorTip'>{$denominatorTip}</div>";
        $formHtml .= "</div>";

        $formHtml .= "</div>";

        return print($formHtml);
    }
    /**
     * Ajax get type form html.
     *
     * @param  int    $chartID
     * @param  string $chartType
     * @access public
     * @return string
     */
    public function ajaxGetTypeForm($chartID, $chartType)
    {
        if($chartType == 'waterpolo') return $this->ajaxGetWaterpoloForm($chartID);

        $chart = $this->chart->getByID($chartID);

        $defaultValues = isset($_POST['values'])        ? $this->post->values        : array();
        $fieldSettings = isset($_POST['fieldSettings']) ? $this->post->fieldSettings : array();
        $langs         = isset($_POST['langs'])         ? $this->post->langs         : array();

        $fieldPairs = array();
        $clientLang = $this->app->getClientLang();
        foreach($fieldSettings as $field => $fieldList)
        {

            $fieldObject  = $fieldList['object'];
            $relatedField = $fieldList['field'];

            if($this->config->edition != 'ipd' && !empty($this->config->chart->ipdFields[$fieldObject]) && in_array($field, $this->config->chart->ipdFields[$fieldObject])) continue;

            $this->app->loadLang($fieldObject);
            $fieldPairs[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;

            if(!isset($langs[$field])) continue;
            if(!empty($langs[$field][$clientLang])) $fieldPairs[$field] = $langs[$field][$clientLang];
        }

        $formHtml  = "<table class='table table-form'><tr class='hidden'><td>";
        $formHtml .= html::input('type', $chartType, "class='form-control'");
        $formHtml .= "</td></tr>";

        $formSetting = $this->config->chart->settings[$chartType];
        foreach($formSetting as $th => $thConfig)
        {
            $thTitle = $this->lang->chart->{$chartType}->{$th};

            /* Compatible with charttpl, these variables are required in charttpl code. */
            $fieldList  = !empty($fieldPairs) ? $fieldPairs : $this->chart->getChartFieldList($chartID);
            $isMultiCol = (isset($this->config->chart->multiColumn[$chartType]) and $this->config->chart->multiColumn[$chartType] == $th);

            $rowCount = 1;
            if($isMultiCol)
            {
                foreach($thConfig as $thSetting)
                {
                    $field = $thSetting['field'];
                    if(!isset($defaultValues[$field])) continue;
                    $rowCount = max($rowCount, count($defaultValues[$field]));
                }
            }

            for($index = 0; $index < $rowCount; $index += 1)
            {
                $title     = $index == 0 ? $thTitle : '';
                $formHtml .= "<tr><th class='w-60px'>{$title}</th>";
                foreach($thConfig as $thSetting)
                {
                    $viewDir = $this->app->getExtensionRoot() . $this->config->edition . '/chart/ext/view';
                    $oldDir  = getcwd();
                    chdir($viewDir);
                    ob_start();
                    include 'charttpl.html.php';
                    $content = ob_get_contents();
                    ob_end_clean();
                    chdir($oldDir);

                    $formHtml .= $content;
                }

                if($isMultiCol)
                {
                    $hidden = $rowCount == 1 ? 'hidden' : '';
                    $formHtml .= "<td colspan='2' class='btn-list text-left'>";
                    $formHtml .= "<a href='javascript:;' onclick='addRow(this, " . '"' . $th .'"' .")' class='btn btn-link add-{$th} addRow'><i class='icon-plus'></i></a>";
                    $formHtml .= "<a href='javascript:;' onclick='deleteRow(this, " . '"' . $th .'"' .")' class='btn btn-link $hidden del-{$th} delRow'><i class='icon-close'></i></a>";
                    $formHtml .= "</td>";
                }
                $formHtml .= '</tr>';

                /* Judge whether show dateGroup. */
                foreach($thConfig as $thSetting)
                {
                    $field = $thSetting['field'];
                    if($this->config->chart->dateGroup[$chartType] != $field) continue;

                    $groupField = $chartType == 'pie' ? 'group' : 'xaxis';
                    $default    = isset($defaultValues[$groupField][0]['field']) ? $defaultValues[$groupField][0]['field'] : '';
                    $dateGroup  = isset($defaultValues[$groupField][0]['group']) ? $defaultValues[$groupField][0]['group'] : '';
                    $dateClass  = (!empty($default) and $fieldSettings[$default]['type'] == 'date') ? '' : 'hidden';

                    $fieldName = 'dateGroup';
                    $formHtml .= "<tr id='dateGroup' class='$dateClass'><th class='w-80px'></th><td colspan='{$thSetting['col']}'>";
                    $formHtml .= html::radio('dateGroup', $this->lang->chart->dateGroup, $dateGroup, "onclick='toggleRadioSelection(this, this.value)'");
                    $formHtml .= "</td></tr>";
                }
            }
        }

        if(in_array($chartType, $this->config->chart->canLabelRotate))
        {
            $formHtml .= "<tr><th class='w-60px'>{$this->lang->chart->rotateLabel}</th>";
            $formHtml .= "<td><i class='icon icon-help rotate-tip' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='{$this->lang->chart->rotateLabelTip}' data-delay='100'></i></td>";
            $formHtml .= "</tr>";

            $rotateX = (isset($defaultValues['rotateX']) and $defaultValues['rotateX'] == 'use') ? '0' : '';
            $rotateY = (isset($defaultValues['rotateY']) and $defaultValues['rotateY'] == 'use') ? '0' : '';
            $formHtml .= "<tr><th></th>";
            $formHtml .= "<td colspan='2'>" . html::checkBox('rotateX', $this->lang->chart->rotateXaxis, $rotateX, "onchange='pieChange(0, \"rotateX\")'") . "</td>";
            $formHtml .= "<td colspan='2'>" . html::checkBox('rotateY', $this->lang->chart->rotateYaxis, $rotateY, "onchange='pieChange(0, \"rotateY\")'") . "</td>";
            $formHtml .= "</tr>";
        }

        $formHtml .= "</table>";

        if(isset($this->config->chart->multiColumn[$chartType]))
        {
            $columnName   = $this->config->chart->multiColumn[$chartType];
            $multiSetting = $this->config->chart->settings[$chartType][$columnName];

            $formHtml .= "<table class='hidden'>";
            $formHtml .= "<tr class='hidden' id='{$chartType}Column'>";
            $formHtml .= "<th class='w-80px'></th>";

            foreach($multiSetting as $th => $thSetting)
            {
                $field       = $thSetting['field'];
                $required    = $thSetting['required'] ? 'required' : '';
                $fieldList   = !empty($fieldPairs) ? $fieldPairs : $this->chart->getChartFieldList($chartID);
                $options     = $thSetting['options'] == 'field' ? arrayUnion(array('' => ''), $fieldList) : $this->lang->chart->{$thSetting['options']};
                $placeholder = $thSetting['placeholder'];
                $default     = '';

                $formHtml .= "<td colspan='{$thSetting['col']}'>";
                /* Picker name is hidden-picker-select, avoid a picker initialized twice. */
                $formHtml .=   html::select($field . '[]', $options, $default, "class='form-control multi-$field hidden-picker-select $required' $required onchange='pieChange(this.value, \"$field\", true, 1)' data-placeholder='$placeholder'");
                $formHtml .= "</td>";
            }
            $formHtml .= "<td colspan='2' class='btn-list text-left'>";
            $formHtml .= "<a href='javascript:;' onclick='addRow(this, \"$columnName\")' class='btn btn-link add-$columnName addRow'><i class='icon-plus'></i></a>";
            $formHtml .= "<a href='javascript:;' onclick='deleteRow(this, \"$columnName\")' class='btn btn-link del-$columnName delRow'><i class='icon icon-close'></i></a>";
            $formHtml .= "</td>";
            $formHtml .= "</tr>";
            $formHtml .= "</table>";
        }

        return print($formHtml);
    }
}
