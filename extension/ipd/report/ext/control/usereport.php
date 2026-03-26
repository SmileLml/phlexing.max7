<?php
/**
 * The control file of report module of zentaopms.
 *
 * @copyright   copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     zpl (http://zpl.pub/page/zplv12.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     report
 * @link        https://www.zentao.net
 */
helper::importControl('report');
class myReport extends report
{
    /**
     * Design report.
     *
     * @param  int    $reportID
     * @param  string $from
     * @access public
     * @return void
     */
    public function useReport($reportID = 0, $from = '')
    {
        if($from) $this->lang->navGroup->report = 'system';

        $report = $this->report->getReportByID($reportID);
        if(!$report) die(js::alert($this->lang->crystal->errorNoReport));

        $this->session->set('reportSQL', $report->sql);
        $this->session->set('reportParams', $report->params);
        $this->session->set('sqlVars', $report->vars);
        $this->session->set('sqlLangs', $report->langs);

        $sqlVarValues = array();
        $sqlVars      = json_decode($report->vars, true);
        if($sqlVars)
        {
            foreach($sqlVars['varName'] as $i => $varName)
            {
                $varType = ($sqlVars['requestType'][$i] == 'select') ? $sqlVars['selectList'][$i] : $sqlVars['requestType'][$i];
                $sqlVarValues[$varName] = isset($sqlVars['default'][$i]) ? $sqlVars['default'][$i] : '';
                if($varType == 'dept' and empty($sqlVarValues[$varName])) $sqlVarValues[$varName] = 0;
            }
        }
        $this->session->set('sqlVarValues', serialize($sqlVarValues));

        $module = (strpos($report->module, 'cmmi') !== false) ? 'cmmi' : '';
        die(js::locate(inlink('custom', "step={$report->step}&reportID=$reportID&from=$module"), $report->vars ? 'parent' : 'self'));
    }
}
