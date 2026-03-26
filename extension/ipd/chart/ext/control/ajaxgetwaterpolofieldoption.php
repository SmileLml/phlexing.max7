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
    public function ajaxGetWaterpoloFieldOption()
    {
        $sql          = trim($this->post->sql);
        $fieldSetting = $this->post->fieldSetting;

        $options = $this->chart->getSQLFieldOptions($sql, $fieldSetting);

        return print(html::select('value[]', $options, '', "class='form-control multi-value picker-select required' onchange='waterpoloChange(this)'"));
    }
}
