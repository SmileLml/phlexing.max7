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
     * Ajax get column form html.
     *
     * @param  int    $pivotID
     * @access public
     * @return string
     */
    public function ajaxGetColumnForm($pivotID)
    {
        $columns       = $this->post->columns;
        $fieldSettings = $this->post->fieldSettings;
        $langs         = $this->post->langs;
        $fieldPairs    = $this->pivot->getCommonColumn($fieldSettings, $langs);

        $htmls = array();
        foreach($columns as $index => $column)
        {
            $html = array();

            $html['columnIndex']    = sprintf($this->lang->pivot->columnIndex, $index + 1);
            $html['fieldSelect']    = html::select('column', arrayUnion(array('' => ''), $fieldPairs), zget($column, 'field', ''), "class='form-control picker-select' data-placeholder='{$this->lang->pivot->step2->selectField}'");
            $html['sliceField']     = html::select('slice', arrayUnion($this->lang->pivot->step2->sliceFieldList, $fieldPairs), zget($column, 'slice', 'noSlice'), "class='form-control picker-select'");
            $html['calcMode']       = html::select('stat',  $this->lang->pivot->step2->statList, zget($column, 'stat', ''), "class='form-control picker-select required' data-placeholder='{$this->lang->pivot->must}'");
            $html['showMode']       = html::select('showMode', $this->lang->pivot->step2->showModeList, zget($column, 'showMode', 'default'), "class='form-control picker-select'");
            $html['monopolize']     = html::checkbox('monopolize', array('1' => $this->lang->pivot->monopolize), zget($column, 'monopolize', ''));
            $html['monopolizeHide'] = zget($column, 'showMode', 'default') == 'default' ? 'hidden' : '';
            $html['showTotal']      = html::select('showTotal', $this->lang->pivot->step2->showTotalList, zget($column, 'showTotal', 'noShow'), "class='form-control picker-select'");
            $html['fieldHide']      = zget($column, 'slice', 'noSlice') == 'noSlice' ? 'hide' : '';
            $html['showOrigin']     = zget($column, 'showOrigin', '') == '1' ? true : false;

            $htmls[] = $html;
        }

        echo json_encode($htmls);
    }
}
