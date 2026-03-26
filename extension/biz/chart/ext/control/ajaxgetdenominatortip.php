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
    public function ajaxGetDenominatorTip()
    {
        $setting       = $this->post->setting;
        $fieldSettings = $this->post->fieldSettings;
        $langs         = $this->post->langs;
        $clientLang    = $this->app->getClientLang();

        $goal     = $setting['goal'];
        $calc     = $setting['calc'];
        $goalName = isset($langs[$goal][$clientLang]) ? $langs[$goal][$clientLang] : $fieldSettings[$goal]['name'];
        $calcName = zget($this->lang->chart->calcList, $calc, '');

        echo $this->chart->getDenominatorTip($goalName, $calcName);
    }
}
