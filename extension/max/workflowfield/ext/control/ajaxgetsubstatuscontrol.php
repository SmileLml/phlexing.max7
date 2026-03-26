<?php
class workflowfield extends control
{
    /**
     * Ajax get substatus control
     *
     * @param  string    $module
     * @param  string    $parent
     * @param  string $subStatus
     * @param  string $wrap
     * @access public
     * @return void
     */
    public function ajaxGetSubstatusControl($module, $parent, $status = '', $wrap = 'td')
    {
        $subStatus = $this->workflowfield->getByField($module, 'subStatus', $mergeOptions = false);
        $statusList = zget($subStatus->options, $parent, '');
        if(!isset($statusList[$status])) $status = '';
        if(empty($statusList)) die('');
        if($wrap == 'td') echo "<tr><th>" . $this->lang->workflowfield->subStatus . "</th><td>";
        echo html::select('subStatus', $statusList, $status, "class='form-control chosen select'");
        if($wrap == 'td') echo "</td></tr>";
    }
}
