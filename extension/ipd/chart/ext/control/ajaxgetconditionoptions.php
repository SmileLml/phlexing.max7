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
    /**
     * Ajax get condition options.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function ajaxGetConditionOptions()
    {
        $html  = "<div class='conditionGroup' style='display:flex'>";
        $html .= html::select('operator', $this->config->bi->conditionList, '', "class='form-control picker-select' onchange='changeCondition(this,this.value)'");
        $html .= html::input('value', '', "class='form-control' onchange='changeConditionValue(this,this.value)'");
        $html .= "<div/>";
        return print($html);
    }
}
