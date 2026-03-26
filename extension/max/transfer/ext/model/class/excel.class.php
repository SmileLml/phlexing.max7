<?php
class excelTransfer extends transferModel
{
    /**
     * 读取excel并格式化数据。
     * Read excel and format data.
     *
     * @param  string $module
     * @param  int    $pagerID
     * @param  string $insert
     * @param  string $filter
     * @access protected
     * @return object|false
     */
    public function readExcel($module = '', $pagerID = 1, $insert = '', $filter = '')
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','100');

        /* 格式化数据。*/
        /* Formatting excel data. */
        $formatDatas = $this->format($module, $filter);
        if(dao::isError()) return false;
        if(!isset(reset($formatDatas)->id)) $insert = '1';

        /* 获取分页后的数据。*/
        /* Get page by datas. */
        $datas        = $this->getPageDatas($formatDatas, $pagerID);
        $suhosinInfo  = $this->checkSuhosinInfo($datas->datas);
        $importFields = !empty($_SESSION[$module . 'TemplateFields']) ? $_SESSION[$module . 'TemplateFields'] : $this->config->$module->templateFields;

        $datas->requiredFields = $this->config->$module->create->requiredFields;
        $datas->allPager       = isset($datas->allPager) ? $datas->allPager : 1;
        $datas->pagerID        = $pagerID;
        $datas->isEndPage      = $pagerID >= $datas->allPager;
        $datas->maxImport      = $this->maxImport;
        $datas->dataInsert     = $insert;
        $datas->fields         = $this->initFieldList($module, $importFields, false);
        $datas->suhosinInfo    = $suhosinInfo;
        $datas->module         = $module;

        return $datas;
    }

    /**
     * 检查suhosin信息。
     * Check suhosin info.
     *
     * @param  array  $datas
     * @access protected
     * @return string
     */
    private function checkSuhosinInfo($datas = array())
    {
        if(empty($datas)) return '';
        $current = (array)current($datas);

        /* Judge whether the editedTasks is too large and set session. */
        $countInputVars  = count($datas) * count($current); // Count all post datas
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) return extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);
        return '';
    }
}
