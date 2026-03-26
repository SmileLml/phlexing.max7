<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * Ajax get field select html.
     *
     * @access public
     * @return string
     */
    public function ajaxGetResultForm()
    {
        $fieldSettings = $this->post->fieldSettings;
        $langs         = $this->post->langs;
        $filters       = $this->post->filters;
        $sql           = $this->post->sql;
        $clientLang    = $this->app->getClientLang();

        $this->loadModel('workflowfield');
        $fieldPairs     = array();
        $workflowFields = array();
        foreach($fieldSettings as $field => $fieldList)
        {
            $fieldObject  = $fieldList['object'];
            $relatedField = $fieldList['field'];

            $this->app->loadLang($fieldObject);
            $fieldPairs[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;

            if(!isset($workflowFields[$fieldObject])) $workflowFields[$fieldObject] = $this->workflowfield->getFieldPairs($fieldObject);
            if(isset($workflowFields[$fieldObject][$relatedField])) $fieldPairs[$field] = $workflowFields[$fieldObject][$relatedField];

            if(!isset($langs[$field])) continue;
            if(!empty($langs[$field][$clientLang])) $fieldPairs[$field] = $langs[$field][$clientLang];
        }

        $htmls = array();
        foreach($filters as $filter)
        {
            $field   = $filter['field'];
            $type    = $filter['type'];
            $default = isset($filter['default']) ? $filter['default'] : '';

            $saveAs      = isset($filter['saveAs']) ? $filter['saveAs'] : '';
            $saveAsClass = $type == 'select' ? '' : 'hidden'; // 只有是选择的时候，才展示显示为

            $options = array();
            if($type == 'select')
            {
                $fieldSetting = $fieldSettings[$field];
                $options      = $this->pivot->getSysOptions(zget($fieldSetting, 'type', ''), zget($fieldSetting, 'object', ''), zget($fieldSetting, 'field', ''), $sql, zget($filter, 'saveAs', ''));
            }

            $filterHtml = array();

            /* field html */
            $filterHtml['field']  = html::select('field', $fieldPairs, $field, "class='form-control picker-select' onchange='methods.step3.result.changeField(this, this.value)'");
            $filterHtml['saveAs'] = html::select('saveAs', arrayUnion(array('' => ''), $fieldPairs), $saveAs, "class='form-control picker-select' onchange='methods.step3.result.changeSaveAs(this, this.value)'");
            $filterHtml['saveAsClass'] = $saveAsClass;

            /* default html */
            $filterHtml['default'] = '';

            if($type == 'input') $filterHtml['default'] .= html::input('default', $default, "class='form-control form-input' onchange='methods.step3.result.changeDefault(this,this.value)'");
            if($type == 'date' or $type == 'datetime')
            {
                if(empty($default)) $default = array('begin' => '', 'end' => '');
                $class = $type == 'date' ? 'form-date' : 'form-datetime';
                $filterHtml['default'] .= '<div class="input-group">';
                $filterHtml['default'] .= html::input('default[begin]', $default['begin'], "class='form-control $class default-begin' placeholder='{$this->lang->pivot->unlimited}' onchange='methods.step3.result.changeDefault(this,this.value)'");
                $filterHtml['default'] .= "<span class='input-group-addon fix-border borderBox' style='border-radius: 0px;'>{$this->lang->pivot->colon}</span>";
                $filterHtml['default'] .= html::input('default[end]', $default['end'], "class='form-control $class default-end' placeholder='{$this->lang->pivot->unlimited}' onchange='methods.step3.result.changeDefault(this,this.value)'");
                $filterHtml['default'] .= '</div>';
            }
            if($type == 'select') $filterHtml['default'] = html::select('default[]', $options, $default, "class='form-control form-select picker-select' onchange='methods.step3.result.changeDefault(this,this.value)' multiple");

            /* type html */
            $filterHtml['type'] = html::select('type', $this->lang->pivot->fieldTypeList, $type, "class='form-control picker-select' onchange='methods.step3.result.changeType(this, this.value)'");

            /* filter item html */
            $filterHtml['item'] = '';
            if($type == 'input') $filterHtml['item'] .= html::input('default', $default, "class='form-control form-input'");
            if($type == 'date' or $type == 'datetime')
            {
                if(empty($default)) $default = array('begin' => '', 'end' => '');
                $class = $type == 'date' ? 'form-date' : 'form-datetime';
                $filterHtml['item'] .= '<div class="input-group">';
                $filterHtml['item'] .= "<input type='text' name='default[begin]' id='default[begin]' value='{$default['begin']}' class='form-control $class default-begin' autocomplete='off' placeholder='{$this->lang->pivot->unlimited}' onchange='changeDefault(this, this.value)'>";
                $filterHtml['item'] .= '<span class="input-group-addon fix-border borderBox" style="border-radius: 0px;">' . $this->lang->pivot->colon . '</span>';
                $filterHtml['item'] .= "<input type='text' name='default[end]' id='default[end]' value='{$default['end']}' class='form-control $class default-end' autocomplete='off' placeholder='{$this->lang->pivot->unlimited}' onchange='changeDefault(this, this.value)'>";
                $filterHtml['item'] .= '</div>';
            }
            if($type == 'select') $filterHtml['item'] .= html::select('default', arrayUnion(array('' => ''), $options), $default, "class='form-control form-select picker-select' multiple");

            $htmls[] = $filterHtml;
        }

        echo json_encode($htmls);
    }
}
